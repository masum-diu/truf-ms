<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Turf;
use Illuminate\View\View;

class TurfController extends Controller
{
    public function index(): View
    {
        $turfs = Turf::query()
            ->with(['area', 'owner'])
            ->withCount('bookings')
            ->latest()
            ->paginate(20);

        return view('admin.turfs.index', compact('turfs'));
    }
}
