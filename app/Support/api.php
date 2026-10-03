<?php
/**
 * Travelora - API integration layer
 * ------------------------------------------------------------
 * Keyless / open APIs used:
 *   - Wikipedia REST API      (destination descriptions, CC BY-SA)
 *   - Wikidata SPARQL         (country facts, CC0)
 *   - Nominatim / OSM         (geocoding, ODbL)
 *   - Overpass API / OSM      (live hotel data, ODbL)
 *   - Open-Meteo              (live weather + forecast, CC BY 4.0)
 *
 * All responses are cached on disk (storage/app/api-cache) with per-provider TTLs.
 */

define('API_USER_AGENT', 'TraveloraProject/1.0 (educational demo; PHP/' . PHP_VERSION . ')');

/* ============================================================
   Low level HTTP + cache
   ============================================================ */

function api_cache_file(string $key): string
{
    if (!is_dir(api_cache_dir())) {
        @mkdir(api_cache_dir(), 0777, true);
    }
    return api_cache_dir() . '/' . preg_replace('/[^a-z0-9_\-]/i', '_', $key) . '.json';
}

function api_cache_get(string $key, int $ttl)
{
    $file = api_cache_file($key);
    if (is_file($file) && (time() - filemtime($file)) < $ttl) {
        $data = json_decode((string) file_get_contents($file), true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $data;
        }
    }
    return null;
}

function api_cache_set(string $key, $data): void
{
    @file_put_contents(api_cache_file($key), json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
}

function api_cache_clear(): int
{
    $count = 0;
    foreach (glob(api_cache_dir() . '/*.json') ?: [] as $file) {
        @unlink($file);
        $count++;
    }
    return $count;
}

function api_cache_stats(): array
{
    $files = glob(api_cache_dir() . '/*.json') ?: [];
    $bytes = 0;
    foreach ($files as $f) {
        $bytes += (int) @filesize($f);
    }
    return ['files' => count($files), 'bytes' => $bytes];
}

function api_log(string $message): void
{
    if (!is_dir(api_log_dir())) {
        @mkdir(api_log_dir(), 0777, true);
    }
    @file_put_contents(api_log_dir() . '/api.log', date('[Y-m-d H:i:s] ') . $message . PHP_EOL, FILE_APPEND);
}

/**
 * GET JSON from an API with retries. Returns array|null.
 */
function api_http_json(string $url, array $headers = [], int $timeout = 20, int $retries = 2): ?array
{
    $headers[] = 'Accept: application/json';
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS      => 4,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_TIMEOUT        => $timeout,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_HTTPHEADER     => array_merge(['User-Agent: ' . API_USER_AGENT], $headers),
    ]);

    $attempts = 0;
    while ($attempts <= $retries) {
        $attempts++;
        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        if ($body !== false && $code >= 200 && $code < 300) {
            $data = json_decode($body, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $data;
            }
            api_log("JSON decode error for $url: " . json_last_error_msg());
            break;
        }
        $err = $body === false ? curl_error($ch) : "HTTP $code";
        api_log("Request failed ($attempts/$retries) $url : $err");
        usleep(400000);
    }
    return null;
}

/**
 * Cache-through fetch: returns cached data or runs $fetcher and caches it.
 */
function api_cached(string $key, int $ttl, callable $fetcher)
{
    $cached = api_cache_get($key, $ttl);
    if ($cached !== null) {
        return $cached;
    }
    $fresh = $fetcher();
    if ($fresh !== null) {
        api_cache_set($key, $fresh);
    }
    return $fresh;
}

/* ============================================================
   Geocoding - Nominatim (OpenStreetMap)
   ============================================================ */

function api_geocode(string $query): ?array
{
    $query = trim($query);
    if ($query === '') {
        return null;
    }
    return api_cached('geo_' . md5(strtolower($query)), 60 * 60 * 24 * 30, function () use ($query) {
        $url  = 'https://nominatim.openstreetmap.org/search?format=jsonv2&limit=1&addressdetails=1&q=' . rawurlencode($query);
        $data = api_http_json($url);
        if (!$data || empty($data[0])) {
            return null;
        }
        $hit = $data[0];
        return [
            'lat'          => (float) $hit['lat'],
            'lng'          => (float) $hit['lon'],
            'display_name' => (string) ($hit['display_name'] ?? $query),
            'country_code' => strtoupper((string) ($hit['address']['country_code'] ?? '')),
        ];
    });
}

/* ============================================================
   Wikipedia - destination descriptions
   ============================================================ */

function api_wiki_summary(string $title): ?array
{
    $title = trim($title);
    if ($title === '') {
        return null;
    }
    return api_cached('wiki_' . md5(strtolower($title)), 60 * 60 * 24 * 7, function () use ($title) {
        $url  = 'https://en.wikipedia.org/api/rest_v1/page/summary/' . rawurlencode(str_replace(' ', '_', $title));
        $data = api_http_json($url);
        if (!$data || empty($data['extract'])) {
            return null;
        }
        return [
            'title'     => (string) ($data['title'] ?? $title),
            'extract'   => (string) $data['extract'],
            'thumbnail' => $data['thumbnail']['source'] ?? null,
            'url'       => $data['content_urls']['desktop']['page'] ?? null,
        ];
    });
}

/* ============================================================
   Wikidata SPARQL - country facts (keyless, CC0)
   Replaces REST Countries, whose keyless v1-v4 endpoints were
   deprecated in favour of a key-based v5.
   ============================================================ */

function api_country_facts(string $code): ?array
{
    $code = strtoupper(trim($code));
    if (strlen($code) !== 2) {
        return null;
    }
    return api_cached('country_wd_' . $code, 60 * 60 * 24 * 30, function () use ($code) {
        $sparql = 'SELECT ?countryLabel ?capitalLabel ?population ?currencyLabel ?languageLabel ?flag WHERE {'
                . ' ?country wdt:P297 "' . $code . '" .'
                . ' OPTIONAL { ?country wdt:P36 ?capital . }'
                . ' OPTIONAL { ?country wdt:P1082 ?population . }'
                . ' OPTIONAL { ?country wdt:P38 ?currency . }'
                . ' OPTIONAL { ?country wdt:P37 ?language . }'
                . ' OPTIONAL { ?country wdt:P41 ?flag . }'
                . ' SERVICE wikibase:label { bd:serviceParam wikibase:language "en". } }';

        $data = api_http_json(
            'https://query.wikidata.org/sparql?format=json&query=' . rawurlencode($sparql),
            ['Accept: application/sparql-results+json'],
            30,
            1
        );
        if (!$data || empty($data['results']['bindings'])) {
            return null;
        }

        $capital = [];
        $currency = [];
        $languages = [];
        $population = 0;
        $flag = null;
        $name = null;

        foreach ($data['results']['bindings'] as $b) {
            $name = $name ?? ($b['countryLabel']['value'] ?? null);
            if (isset($b['capitalLabel']['value'])) {
                $capital[$b['capitalLabel']['value']] = true;
            }
            if (isset($b['population']['value'])) {
                $population = max($population, (int) $b['population']['value']);
            }
            if (isset($b['currencyLabel']['value'])) {
                $currency[$b['currencyLabel']['value']] = true;
            }
            if (isset($b['languageLabel']['value'])) {
                $languages[$b['languageLabel']['value']] = true;
            }
            if (isset($b['flag']['value'])) {
                $flag = $b['flag']['value'];
            }
        }

        return [
            'name'       => $name ?: $code,
            'official'   => '',
            'capital'    => implode(', ', array_slice(array_keys($capital), 0, 2)),
            'currency'   => implode(', ', array_slice(array_keys($currency), 0, 2)),
            'languages'  => implode(', ', array_slice(array_keys($languages), 0, 3)),
            'population' => $population,
            'region'     => '',
            'subregion'  => '',
            'flag'       => $flag,
        ];
    });
}

/* ============================================================
   Open-Meteo - live weather + forecast
   ============================================================ */

function weather_meta(int $code): array
{
    $map = [
        0  => ['fa-sun', 'weather.clear', 'Clear sky'],
        1  => ['fa-sun', 'weather.clear', 'Mainly clear'],
        2  => ['fa-cloud-sun', 'weather.partly_cloudy', 'Partly cloudy'],
        3  => ['fa-cloud', 'weather.overcast', 'Overcast'],
        45 => ['fa-smog', 'weather.fog', 'Fog'],
        48 => ['fa-smog', 'weather.fog', 'Rime fog'],
        51 => ['fa-cloud-rain', 'weather.drizzle', 'Light drizzle'],
        53 => ['fa-cloud-rain', 'weather.drizzle', 'Drizzle'],
        55 => ['fa-cloud-rain', 'weather.drizzle', 'Dense drizzle'],
        56 => ['fa-cloud-rain', 'weather.drizzle', 'Freezing drizzle'],
        57 => ['fa-cloud-rain', 'weather.drizzle', 'Freezing drizzle'],
        61 => ['fa-cloud-rain', 'weather.light_rain', 'Light rain'],
        63 => ['fa-cloud-showers-heavy', 'weather.heavy_rain', 'Rain'],
        65 => ['fa-cloud-showers-heavy', 'weather.heavy_rain', 'Heavy rain'],
        66 => ['fa-cloud-showers-heavy', 'weather.heavy_rain', 'Freezing rain'],
        67 => ['fa-cloud-showers-heavy', 'weather.heavy_rain', 'Freezing rain'],
        71 => ['fa-snowflake', 'weather.snow', 'Light snow'],
        73 => ['fa-snowflake', 'weather.snow', 'Snow'],
        75 => ['fa-snowflake', 'weather.snow', 'Heavy snow'],
        77 => ['fa-snowflake', 'weather.snow', 'Snow grains'],
        80 => ['fa-cloud-sun-rain', 'weather.light_rain', 'Light showers'],
        81 => ['fa-cloud-showers-heavy', 'weather.heavy_rain', 'Showers'],
        82 => ['fa-cloud-showers-heavy', 'weather.heavy_rain', 'Violent showers'],
        85 => ['fa-snowflake', 'weather.snow', 'Snow showers'],
        86 => ['fa-snowflake', 'weather.snow', 'Snow showers'],
        95 => ['fa-cloud-bolt', 'weather.storm', 'Thunderstorm'],
        96 => ['fa-cloud-bolt', 'weather.storm', 'Storm with hail'],
        99 => ['fa-cloud-bolt', 'weather.storm', 'Storm with hail'],
    ];
    $row = $map[$code] ?? ['fa-cloud', 'weather.overcast', 'Unknown'];
    return [t($row[1], $row[2]), $row[0]];
}

function api_weather(float $lat, float $lng): ?array
{
    $key = 'weather_' . round($lat, 3) . '_' . round($lng, 3);
    return api_cached($key, 60 * 30, function () use ($lat, $lng) {
        $url = 'https://api.open-meteo.com/v1/forecast?latitude=' . $lat . '&longitude=' . $lng
             . '&current=temperature_2m,relative_humidity_2m,apparent_temperature,weather_code,wind_speed_10m'
             . '&daily=weather_code,temperature_2m_max,temperature_2m_min'
             . '&forecast_days=4&timezone=auto';
        $data = api_http_json($url);
        if (!$data || !isset($data['current'])) {
            return null;
        }
        $daily = [];
        for ($i = 0; $i < 4; $i++) {
            if (!isset($data['daily']['time'][$i])) {
                break;
            }
            $daily[] = [
                'date' => $data['daily']['time'][$i],
                'code' => (int) $data['daily']['weather_code'][$i],
                'max'  => (float) $data['daily']['temperature_2m_max'][$i],
                'min'  => (float) $data['daily']['temperature_2m_min'][$i],
            ];
        }
        return [
            'temp'     => (float) $data['current']['temperature_2m'],
            'feels'    => (float) $data['current']['apparent_temperature'],
            'humidity' => (int) $data['current']['relative_humidity_2m'],
            'wind'     => (float) $data['current']['wind_speed_10m'],
            'code'     => (int) $data['current']['weather_code'],
            'daily'    => $daily,
            'updated'  => time(),
        ];
    });
}

/**
 * One call for many points: [[lat,lng], ...] => list of weather arrays (index aligned).
 */
function api_weather_multi(array $points): array
{
    if (!$points) {
        return [];
    }
    $lats = implode(',', array_map(fn($p) => $p[0], $points));
    $lngs = implode(',', array_map(fn($p) => $p[1], $points));
    $key  = 'weather_multi_' . md5($lats . '|' . $lngs);
    $data = api_cached($key, 60 * 30, function () use ($lats, $lngs) {
        $url = 'https://api.open-meteo.com/v1/forecast?latitude=' . $lats . '&longitude=' . $lngs
             . '&current=temperature_2m,weather_code&timezone=auto';
        return api_http_json($url);
    });
    if (!$data) {
        return [];
    }
    // Single point returns an object, multiple points return a list
    if (isset($data['current'])) {
        $data = [$data];
    }
    $out = [];
    foreach ($data as $entry) {
        $out[] = isset($entry['current']) ? [
            'temp' => (float) $entry['current']['temperature_2m'],
            'code' => (int) $entry['current']['weather_code'],
        ] : null;
    }
    return $out;
}

/* ============================================================
   Overpass / OpenStreetMap - real hotels
   ============================================================ */

function haversine_km(float $lat1, float $lng1, float $lat2, float $lng2): float
{
    $r = 6371;
    $dLat = deg2rad($lat2 - $lat1);
    $dLng = deg2rad($lng2 - $lng1);
    $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
    return round($r * 2 * atan2(sqrt($a), sqrt(1 - $a)), 1);
}

/**
 * Real hotels around a point from OpenStreetMap via Overpass.
 */
function api_osm_hotels(float $lat, float $lng, int $radius = 5000, int $limit = 30): array
{
    $key = 'osm_hotels_' . round($lat, 3) . '_' . round($lng, 3) . '_' . $radius . '_' . $limit;
    $data = api_cached($key, 60 * 60 * 24, function () use ($lat, $lng, $radius, $limit) {
        $query = '[out:json][timeout:25];('
               . 'node["tourism"="hotel"](around:' . $radius . ',' . $lat . ',' . $lng . ');'
               . 'way["tourism"="hotel"](around:' . $radius . ',' . $lat . ',' . $lng . ');'
               . ');out center ' . $limit . ';';
        return api_http_json('https://overpass-api.de/api/interpreter?data=' . rawurlencode($query), [], 35, 1);
    });
    if (!$data || empty($data['elements'])) {
        return [];
    }
    $hotels = [];
    foreach ($data['elements'] as $el) {
        $tags = $el['tags'] ?? [];
        $name = trim((string) ($tags['name'] ?? ''));
        if ($name === '') {
            continue;
        }
        $hLat = $el['lat'] ?? ($el['center']['lat'] ?? null);
        $hLng = $el['lon'] ?? ($el['center']['lon'] ?? null);
        if ($hLat === null || $hLng === null) {
            continue;
        }
        $addrParts = array_filter([
            $tags['addr:housenumber'] ?? '',
            $tags['addr:street'] ?? '',
            $tags['addr:city'] ?? '',
        ]);
        $hotels[] = [
            'name'     => $name,
            'lat'      => (float) $hLat,
            'lng'      => (float) $hLng,
            'stars'    => isset($tags['stars']) ? max(1, min(5, (int) $tags['stars'])) : null,
            'address'  => $addrParts ? implode(' ', $addrParts) : null,
            'website'  => $tags['website'] ?? ($tags['contact:website'] ?? null),
            'phone'    => $tags['phone'] ?? ($tags['contact:phone'] ?? null),
            'rooms'    => isset($tags['rooms']) ? (int) $tags['rooms'] : null,
            'distance' => haversine_km($lat, $lng, (float) $hLat, (float) $hLng),
            'osm_type' => $el['type'],
            'osm_id'   => $el['type'] . '/' . $el['id'],
        ];
    }
    usort($hotels, fn($a, $b) => $a['distance'] <=> $b['distance']);
    return $hotels;
}
