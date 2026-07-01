<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $turf = Auth::user()->turf;

        $bookings = $turf
            ? Booking::query()
                ->where('turf_id', $turf->id)
                ->with('user')
                ->when($request->status, fn ($q) => $q->where('status', $request->status))
                ->latest()
                ->paginate(15)
            : collect();

        return view('vendor.bookings.index', compact('bookings', 'turf'));
    }

    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorizeBooking($booking);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,cancelled'],
        ]);

        $booking->update(['status' => $validated['status']]);

        return back()->with('success', 'Booking status updated.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $this->authorizeBooking($booking);
        $booking->delete();

        return back()->with('success', 'Booking deleted successfully.');
    }

    private function authorizeBooking(Booking $booking): void
    {
        abort_unless($booking->turf->owner_id === Auth::id(), 403);
    }
}
