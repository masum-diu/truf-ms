@extends('layouts.vendor')

@section('title', 'My Turf')
@section('heading', $turf ? 'Manage My Turf' : 'Set Up My Turf')

@section('content')
<div class="max-w-2xl">
  @if(!$turf)
    <p class="text-gray-500 text-sm mb-6">Each vendor can register one turf. Fill in the details below to get started.</p>
  @else
    <p class="text-gray-500 text-sm mb-6">Update your turf details, pricing, and availability.</p>
  @endif

  <form method="POST"
        action="{{ $turf ? route('vendor.turf.update') : route('vendor.turf.store') }}"
        class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
    @csrf
    @if($turf) @method('PUT') @endif

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Turf Name *</label>
      <input type="text" name="name" value="{{ old('name', $turf->name ?? '') }}" required
             class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500">
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Area *</label>
      <select name="area_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500">
        <option value="">Select an area</option>
        @foreach($areas as $area)
          <option value="{{ $area->id }}" {{ old('area_id', $turf->area_id ?? '') == $area->id ? 'selected' : '' }}>
            {{ $area->name }}
          </option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Address *</label>
      <textarea name="address" rows="2" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500">{{ old('address', $turf->address ?? '') }}</textarea>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
      <textarea name="description" rows="3"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500">{{ old('description', $turf->description ?? '') }}</textarea>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Day Price / Hour (Tk) *</label>
        <input type="number" name="day_price" value="{{ old('day_price', $turf->day_price ?? $turf->price_per_hour ?? '') }}" min="100" required
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500">
        <p class="text-xs text-gray-500 mt-1">Weekday slots before 6 PM</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Night Price / Hour (Tk) *</label>
        <input type="number" name="night_price" value="{{ old('night_price', $turf->night_price ?? $turf->price_per_hour ?? '') }}" min="100" required
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500">
        <p class="text-xs text-gray-500 mt-1">Weekday slots from 6 PM</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Off-Day Price / Hour (Tk) *</label>
        <input type="number" name="offday_price" value="{{ old('offday_price', $turf->offday_price ?? $turf->price_per_hour ?? '') }}" min="100" required
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500">
        <p class="text-xs text-gray-500 mt-1">Friday &amp; Saturday (all slots)</p>
      </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Size</label>
        <input type="text" name="size" value="{{ old('size', $turf->size ?? '') }}" placeholder="e.g. 7-a-side"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500">
      </div>
      <div></div>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Surface Type *</label>
      <select name="surface_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500">
        <option value="artificial_grass" {{ old('surface_type', $turf->surface_type ?? '') === 'artificial_grass' ? 'selected' : '' }}>Artificial Grass</option>
        <option value="natural_grass" {{ old('surface_type', $turf->surface_type ?? '') === 'natural_grass' ? 'selected' : '' }}>Natural Grass</option>
        <option value="indoor" {{ old('surface_type', $turf->surface_type ?? '') === 'indoor' ? 'selected' : '' }}>Indoor</option>
      </select>
    </div>

    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Opening Time *</label>
        <input type="time" name="open_time" value="{{ old('open_time', isset($turf) ? \Carbon\Carbon::parse($turf->open_time)->format('H:i') : '06:00') }}" required
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Closing Time *</label>
        <input type="time" name="close_time" value="{{ old('close_time', isset($turf) ? \Carbon\Carbon::parse($turf->close_time)->format('H:i') : '23:00') }}" required
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500">
      </div>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Amenities (comma separated)</label>
      <input type="text" name="amenities"
             value="{{ old('amenities', isset($turf) && $turf->amenities ? implode(', ', $turf->amenities) : '') }}"
             placeholder="Parking, Floodlights, Dressing Room"
             class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500">
    </div>

    @if($turf)
      <label class="flex items-center gap-2">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $turf->is_active) ? 'checked' : '' }} class="rounded">
        <span class="text-sm font-medium text-gray-700">Turf is active and visible to customers</span>
      </label>
    @endif

    <button type="submit" class="w-full bg-emerald-600 text-white py-3 rounded-lg font-semibold hover:bg-emerald-700 transition">
      {{ $turf ? 'Update Turf' : 'Create My Turf' }}
    </button>
  </form>
</div>
@endsection
