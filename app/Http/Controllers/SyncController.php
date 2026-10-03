<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;

class SyncController extends Controller
{
    public function destinations(Request $request)
    {
        set_time_limit(600);
        $r = sync_destination_coords(null, (bool) $request->input('force'));

        if ($r['failed']) {
            set_flash('error', "Synced {$r['updated']} destinations, but failed for: " . implode(', ', $r['failed']) . '.');
        } else {
            set_flash('success', "Destination coordinates synced from OpenStreetMap: {$r['updated']} updated, {$r['skipped']} already cached.");
        }

        return redirect('/api-status');
    }

    public function hotels(Request $request)
    {
        set_time_limit(900);
        $back = safe_redirect($request->headers->get('referer'), '/api-status');

        $totalInserted = 0;
        $totalUpdated  = 0;
        $totalFound    = 0;
        $errors        = [];

        if ($request->boolean('all')) {
            foreach (Destination::orderBy('id')->get() as $d) {
                $r = sync_osm_hotels((int) $d->id);
                $totalFound    += $r['found'];
                $totalInserted += $r['inserted'];
                $totalUpdated  += $r['updated'];
                if (!empty($r['error'])) {
                    $errors[] = $d->name . ' (' . $r['error'] . ')';
                }
                usleep(1200000);
            }
        } else {
            $r = sync_osm_hotels((int) $request->input('destination_id'));
            $totalFound    = $r['found'];
            $totalInserted = $r['inserted'];
            $totalUpdated  = $r['updated'];
            if (!empty($r['error'])) {
                $errors[] = $r['error'];
            }
        }

        if ($totalFound === 0) {
            set_flash('error', 'No hotels returned from OpenStreetMap.' . ($errors ? ' ' . implode('; ', $errors) : ''));
        } else {
            set_flash('success', "OpenStreetMap sync complete: {$totalFound} real hotels found, {$totalInserted} new, {$totalUpdated} updated.");
        }

        return redirect($back);
    }

    public function clearCache()
    {
        $count = api_cache_clear();
        set_flash('success', "API cache cleared ({$count} cached responses removed).");

        return redirect('/api-status');
    }
}
