@extends('layouts.vendor')

@section('title', 'Bookings')
@section('heading', 'Booking Management')

@section('content')
@if(!$turf)
  <div class="bg-white rounded-xl border border-gray-200 p-12 text-center text-gray-500">
    <p class="mb-4">Set up your turf first to receive bookings.</p>
    <a href="{{ route('vendor.turf.manage') }}" class="text-emerald-600 font-medium hover:underline">Setup Turf</a>
  </div>
@else
<div class="flex gap-2 mb-6">
@foreach(['' => 'All', 'pending' => 'Pending', 'confirmed' => 'Confirmed', 'cancelled' => 'Cancelled'] as $value => $label)
  <a href="{{ route('vendor.bookings.index', $value ? ['status' => $value] : []) }}"
     class="px-4 py-2 rounded-lg text-sm font-medium transition
            {{ request('status') === $value || (!request('status') && $value === '') ? 'bg-emerald-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-emerald-300' }}">
    {{ $label }}
  </a>
@endforeach
</div>

@if($bookings->isEmpty())
  <div class="bg-white rounded-xl border border-gray-200 p-12 text-center text-gray-500">
    No bookings found.
  </div>
@else
  <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 border-b border-gray-200">
        <tr>
          <th class="text-left px-6 py-3 font-medium text-gray-500">Turf</th>
          <th class="text-left px-6 py-3 font-medium text-gray-500">Customer</th>
          <th class="text-left px-6 py-3 font-medium text-gray-500">Date & Time</th>
          <th class="text-left px-6 py-3 font-medium text-gray-500">Price</th>
          <th class="text-left px-6 py-3 font-medium text-gray-500">Status</th>
          <th class="text-right px-6 py-3 font-medium text-gray-500">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        @foreach($bookings as $booking)
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 font-medium">{{ $booking->turf->name }}</td>
            <td class="px-6 py-4">
              <p>{{ $booking->customer_name }}</p>
              <p class="text-gray-400 text-xs">{{ $booking->customer_phone }}</p>
            </td>
            <td class="px-6 py-4 text-gray-600">
              {{ $booking->booking_date->format('d M, Y') }}<br>
              {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} -
              {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
            </td>
            <td class="px-6 py-4 font-medium">Tk {{ number_format($booking->total_price) }}</td>
            <td class="px-6 py-4">
              <span class="px-2 py-1 rounded-full text-xs capitalize
                @if($booking->status === 'confirmed') bg-emerald-100 text-emerald-700
                @elseif($booking->status === 'cancelled') bg-red-100 text-red-700
                @else bg-amber-100 text-amber-700 @endif">
                {{ $booking->status }}
              </span>
            </td>
            <td class="px-6 py-4 text-right">
              <div class="inline-flex items-center gap-2">
                @if($booking->status === 'pending')
                  <form method="POST" action="{{ route('vendor.bookings.status', $booking) }}" class="inline-flex gap-1">
                    @csrf @method('PATCH')
                    <button name="status" value="confirmed" class="text-xs bg-emerald-600 text-white px-2 py-1 rounded hover:bg-emerald-700">Confirm</button>
                    <button name="status" value="cancelled" class="text-xs bg-amber-500 text-white px-2 py-1 rounded hover:bg-amber-600">Cancel</button>
                  </form>
                @endif
                <form method="POST" action="{{ route('vendor.bookings.destroy', $booking) }}" class="inline"
                      onsubmit="return confirm('Delete this booking?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="text-xs text-red-500 hover:underline">Delete</button>
                </form>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="mt-6">{{ $bookings->links() }}</div>
@endif
@endif
@endsection
