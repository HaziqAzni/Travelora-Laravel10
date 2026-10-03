<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function index(Request $request)
    {
        $from   = trim((string) $request->query('from', ''));
        $to     = trim((string) $request->query('to', ''));
        $date   = $request->query('depart_date');
        $guests = max(1, (int) $request->query('guests', 1));

        $query = Flight::query();

        if ($from !== '') {
            $like = '%' . $from . '%';
            $query->where(function ($w) use ($like) {
                $w->where('from_city', 'like', $like)->orWhere('from_code', 'like', $like);
            });
        }
        if ($to !== '') {
            $like = '%' . $to . '%';
            $query->where(function ($w) use ($like) {
                $w->where('to_city', 'like', $like)->orWhere('to_code', 'like', $like);
            });
        }

        $flights = $query->orderBy('price')->get();

        return view('flights', compact('flights', 'from', 'to', 'date', 'guests'));
    }
}
