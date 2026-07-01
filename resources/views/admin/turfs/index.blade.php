@extends('layouts.admin')

@section('title', 'All Turfs')
@section('heading', 'All Turfs')

@section('content')
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-gray-50 border-b border-gray-200">
      <tr>
        <th class="text-left px-6 py-3 font-medium text-gray-500">Turf</th>
        <th class="text-left px-6 py-3 font-medium text-gray-500">Vendor</th>
        <th class="text-left px-6 py-3 font-medium text-gray-500">Area</th>
        <th class="text-left px-6 py-3 font-medium text-gray-500">Pricing (Day/Night/Off)</th>
        <th class="text-left px-6 py-3 font-medium text-gray-500">Bookings</th>
        <th class="text-left px-6 py-3 font-medium text-gray-500">Status</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-100">
      @forelse($turfs as $turf)
        <tr class="hover:bg-gray-50">
          <td class="px-6 py-4 font-medium">
            <a href="{{ route('turfs.show', $turf) }}" class="text-slate-600 hover:underline">{{ $turf->name }}</a>
          </td>
          <td class="px-6 py-4">
            <a href="{{ route('admin.vendors.show', $turf->owner) }}" class="text-slate-600 hover:underline">{{ $turf->owner->name }}</a>
          </td>
          <td class="px-6 py-4">{{ $turf->area->name }}</td>
          <td class="px-6 py-4 text-xs">
            D: {{ number_format($turf->day_price) }} · N: {{ number_format($turf->night_price) }} · O: {{ number_format($turf->offday_price) }}
          </td>
          <td class="px-6 py-4">{{ $turf->bookings_count }}</td>
          <td class="px-6 py-4">
            <span class="px-2 py-1 rounded-full text-xs {{ $turf->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
              {{ $turf->is_active ? 'Active' : 'Inactive' }}
            </span>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="px-6 py-12 text-center text-gray-500">No turfs found.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-6">{{ $turfs->links() }}</div>
@endsection
