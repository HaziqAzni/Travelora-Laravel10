<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Package;

class DestinationController extends Controller
{
    public function show(string $slug)
    {
        $dest = Destination::where('slug', $slug)->first();

        if (!$dest) {
            return response()->view('destination-missing', [], 404);
        }

        /* Live API enrichment (cached) */
        if ($dest->lat === null || $dest->lng === null) {
            $geo = api_geocode($dest->name . ', ' . $dest->country);
            if ($geo) {
                $dest->lat          = $geo['lat'];
                $dest->lng          = $geo['lng'];
                $dest->country_code = $dest->country_code ?: $geo['country_code'];
                $dest->source       = 'api';
                $dest->api_synced_at = now();
                $dest->save();
            }
        }

        $wiki    = api_wiki_summary($dest->wiki_title ?: $dest->name);
        $country = $dest->country_code ? api_country_facts($dest->country_code) : null;
        $weather = $dest->lat !== null ? api_weather((float) $dest->lat, (float) $dest->lng) : null;

        $hotels = Hotel::where('destination_id', $dest->id)->orderByDesc('rating')->get();
        $things = Activity::where('destination_id', $dest->id)->orderByDesc('reviews')->get();
        $packs  = Package::where('destination_id', $dest->id)->orderBy('price')->get();

        return view('destination', compact('dest', 'wiki', 'country', 'weather', 'hotels', 'things', 'packs'));
    }
}
