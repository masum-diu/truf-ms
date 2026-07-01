@extends('layouts.admin')

@section('title', 'Vendors')
@section('heading', 'Manage Vendors')

@section('content')
<div class="flex items-center justify-between mb-6">
  <p class="text-gray-500 text-sm">Create and manage all vendor accounts</p>
  <a href="{{ route('admin.vendors.create') }}" class="btn-panel-admin">+ Create Vendor</a>
</div>

<div class="panel-table-wrap">
  <table class="w-full text-sm">
    <thead class="bg-gray-50 border-b border-gray-200">
      <tr>
        <th class="text-left px-6 py-3 font-medium text-gray-500">Name</th>
        <th class="text-left px-6 py-3 font-medium text-gray-500">Email</th>
        <th class="text-left px-6 py-3 font-medium text-gray-500">Phone</th>
        <th class="text-left px-6 py-3 font-medium text-gray-500">Turf</th>
        <th class="text-left px-6 py-3 font-medium text-gray-500">Joined</th>
        <th class="text-right px-6 py-3 font-medium text-gray-500">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-100">
      @forelse($vendors as $vendor)
        <tr class="hover:bg-gray-50">
          <td class="px-6 py-4 font-medium">{{ $vendor->name }}</td>
          <td class="px-6 py-4 text-gray-600">{{ $vendor->email }}</td>
          <td class="px-6 py-4 text-gray-600">{{ $vendor->phone }}</td>
          <td class="px-6 py-4">
            @if($vendor->turf)
              <span class="text-emerald-700">{{ $vendor->turf->name }}</span>
            @else
              <span class="text-amber-600 text-sm">Not setup</span>
            @endif
          </td>
          <td class="px-6 py-4 text-gray-500">{{ $vendor->created_at->format('d M, Y') }}</td>
          <td class="px-6 py-4 text-right space-x-3">
            <a href="{{ route('admin.vendors.show', $vendor) }}" class="text-gray-500 hover:text-gray-700">View</a>
            <a href="{{ route('admin.vendors.edit', $vendor) }}" class="text-slate-600 hover:underline">Edit</a>
            <form method="POST" action="{{ route('admin.vendors.destroy', $vendor) }}" class="inline"
                  onsubmit="return confirm('Delete this vendor and all their turfs?')">
              @csrf @method('DELETE')
              <button type="submit" class="text-red-500 hover:underline">Delete</button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="px-6 py-12 text-center text-gray-500">
            No vendors found.
            <a href="{{ route('admin.vendors.create') }}" class="block text-slate-700 font-medium hover:underline mt-2">Create vendor account</a>
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-6">{{ $vendors->links() }}</div>
@endsection
