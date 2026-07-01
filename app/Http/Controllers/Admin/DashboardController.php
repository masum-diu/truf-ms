<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Turf;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'vendors' => User::vendors()->count(),
            'turfs' => Turf::count(),
            'bookings' => Booking::count(),
            'users' => User::where('role', 'user')->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'revenue' => Booking::where('status', 'confirmed')->sum('total_price'),
        ];

        $recentVendors = User::vendors()->with('turf')->latest()->limit(5)->get();
        $recentBookings = Booking::with(['turf.owner', 'user'])->latest()->limit(8)->get();

        return view('admin.dashboard', compact('stats', 'recentVendors', 'recentBookings'));
    }
}
