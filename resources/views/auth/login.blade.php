@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 py-12 pitch-pattern relative">
    <div class="absolute inset-0 bg-pitch-900/60"></div>
    <div class="auth-card relative z-10">
        <div class="text-center mb-8">
            <div class="nav-brand-icon w-12 h-12 text-xl mx-auto mb-4">⚽</div>
            <h1 class="font-display text-2xl font-bold text-pitch-900">Welcome Back</h1>
            <p class="text-pitch-900/50 text-sm mt-1">Sign in to your TurfBook account</p>
        </div>
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="form-input">
            </div>
            <div>
                <label class="form-label">Password</label>
                <input type="password" name="password" required class="form-input">
            </div>
            <label class="flex items-center gap-2 text-sm text-pitch-900/60">
                <input type="checkbox" name="remember" class="rounded border-pitch-900/20 text-lime-500">
                Remember me
            </label>
            <button type="submit" class="btn-lime w-full py-3 text-base">Sign In</button>
        </form>
        <p class="text-center text-sm text-pitch-900/50 mt-6">
            No account? <a href="{{ route('register') }}" class="text-turf-700 font-semibold hover:text-lime-500 transition">Register free</a>
        </p>
    </div>
</div>
@endsection
