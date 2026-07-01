@extends('layouts.vendor')

@section('title', 'Profile')
@section('heading', 'My Profile')

@section('content')
<div class="max-w-2xl">
  <p class="text-gray-500 text-sm mb-6">Update your account details and password</p>

  <form method="POST" action="{{ route('vendor.profile.update') }}"
        class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
    @csrf
    @method('PUT')

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
      <input type="text" name="name" value="{{ old('name', $vendor->name) }}" required
             class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500">
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
      <input type="email" name="email" value="{{ old('email', $vendor->email) }}" required
             class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500">
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
      <input type="text" name="phone" value="{{ old('phone', $vendor->phone) }}" required
             class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500">
    </div>

    <hr class="border-gray-200">

    <p class="text-sm font-medium text-gray-700">Change Password (optional)</p>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
      <input type="password" name="password"
             class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500">
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
      <input type="password" name="password_confirmation"
             class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500">
    </div>

    <button type="submit" class="w-full bg-emerald-600 text-white py-3 rounded-lg font-semibold hover:bg-emerald-700 transition">
      Update Profile
    </button>
  </form>
</div>
@endsection
