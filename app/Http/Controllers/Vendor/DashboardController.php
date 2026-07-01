<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $vendor = Auth::user();
        $turf = $vendor->turf()->with('area')->withCount('bookings')->first();

        $recentBookings = collect();
        $stats = [
            'turf_setup' => $turf !== null,
            'turf_active' => $turf?->is_active ?? false,
            'pending_bookings' => 0,
            'total_revenue' => 0,
            'total_bookings' => 0,
        ];

        if ($turf) {
            $recentBookings = Booking::query()
                ->where('turf_id', $turf->id)
                ->with('user')
                ->latest()
                ->limit(8)
                ->get();

            $stats['pending_bookings'] = Booking::where('turf_id', $turf->id)->where('status', 'pending')->count();
            $stats['total_revenue'] = Booking::where('turf_id', $turf->id)->where('status', 'confirmed')->sum('total_price');
            $stats['total_bookings'] = Booking::where('turf_id', $turf->id)->count();
        }

        return view('vendor.dashboard', compact('turf', 'recentBookings', 'stats'));
    }
}
