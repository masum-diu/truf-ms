@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 py-12 pitch-pattern relative">
    <div class="absolute inset-0 bg-pitch-900/60"></div>
    <div class="auth-card relative z-10">
        <div class="text-center mb-8">
            <div class="nav-brand-icon w-12 h-12 text-xl mx-auto mb-4">⚽</div>
            <h1 class="font-display text-2xl font-bold text-pitch-900">Create Account</h1>
            <p class="text-pitch-900/50 text-sm mt-1">Book turfs across Dhaka instantly</p>
        </div>
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <div>
                <label class="form-label">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-input">
            </div>
            <div>
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="form-input">
            </div>
            <div>
                <label class="form-label">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="01XXXXXXXXX" class="form-input">
            </div>
            <div>
                <label class="form-label">Password</label>
                <input type="password" name="password" required class="form-input">
            </div>
            <div>
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" required class="form-input">
            </div>
            <button type="submit" class="btn-lime w-full py-3 text-base">Create Account</button>
        </form>
        <p class="text-center text-sm text-pitch-900/50 mt-6">
            Already have an account? <a href="{{ route('login') }}" class="text-turf-700 font-semibold hover:text-lime-500 transition">Sign in</a>
        </p>
    </div>
</div>
@endsection
