<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Turf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $areas = Area::query()
            ->where('is_active', true)
            ->withCount(['activeTurfs'])
            ->orderBy('name')
            ->get();

        $selectedArea = null;
        $turfs = collect();

        if ($request->filled('area')) {
            $selectedArea = Area::query()
                ->where('slug', $request->area)
                ->where('is_active', true)
                ->firstOrFail();

            $turfs = Turf::query()
                ->with('area')
                ->where('area_id', $selectedArea->id)
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }

        $featuredTurfs = Turf::query()
            ->with('area')
            ->where('is_active', true)
            ->latest()
            ->limit(6)
            ->get();

        return view('home', compact('areas', 'selectedArea', 'turfs', 'featuredTurfs'));
    }
}
