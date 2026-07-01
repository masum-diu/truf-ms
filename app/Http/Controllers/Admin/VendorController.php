<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class VendorController extends Controller
{
    public function index(): View
    {
        $vendors = User::vendors()
            ->with(['turf.area'])
            ->latest()
            ->paginate(15);

        return view('admin.vendors.index', compact('vendors'));
    }

    public function create(): View
    {
        return view('admin.vendors.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        User::create([
            ...$validated,
            'role' => 'vendor',
        ]);

        return redirect()
            ->route('admin.vendors.index')
            ->with('success', 'Vendor account created successfully.');
    }

    public function show(User $vendor): View
    {
        abort_unless($vendor->isVendor(), 404);

        $vendor->load(['turf.area']);
        $turf = $vendor->turf?->loadCount('bookings');
        $bookings = $turf
            ? Booking::query()
                ->where('turf_id', $turf->id)
                ->latest()
                ->limit(10)
                ->get()
            : collect();

        return view('admin.vendors.show', compact('vendor', 'turf', 'bookings'));
    }

    public function edit(User $vendor): View
    {
        abort_unless($vendor->isVendor(), 404);

        return view('admin.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, User $vendor): RedirectResponse
    {
        abort_unless($vendor->isVendor(), 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$vendor->id],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $vendor->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        if ($request->filled('password')) {
            $vendor->update(['password' => $validated['password']]);
        }

        return redirect()
            ->route('admin.vendors.index')
            ->with('success', 'Vendor updated successfully.');
    }

    public function destroy(User $vendor): RedirectResponse
    {
        abort_unless($vendor->isVendor(), 404);
        abort_if($vendor->id === Auth::id(), 403);

        $vendor->delete();

        return redirect()
            ->route('admin.vendors.index')
            ->with('success', 'Vendor deleted successfully.');
    }
}
