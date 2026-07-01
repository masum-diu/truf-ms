<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Turf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TurfController extends Controller
{
    public function manage(): View
    {
        $turf = Auth::user()->turf;
        $areas = Area::query()->where('is_active', true)->orderBy('name')->get();

        return view('vendor.turf.manage', compact('turf', 'areas'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_if(Auth::user()->hasTurf(), 403, 'You already have a turf registered.');

        $validated = $this->validateTurf($request);

        $slug = $this->uniqueSlug($validated['name']);

        Auth::user()->turf()->create([
            ...$validated,
            'price_per_hour' => $this->lowestPrice($validated),
            'slug' => $slug,
            'amenities' => $this->parseAmenities($request),
            'is_active' => true,
        ]);

        return redirect()
            ->route('vendor.turf.manage')
            ->with('success', 'Your turf has been set up successfully!');
    }

    public function update(Request $request): RedirectResponse
    {
        $turf = Auth::user()->turf;
        abort_unless($turf, 404);

        $validated = $this->validateTurf($request);

        $turf->update([
            ...$validated,
            'price_per_hour' => $this->lowestPrice($validated),
            'amenities' => $this->parseAmenities($request),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('vendor.turf.manage')
            ->with('success', 'Turf updated successfully!');
    }

    private function validateTurf(Request $request): array
    {
        return $request->validate([
            'area_id' => ['required', 'exists:areas,id'],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'day_price' => ['required', 'integer', 'min:100'],
            'night_price' => ['required', 'integer', 'min:100'],
            'offday_price' => ['required', 'integer', 'min:100'],
            'surface_type' => ['required', 'in:artificial_grass,natural_grass,indoor'],
            'size' => ['nullable', 'string', 'max:50'],
            'open_time' => ['required', 'date_format:H:i'],
            'close_time' => ['required', 'date_format:H:i', 'after:open_time'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
    }

    private function lowestPrice(array $validated): int
    {
        return min($validated['day_price'], $validated['night_price'], $validated['offday_price']);
    }

    private function parseAmenities(Request $request): array
    {
        if (! $request->filled('amenities')) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $request->amenities))));
    }

    private function uniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;
        while (Turf::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter++;
        }

        return $slug;
    }
}
