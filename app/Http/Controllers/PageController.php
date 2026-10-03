<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Package;

class PageController extends Controller
{
    public function about()
    {
        $totalDest = Destination::count();
        $totalHot  = Hotel::count();
        $totalFl   = \App\Models\Flight::count();
        $totalUser = \App\Models\User::count();

        return view('about', compact('totalDest', 'totalHot', 'totalFl', 'totalUser'));
    }

    public function terms()
    {
        return view('legal.terms');
    }

    public function privacy()
    {
        return view('legal.privacy');
    }

    public function contact()
    {
        return view('legal.contact');
    }
}
