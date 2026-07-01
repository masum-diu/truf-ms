@extends('layouts.admin')

@section('title', $vendor->name)
@section('heading', $vendor->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
  <div class="bg-white rounded-xl border border-gray-200 p-5">
    <p class="text-gray-500 text-sm">Email</p>
    <p class="font-medium mt-1">{{ $vendor->email }}</p>
  </div>
  <div class="bg-white rounded-xl border border-gray-200 p-5">
    <p class="text-gray-500 text-sm">Phone</p>
    <p class="font-medium mt-1">{{ $vendor->phone }}</p>
  </div>
  <div class="bg-white rounded-xl border border-gray-200 p-5">
    <p class="text-gray-500 text-sm">Turf Status</p>
    <p class="font-medium mt-1">
      @if($turf)
        <span class="text-emerald-600">{{ $turf->name }}</span>
      @else
        <span class="text-amber-600">Not setup yet</span>
      @endif
    </p>
  </div>
</div>

<div class="flex items-center justify-between mb-4">
  <h2 class="text-lg font-bold">Vendor Turf</h2>
  <a href="{{ route('admin.vendors.edit', $vendor) }}" class="text-slate-600 text-sm hover:underline">Edit Vendor</a>
</div>

@if(!$turf)
  <div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-500 mb-8">
    This vendor has not set up their turf yet.
  </div>
@else
  <div class="bg-white rounded-xl border border-gray-200 p-6 mb-8">
    <div class="flex items-start justify-between mb-4">
      <div>
        <h3 class="font-bold text-lg">{{ $turf->name }}</h3>
        <p class="text-sm text-gray-500">{{ $turf->area->name }} • {{ $turf->address }}</p>
      </div>
      <span class="px-2 py-1 rounded-full text-xs {{ $turf->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
        {{ $turf->is_active ? 'Active' : 'Inactive' }}
      </span>
    </div>
    <div class="grid grid-cols-4 gap-4 text-sm">
      <div>
        <p class="text-gray-500">Day Price</p>
        <p class="font-medium">Tk {{ number_format($turf->day_price) }}/hr</p>
      </div>
      <div>
        <p class="text-gray-500">Night Price</p>
        <p class="font-medium">Tk {{ number_format($turf->night_price) }}/hr</p>
      </div>
      <div>
        <p class="text-gray-500">Off-Day Price</p>
        <p class="font-medium">Tk {{ number_format($turf->offday_price) }}/hr</p>
      </div>
      <div>
        <p class="text-gray-500">Size</p>
        <p class="font-medium">{{ $turf->size ?? 'N/A' }}</p>
      </div>
    </div>
    <div class="grid grid-cols-1 gap-4 text-sm mt-4">
      <div>
        <p class="text-gray-500">Bookings</p>
        <p class="font-medium">{{ $turf->bookings_count }}</p>
      </div>
    </div>
    <a href="{{ route('turfs.show', $turf) }}" class="text-slate-600 text-sm hover:underline mt-4 inline-block">View public page</a>
  </div>
@endif

<h2 class="text-lg font-bold mb-4">Recent Bookings</h2>
@if($bookings->isEmpty())
  <div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-500">
    No bookings for this vendor.
  </div>
@else
  <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">
    @foreach($bookings as $booking)
      <div class="p-4 flex items-center justify-between">
        <div>
          <p class="font-medium">{{ $booking->customer_name }}</p>
          <p class="text-sm text-gray-500">
            {{ $booking->booking_date->format('d M, Y') }} • Tk {{ number_format($booking->total_price) }}
          </p>
        </div>
        <span class="text-xs px-2 py-1 rounded-full capitalize
          @if($booking->status === 'confirmed') bg-emerald-100 text-emerald-700
          @elseif($booking->status === 'cancelled') bg-red-100 text-red-700
          @else bg-amber-100 text-amber-700 @endif">
          {{ $booking->status }}
        </span>
      </div>
    @endforeach
  </div>
@endif
@endsection
