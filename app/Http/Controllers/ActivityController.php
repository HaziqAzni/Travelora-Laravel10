<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $q      = trim((string) $request->query('q', ''));
        $date   = $request->query('travel_date');
        $guests = max(1, (int) $request->query('guests', 2));

        $base = Activity::query()
            ->join('destinations', 'destinations.id', '=', 'things_to_do.destination_id')
            ->select('things_to_do.*', 'destinations.name as destination', 'destinations.country', 'destinations.slug as destination_slug');

        if ($q !== '') {
            $like = '%' . $q . '%';
            $base->where(function ($w) use ($like) {
                $w->where('things_to_do.title', 'like', $like)
                    ->orWhere('things_to_do.category', 'like', $like)
                    ->orWhere('destinations.name', 'like', $like)
                    ->orWhere('destinations.country', 'like', $like);
            });
        }

        $things = $base->orderByDesc('things_to_do.rating')->orderByDesc('things_to_do.reviews')->get();

        return view('things', compact('things', 'q', 'date', 'guests'));
    }
}
