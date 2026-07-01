@extends('layouts.vendor')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
@if(!$stats['turf_setup'])
  <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 mb-6 flex items-center justify-between gap-4">
    <div>
      <p class="font-semibold text-amber-900">Set up your turf</p>
      <p class="text-sm text-amber-700 mt-1">You need to configure your turf before customers can book it.</p>
    </div>
    <a href="{{ route('vendor.turf.manage') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition shrink-0">
      Setup Turf
    </a>
  </div>
@endif

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
  <div class="bg-white rounded-xl border border-gray-200 p-5">
    <p class="text-gray-500 text-sm">Turf Status</p>
    <p class="text-lg font-bold mt-1 {{ $stats['turf_active'] ? 'text-emerald-600' : 'text-gray-500' }}">
      @if(!$stats['turf_setup']) Not Setup
      @elseif($stats['turf_active']) Active
      @else Inactive @endif
    </p>
  </div>
  <div class="bg-white rounded-xl border border-gray-200 p-5">
    <p class="text-gray-500 text-sm">Total Bookings</p>
    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_bookings'] }}</p>
  </div>
  <div class="bg-white rounded-xl border border-gray-200 p-5">
    <p class="text-gray-500 text-sm">Pending Bookings</p>
    <p class="text-3xl font-bold text-amber-600 mt-1">{{ $stats['pending_bookings'] }}</p>
  </div>
  <div class="bg-white rounded-xl border border-gray-200 p-5">
    <p class="text-gray-500 text-sm">Total Revenue</p>
    <p class="text-3xl font-bold text-emerald-600 mt-1">Tk {{ number_format($stats['total_revenue']) }}</p>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
  <div>
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-lg font-bold">My Turf</h2>
      @if($turf)
        <a href="{{ route('vendor.turf.manage') }}" class="text-emerald-600 text-sm hover:underline">Edit</a>
      @endif
    </div>
    @if(!$turf)
      <div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-500">
        <p>No turf configured yet.</p>
        <a href="{{ route('vendor.turf.manage') }}" class="text-emerald-600 text-sm hover:underline mt-2 inline-block">Set up now</a>
      </div>
    @else
      <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-start justify-between mb-3">
          <div>
            <p class="font-bold text-lg">{{ $turf->name }}</p>
            <p class="text-sm text-gray-500">{{ $turf->area->name }}</p>
          </div>
          <span class="text-xs px-2 py-1 rounded-full {{ $turf->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
            {{ $turf->is_active ? 'Active' : 'Inactive' }}
          </span>
        </div>
        <p class="text-sm text-gray-600 mb-3">{{ $turf->address }}</p>
        <div class="flex items-center justify-between text-sm">
          <span class="text-emerald-600 font-bold text-xs">
            Day Tk {{ number_format($turf->day_price) }} · Night Tk {{ number_format($turf->night_price) }} · Off-day Tk {{ number_format($turf->offday_price) }}
          </span>
          <a href="{{ route('turfs.show', $turf) }}" class="text-gray-500 hover:underline">Public page</a>
        </div>
      </div>
    @endif
  </div>

  <div>
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-lg font-bold">Recent Bookings</h2>
      <a href="{{ route('vendor.bookings.index') }}" class="text-emerald-600 text-sm hover:underline">View all</a>
    </div>
    @if($recentBookings->isEmpty())
      <div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-500">
        <p>No bookings yet.</p>
      </div>
    @else
      <div class="space-y-3">
        @foreach($recentBookings as $booking)
          <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="flex items-center justify-between mb-2">
              <p class="font-semibold">{{ $booking->customer_name }}</p>
              <span class="text-xs px-2 py-1 rounded-full capitalize
                @if($booking->status === 'confirmed') bg-emerald-100 text-emerald-700
                @elseif($booking->status === 'cancelled') bg-red-100 text-red-700
                @else bg-amber-100 text-amber-700 @endif">
                {{ $booking->status }}
              </span>
            </div>
            <p class="text-sm text-gray-500">
              {{ $booking->booking_date->format('d M') }} • Tk {{ number_format($booking->total_price) }}
            </p>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</div>
@endsection
