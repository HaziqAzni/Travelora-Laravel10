<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;

class NearbyHotelsController extends Controller
{
    public function index(Request $request)
    {
        $destId = (int) $request->query('destination_id', 0);

        // Validate coordinates so the endpoint can't be used to query arbitrary
        // areas (and flood the public Overpass service).
        if ($request->has('lat')) {
            $raw = $request->query('lat');
            if (!is_numeric($raw) || (float) $raw < -90 || (float) $raw > 90) {
                return response()->json(['ok' => false, 'error' => 'lat must be a number between -90 and 90'], 422);
            }
        }
        if ($request->has('lng')) {
            $raw = $request->query('lng');
            if (!is_numeric($raw) || (float) $raw < -180 || (float) $raw > 180) {
                return response()->json(['ok' => false, 'error' => 'lng must be a number between -180 and 180'], 422);
            }
        }

        $lat    = $request->has('lat') ? (float) $request->query('lat') : null;
        $lng    = $request->has('lng') ? (float) $request->query('lng') : null;
        $radius = max(1000, min(15000, (int) $request->query('radius', 5000)));

        $place = null;

        if ($destId > 0) {
            $place = Destination::find($destId);
            if (!$place) {
                return response()->json(['ok' => false, 'error' => 'Destination not found', 'hotels' => []]);
            }
            if ($place->lat === null || $place->lng === null) {
                $geo = api_geocode($place->name . ', ' . $place->country);
                if ($geo) {
                    $lat = $geo['lat'];
                    $lng = $geo['lng'];
                    $place->lat = $lat;
                    $place->lng = $lng;
                    $place->country_code = $place->country_code ?: $geo['country_code'];
                    $place->source = 'api';
                    $place->api_synced_at = now();
                    $place->save();
                }
            } else {
                $lat = (float) $place->lat;
                $lng = (float) $place->lng;
            }
        }

        if ($lat === null || $lng === null) {
            return response()->json(['ok' => false, 'error' => 'No coordinates available', 'hotels' => []]);
        }

        $hotels = api_osm_hotels($lat, $lng, $radius, 24);

        return response()->json([
            'ok'         => true,
            'source'     => 'OpenStreetMap via Overpass API (ODbL)',
            'place'      => $place->name ?? null,
            'count'      => count($hotels),
            'updated_at' => date('Y-m-d H:i:s'),
            'hotels'     => $hotels,
        ]);
    }
}
