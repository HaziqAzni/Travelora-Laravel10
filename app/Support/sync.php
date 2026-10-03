<?php

use App\Models\Destination;
use App\Models\Hotel;

/**
 * Travelora - API sync engine (Eloquent version).
 */

function sync_destination_coords(?int $id = null, bool $force = false): array
{
    $query = Destination::query();
    if ($id !== null) {
        $query->where('id', $id);
    }

    $updated = 0;
    $skipped = 0;
    $failed  = [];

    foreach ($query->orderBy('id')->get() as $row) {
        if (!$force && $row->lat !== null && $row->lng !== null) {
            $skipped++;
            continue;
        }
        $geo = api_geocode($row->name . ', ' . $row->country);
        if (!$geo) {
            $failed[] = $row->name;
            continue;
        }
        $row->lat          = $geo['lat'];
        $row->lng          = $geo['lng'];
        $row->country_code = $row->country_code ?: $geo['country_code'];
        $row->wiki_title   = $row->wiki_title ?: $row->name;
        $row->source       = 'api';
        $row->api_synced_at = now();
        $row->save();
        $updated++;

        sleep(1); // Nominatim usage policy
    }

    return ['updated' => $updated, 'skipped' => $skipped, 'failed' => $failed];
}

function sync_osm_hotels(int $destinationId): array
{
    $dest = Destination::find($destinationId);
    if (!$dest) {
        return ['found' => 0, 'inserted' => 0, 'updated' => 0, 'error' => 'Destination not found'];
    }

    if ($dest->lat === null || $dest->lng === null) {
        sync_destination_coords($destinationId);
        $dest->refresh();
    }
    if ($dest->lat === null || $dest->lng === null) {
        return ['found' => 0, 'inserted' => 0, 'updated' => 0, 'error' => 'Could not geocode destination'];
    }

    $hotels = api_osm_hotels((float) $dest->lat, (float) $dest->lng, 5000, 25);
    if (!$hotels) {
        return ['found' => 0, 'inserted' => 0, 'updated' => 0, 'error' => null];
    }

    $images   = ['hotel-1', 'hotel-2', 'hotel-3', 'hotel-4', 'hotel-5', 'hotel-6'];
    $inserted = 0;
    $updated  = 0;

    foreach ($hotels as $h) {
        $seed    = crc32($h['name']);
        $stars   = $h['stars'] ?? (3 + ($seed % 3));
        $price   = round(40 + $stars * 35 + ($seed % 80), 2);
        $rating  = round(4.0 + ((crc32($h['name'] . 'r') % 10) / 10), 1);
        $reviews = 80 + (crc32($h['name'] . 'v') % 2200);
        $image   = 'assets/img/hotels/' . $images[$seed % 6] . '.jpg';
        $address = $h['address'] ?: ($dest->name . ', ' . $dest->country);

        $hotel = Hotel::updateOrCreate(
            ['osm_id' => $h['osm_id']],
            [
                'destination_id'    => $destinationId,
                'name'              => $h['name'],
                'image'             => $image,
                'stars'             => $stars,
                'rating'            => $rating,
                'reviews'           => $reviews,
                'address'           => $address,
                'price_per_night'   => $price,
                'lat'               => $h['lat'],
                'lng'               => $h['lng'],
                'source'            => 'osm',
                'price_is_estimate' => true,
                'data_note'         => 'Sample price, rating and review count (estimated) - these values are NOT supplied by OpenStreetMap.',
                'amenities'         => 'Free WiFi, Breakfast' . ($h['website'] ? ', Website' : ''),
            ]
        );

        if ($hotel->wasRecentlyCreated) {
            $inserted++;
        } else {
            $updated++;
        }
    }

    return ['found' => count($hotels), 'inserted' => $inserted, 'updated' => $updated, 'error' => null];
}
