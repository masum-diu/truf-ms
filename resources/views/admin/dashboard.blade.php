@extends('layouts.admin')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
<div class="flex items-center justify-between mb-6">
  <p class="text-gray-500 text-sm">Overview of vendors, turfs, and bookings</p>
  <a href="{{ route('admin.vendors.create') }}" class="btn-panel-admin">
    + Create Vendor
  </a>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
  <div class="panel-stat-card">
    <p class="text-pitch-900/40 text-sm">Vendors</p>
    <p class="font-display text-2xl font-bold text-pitch-900 mt-1">{{ $stats['vendors'] }}</p>
  </div>
  <div class="panel-stat-card">
    <p class="text-pitch-900/40 text-sm">Turfs</p>
    <p class="font-display text-2xl font-bold text-pitch-900 mt-1">{{ $stats['turfs'] }}</p>
  </div>
  <div class="panel-stat-card">
    <p class="text-pitch-900/40 text-sm">Bookings</p>
    <p class="font-display text-2xl font-bold text-pitch-900 mt-1">{{ $stats['bookings'] }}</p>
  </div>
  <div class="panel-stat-card">
    <p class="text-pitch-900/40 text-sm">Users</p>
    <p class="font-display text-2xl font-bold text-pitch-900 mt-1">{{ $stats['users'] }}</p>
  </div>
  <div class="panel-stat-card">
    <p class="text-pitch-900/40 text-sm">Pending</p>
    <p class="font-display text-2xl font-bold text-amber-500 mt-1">{{ $stats['pending_bookings'] }}</p>
  </div>
  <div class="panel-stat-card">
    <p class="text-pitch-900/40 text-sm">Revenue</p>
    <p class="font-display text-2xl font-bold text-turf-700 mt-1">Tk {{ number_format($stats['revenue']) }}</p>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
  <div>
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-lg font-bold">Recent Vendors</h2>
      <a href="{{ route('admin.vendors.index') }}" class="text-slate-600 text-sm hover:underline">View all</a>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">
      @forelse($recentVendors as $vendor)
        <div class="p-4 flex items-center justify-between">
          <div>
            <p class="font-semibold">{{ $vendor->name }}</p>
            <p class="text-sm text-gray-500">{{ $vendor->email }} • {{ $vendor->turf?->name ?? 'No turf yet' }}</p>
          </div>
          <a href="{{ route('admin.vendors.show', $vendor) }}" class="text-sm text-slate-600 hover:underline">View</a>
        </div>
      @empty
        <p class="p-8 text-center text-gray-500">
          No vendors yet.
          <a href="{{ route('admin.vendors.create') }}" class="block text-slate-700 font-medium hover:underline mt-2">Create first vendor</a>
        </p>
      @endforelse
    </div>
  </div>

  <div>
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-lg font-bold">Recent Bookings</h2>
      <a href="{{ route('admin.bookings.index') }}" class="text-slate-600 text-sm hover:underline">View all</a>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">
      @forelse($recentBookings as $booking)
        <div class="p-4">
          <div class="flex items-center justify-between mb-1">
            <p class="font-semibold">{{ $booking->turf->name }}</p>
            <span class="text-xs px-2 py-1 rounded-full capitalize
              @if($booking->status === 'confirmed') bg-emerald-100 text-emerald-700
              @elseif($booking->status === 'cancelled') bg-red-100 text-red-700
              @else bg-amber-100 text-amber-700 @endif">
              {{ $booking->status }}
            </span>
          </div>
          <p class="text-sm text-gray-500">
            {{ $booking->customer_name }} • {{ $booking->turf->owner->name }} • Tk {{ number_format($booking->total_price) }}
          </p>
        </div>
      @empty
        <p class="p-8 text-center text-gray-500">No bookings yet.</p>
      @endforelse
    </div>
  </div>
</div>
@endsection
