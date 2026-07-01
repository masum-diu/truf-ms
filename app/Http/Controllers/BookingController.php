<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        $bookings = Auth::user()
            ->bookings()
            ->with('turf.area')
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function show(Booking $booking): View
    {
        abort_unless($booking->user_id === Auth::id(), 403);

        $booking->load('turf.area');

        return view('bookings.show', compact('booking'));
    }
}
