<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $q      = trim((string) $request->query('q', ''));
        $date   = $request->query('travel_date');
        $guests = max(1, (int) $request->query('guests', 2));

        $base = Package::query()
            ->join('destinations', 'destinations.id', '=', 'packages.destination_id')
            ->select('packages.*', 'destinations.name as destination', 'destinations.country', 'destinations.slug as destination_slug');

        if ($q !== '') {
            $like = '%' . $q . '%';
            $base->where(function ($w) use ($like) {
                $w->where('packages.title', 'like', $like)
                    ->orWhere('destinations.name', 'like', $like)
                    ->orWhere('destinations.country', 'like', $like);
            });
        }

        $packages = $base->orderByDesc('packages.rating')->orderBy('packages.price')->get();

        return view('packages', compact('packages', 'q', 'date', 'guests'));
    }
}
