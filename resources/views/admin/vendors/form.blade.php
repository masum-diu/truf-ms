@extends('layouts.admin')

@section('title', isset($vendor) ? 'Edit Vendor' : 'Create Vendor')
@section('heading', isset($vendor) ? 'Edit Vendor' : 'Create Vendor Account')

@section('content')
<div class="max-w-2xl">
  @if(!isset($vendor))
    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 mb-6 text-sm text-slate-700">
      Create a vendor account here. After login, the vendor will set up and manage their single turf from the vendor panel.
    </div>
  @endif

  <form method="POST"
        action="{{ isset($vendor) ? route('admin.vendors.update', $vendor) : route('admin.vendors.store') }}"
        class="bg-white rounded-2xl border border-pitch-900/8 p-6 space-y-4">
    @csrf
    @if(isset($vendor)) @method('PUT') @endif

    <div>
      <label class="form-label">Name *</label>
      <input type="text" name="name" value="{{ old('name', $vendor->name ?? '') }}" required class="form-input">
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
      <input type="email" name="email" value="{{ old('email', $vendor->email ?? '') }}" required
             class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-slate-500">
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
      <input type="text" name="phone" value="{{ old('phone', $vendor->phone ?? '') }}" required placeholder="01XXXXXXXXX"
             class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-slate-500">
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">
        Password {{ isset($vendor) ? '(leave blank to keep current)' : '*' }}
      </label>
      <input type="password" name="password" {{ isset($vendor) ? '' : 'required' }}
             class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-slate-500">
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
      <input type="password" name="password_confirmation"
             class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-slate-500">
    </div>

    <div class="flex gap-3 pt-2">
      <a href="{{ route('admin.vendors.index') }}" class="flex-1 text-center border border-gray-300 py-3 rounded-lg hover:bg-gray-50 transition">
        Cancel
      </a>
      <button type="submit" class="flex-1 btn-panel-admin py-3">
        {{ isset($vendor) ? 'Update Vendor' : 'Create Vendor' }}
      </button>
    </div>
  </form>
</div>
@endsection
