@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
  <div class="bg-white rounded-2xl border border-gray-200 p-8">
    <div class="text-center mb-6">
      <div class="text-5xl mb-3">✅</div>
      <h1 class="text-2xl font-bold text-gray-800">Booking Confirmed!</h1>
    </div>

    <div class="space-y-4 border-t border-gray-100 pt-6">
      <div class="flex justify-between">
        <span class="text-gray-500">Turf</span>
        <span class="font-medium">{{ $booking->turf->name }}</span>
      </div>
      <div class="flex justify-between">
        <span class="text-gray-500">Area</span>
        <span class="font-medium">{{ $booking->turf->area->name }}</span>
      </div>
      <div class="flex justify-between">
        <span class="text-gray-500">Date</span>
        <span class="font-medium">{{ $booking->booking_date->format('d M, Y') }}</span>
      </div>
      <div class="flex justify-between">
        <span class="text-gray-500">Time</span>
        <span class="font-medium">
          {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} -
          {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
        </span>
      </div>
      <div class="flex justify-between">
        <span class="text-gray-500">Total Price</span>
        <span class="font-bold text-emerald-600 text-lg">Tk {{ number_format($booking->total_price) }}</span>
      </div>
      <div class="flex justify-between">
        <span class="text-gray-500">Status</span>
        <span class="px-3 py-1 rounded-full text-sm font-medium
          @if($booking->status === 'confirmed') bg-emerald-100 text-emerald-700
          @elseif($booking->status === 'cancelled') bg-red-100 text-red-700
          @else bg-amber-100 text-amber-700 @endif">
          @switch($booking->status)
            @case('confirmed') Confirmed @break
            @case('cancelled') Cancelled @break
            @default Pending
          @endswitch
        </span>
      </div>
    </div>

    <div class="mt-8 flex gap-3">
      <a href="{{ route('bookings.index') }}" class="flex-1 text-center border border-gray-300 py-3 rounded-lg hover:bg-gray-50 transition">
        All Bookings
      </a>
      <a href="{{ route('home') }}" class="flex-1 text-center bg-emerald-600 text-white py-3 rounded-lg hover:bg-emerald-700 transition">
        Go Home
      </a>
    </div>
  </div>
</div>
@endsection
