<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        $q         = trim((string) $request->query('q', ''));
        $checkIn   = $request->query('check_in');
        $checkOut  = $request->query('check_out');
        $guests    = max(1, (int) $request->query('guests', 2));
        $perPage   = 12;
        $page      = max(1, (int) $request->query('page', 1));

        $base = Hotel::query()
            ->join('destinations', 'destinations.id', '=', 'hotels.destination_id')
            ->select('hotels.*', 'destinations.name as destination', 'destinations.country', 'destinations.slug as destination_slug');

        if ($q !== '') {
            $like = '%' . $q . '%';
            $base->where(function ($w) use ($like) {
                $w->where('hotels.name', 'like', $like)
                    ->orWhere('destinations.name', 'like', $like)
                    ->orWhere('destinations.country', 'like', $like);
            });
        }

        $total = (clone $base)->count();
        $pages = max(1, (int) ceil($total / $perPage));
        $page  = min($page, $pages);
        $offset = ($page - 1) * $perPage;

        $hotels = $base->orderByDesc('hotels.rating')
            ->orderBy('hotels.price_per_night')
            ->forPage($page, $perPage)
            ->get();

        return view('hotels', compact('hotels', 'q', 'checkIn', 'checkOut', 'guests', 'total', 'page', 'pages', 'perPage', 'offset'));
    }
}
