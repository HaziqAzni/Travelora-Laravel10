<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\Hotel;
use App\Models\Package;
use DateTime;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $user = current_user();
        if (!$user) {
            return redirect('/login?redirect=' . urlencode('/my-bookings'));
        }

        $bookings = Booking::where('user_id', $user['id'])->orderByDesc('created_at')->orderByDesc('id')->get();

        $icons = [
            'hotel'    => 'fa-solid fa-hotel',
            'flight'   => 'fa-solid fa-plane',
            'package'  => 'fa-solid fa-suitcase-rolling',
            'activity' => 'fa-solid fa-ticket',
        ];

        return view('bookings', compact('bookings', 'icons', 'user'));
    }

    public function store(Request $request)
    {
        $back = safe_redirect($request->headers->get('referer'), '/');

        if (!current_user()) {
            return redirect('/login?redirect=' . urlencode($back));
        }

        $type     = $request->input('booking_type');
        $itemId   = (int) $request->input('item_id');
        $name     = trim((string) $request->input('item_name'));
        $travel   = $request->input('travel_date');
        $end      = $request->input('end_date');
        $guests   = max(1, min(10, (int) $request->input('guests', 1)));
        $user     = current_user();

        $allowed = ['hotel', 'flight', 'package', 'activity'];
        if (!in_array($type, $allowed, true) || $itemId < 1 || $travel === '') {
            set_flash('error', t('err.book_invalid', 'Sorry, that booking request was invalid. Please try again.'));
            return redirect($back);
        }

        $total = 0.0;

        switch ($type) {
            case 'hotel':
                $row = Hotel::find($itemId);
                if (!$row) break;
                $name   = $row->name;
                $start  = new DateTime($travel);
                $finish = $end !== '' && $end !== null ? new DateTime($end) : (clone $start)->modify('+1 day');
                $nights = max(1, (int) $start->diff($finish)->days);
                $total  = (float) $row->price_per_night * $nights;
                $end    = $finish->format('Y-m-d');
                break;

            case 'flight':
                $row = Flight::find($itemId);
                if (!$row) break;
                $name  = $row->airline . ': ' . $row->from_code . ' to ' . $row->to_code;
                $total = (float) $row->price * $guests;
                $end   = null;
                break;

            case 'package':
                $row = Package::find($itemId);
                if (!$row) break;
                $name  = $row->title;
                $total = (float) $row->price * $guests;
                $end   = null;
                break;

            case 'activity':
                $row = Activity::find($itemId);
                if (!$row) break;
                $name  = $row->title;
                $total = (float) $row->price * $guests;
                $end   = null;
                break;
        }

        if ($total <= 0) {
            set_flash('error', t('err.book_item', 'We could not find that item. Please try again.'));
            return redirect($back);
        }

        Booking::create([
            'user_id'      => $user['id'],
            'booking_type' => $type,
            'item_id'      => $itemId,
            'item_name'    => $name,
            'travel_date'  => $travel,
            'end_date'     => $end,
            'guests'       => $guests,
            'total_price'  => $total,
            'status'       => 'pending',
            'is_demo'      => true,
        ]);

        set_flash('success', sprintf(t('flash.book_requested', 'Booking request received (demo): %s. No payment was taken and no live availability was checked.'), $name));
        return redirect('/my-bookings');
    }

    public function cancel(Request $request)
    {
        $user = current_user();
        if (!$user) {
            return redirect('/login?redirect=' . urlencode('/my-bookings'));
        }

        $id = (int) $request->input('id');

        $affected = Booking::where('id', $id)
            ->where('user_id', $user['id'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->update(['status' => 'cancelled']);

        if ($affected > 0) {
            set_flash('success', sprintf(t('flash.cancel_ok', 'Booking %s has been cancelled.'), 'TRV-' . str_pad((string) $id, 5, '0', STR_PAD_LEFT)));
        } else {
            set_flash('error', t('err.cancel', 'We could not cancel that booking.'));
        }

        return redirect('/my-bookings');
    }
}
