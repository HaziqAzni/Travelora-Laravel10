<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Hotel;
use Throwable;

class ApiStatusController extends Controller
{
    private function check(callable $fn): array
    {
        $start = microtime(true);
        try {
            $result = $fn();
            $ms     = (int) round((microtime(true) - $start) * 1000);
            return ['ok' => !empty($result), 'ms' => $ms, 'result' => $result];
        } catch (Throwable $e) {
            return ['ok' => false, 'ms' => 0, 'error' => $e->getMessage()];
        }
    }

    public function index()
    {
        $checks = [];

        $r = $this->check(fn() => api_wiki_summary('Bali'));
        $checks[] = ['provider' => 'Wikipedia REST API', 'icon' => 'fa-brands fa-wikipedia-w', 'license' => 'CC BY-SA 4.0',
            'endpoint' => 'en.wikipedia.org/api/rest_v1', 'use' => 'Destination descriptions',
            'ok' => $r['ok'], 'ms' => $r['ms'],
            'detail' => $r['ok'] ? strlen($r['result']['extract']) . ' chars received' : 'unavailable'];

        $r = $this->check(fn() => api_country_facts('MY'));
        $checks[] = ['provider' => 'Wikidata SPARQL (country facts)', 'icon' => 'fa-solid fa-earth-asia', 'license' => 'CC0',
            'endpoint' => 'query.wikidata.org/sparql', 'use' => 'Country facts (capital, currency, population)',
            'ok' => $r['ok'], 'ms' => $r['ms'],
            'detail' => $r['ok'] ? $r['result']['name'] . ' · capital ' . $r['result']['capital'] . ' · pop ' . number_format($r['result']['population']) : 'unavailable'];

        $r = $this->check(fn() => api_geocode('Kuala Lumpur, Malaysia'));
        $checks[] = ['provider' => 'Nominatim (OpenStreetMap)', 'icon' => 'fa-solid fa-map-location-dot', 'license' => 'ODbL',
            'endpoint' => 'nominatim.openstreetmap.org', 'use' => 'Geocoding destinations',
            'ok' => $r['ok'], 'ms' => $r['ms'],
            'detail' => $r['ok'] ? $r['result']['lat'] . ', ' . $r['result']['lng'] : 'unavailable'];

        $r = $this->check(fn() => api_weather(3.1390, 101.6869));
        $checks[] = ['provider' => 'Open-Meteo', 'icon' => 'fa-solid fa-cloud-sun', 'license' => 'CC BY 4.0',
            'endpoint' => 'api.open-meteo.com/v1/forecast', 'use' => 'Live weather + forecast',
            'ok' => $r['ok'], 'ms' => $r['ms'],
            'detail' => $r['ok'] ? round($r['result']['temp']) . '°C in Kuala Lumpur · ' . count($r['result']['daily']) . '-day forecast' : 'unavailable'];

        $r = $this->check(fn() => api_osm_hotels(3.1390, 101.6869, 3000, 10));
        $checks[] = ['provider' => 'Overpass API (OpenStreetMap)', 'icon' => 'fa-solid fa-hotel', 'license' => 'ODbL',
            'endpoint' => 'overpass-api.de/api/interpreter', 'use' => 'Live hotel data',
            'ok' => $r['ok'], 'ms' => $r['ms'],
            'detail' => $r['ok'] ? count($r['result']) . ' real hotels near KLCC' : 'unavailable'];

        $db = [
            'destinations'     => Destination::count(),
            'destinations_geo' => Destination::whereNotNull('lat')->count(),
            'hotels_seed'      => Hotel::where('source', 'seed')->count(),
            'hotels_osm'       => Hotel::where('source', 'osm')->count(),
        ];
        $cache = api_cache_stats();
        $dests = Destination::withCount(['hotels as osm_hotels' => function ($q) {
            $q->where('source', 'osm');
        }])->orderBy('id')->get();

        return view('api-status', compact('checks', 'db', 'cache', 'dests'));
    }
}
