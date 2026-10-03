<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Hotel;

class HomeController extends Controller
{
    public function index()
    {
        $featured  = Destination::where('featured', 1)->orderBy('id')->limit(5)->get();
        $totalDest = Destination::count();
        $totalHot  = Hotel::count();

        $liveWeather  = [];
        $points       = [];
        $pointIndexes = [];

        foreach ($featured as $i => $d) {
            if ($d->lat !== null && $d->lng !== null) {
                $points[]       = [(float) $d->lat, (float) $d->lng];
                $pointIndexes[] = $i;
            }
        }
        if ($points) {
            foreach (api_weather_multi($points) as $k => $w) {
                if ($w !== null && isset($pointIndexes[$k])) {
                    $liveWeather[$pointIndexes[$k]] = $w;
                }
            }
        }

        return view('home', compact('featured', 'totalDest', 'totalHot', 'liveWeather'));
    }
}
