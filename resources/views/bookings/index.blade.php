@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-6">My Bookings</h1>

  @if($bookings->isEmpty())
    <div class="bg-white rounded-xl border border-gray-200 p-12 text-center text-gray-500">
      <p class="text-lg mb-4">You have no bookings yet.</p>
      <a href="{{ route('home') }}" class="text-emerald-600 font-medium hover:underline">Find a turf</a>
    </div>
  @else
    <div class="space-y-4">
      @foreach($bookings as $booking)
        <div class="bg-white rounded-xl border border-gray-200 p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <h3 class="font-bold text-lg">{{ $booking->turf->name }}</h3>
            <p class="text-gray-500 text-sm">{{ $booking->turf->area->name }} • {{ $booking->booking_date->format('d M, Y') }}</p>
            <p class="text-gray-600 text-sm mt-1">
              {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} -
              {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
            </p>
          </div>
          <div class="flex items-center gap-4">
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
            <span class="font-bold text-emerald-600">Tk {{ number_format($booking->total_price) }}</span>
            <a href="{{ route('bookings.show', $booking) }}" class="text-emerald-600 hover:underline text-sm">Details</a>
          </div>
        </div>
      @endforeach
    </div>
    <div class="mt-6">{{ $bookings->links() }}</div>
  @endif
</div>
@endsection
