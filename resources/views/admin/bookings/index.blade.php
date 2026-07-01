@extends('layouts.admin')

@section('title', 'All Bookings')
@section('heading', 'All Bookings')

@section('content')
<div class="flex gap-2 mb-6">
@foreach(['' => 'All', 'pending' => 'Pending', 'confirmed' => 'Confirmed', 'cancelled' => 'Cancelled'] as $value => $label)
  <a href="{{ route('admin.bookings.index', $value ? ['status' => $value] : []) }}"
     class="px-4 py-2 rounded-lg text-sm font-medium transition
            {{ request('status') === $value || (!request('status') && $value === '') ? 'bg-slate-800 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-slate-400' }}">
    {{ $label }}
  </a>
@endforeach
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-gray-50 border-b border-gray-200">
      <tr>
        <th class="text-left px-6 py-3 font-medium text-gray-500">Turf</th>
        <th class="text-left px-6 py-3 font-medium text-gray-500">Vendor</th>
        <th class="text-left px-6 py-3 font-medium text-gray-500">Customer</th>
        <th class="text-left px-6 py-3 font-medium text-gray-500">Date & Time</th>
        <th class="text-left px-6 py-3 font-medium text-gray-500">Price</th>
        <th class="text-left px-6 py-3 font-medium text-gray-500">Status</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-100">
      @forelse($bookings as $booking)
        <tr class="hover:bg-gray-50">
          <td class="px-6 py-4 font-medium">{{ $booking->turf->name }}</td>
          <td class="px-6 py-4">
            <a href="{{ route('admin.vendors.show', $booking->turf->owner) }}" class="text-slate-600 hover:underline">
              {{ $booking->turf->owner->name }}
            </a>
          </td>
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
        </tr>
      @empty
        <tr>
          <td colspan="6" class="px-6 py-12 text-center text-gray-500">No bookings found.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-6">{{ $bookings->links() }}</div>
@endsection
