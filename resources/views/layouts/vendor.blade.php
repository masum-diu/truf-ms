<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Vendor Panel') - {{ config('app.name', 'TurfBook') }}</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-surface pitch-pattern-light">
    <div class="flex min-h-screen">
        <aside class="panel-sidebar panel-sidebar-vendor">
            <div class="p-6 border-b border-white/10">
                <a href="{{ route('home') }}" class="nav-brand text-base">
                    <span class="nav-brand-icon text-sm w-8 h-8">⚽</span>
                    <span>Turf<span class="text-lime-400">Book</span></span>
                </a>
                <p class="text-lime-400/70 text-xs mt-2 font-medium uppercase tracking-widest">Vendor Panel</p>
            </div>
            <nav class="flex-1 p-4 space-y-1">
                <a href="{{ route('vendor.dashboard') }}"
                   class="panel-nav-link {{ request()->routeIs('vendor.dashboard') ? 'panel-nav-link-active' : '' }}">
                    <span>📊</span> Dashboard
                </a>
                <a href="{{ route('vendor.turf.manage') }}"
                   class="panel-nav-link {{ request()->routeIs('vendor.turf.*') ? 'panel-nav-link-active' : '' }}">
                    <span>🏟️</span> My Turf
                </a>
                <a href="{{ route('vendor.bookings.index') }}"
                   class="panel-nav-link {{ request()->routeIs('vendor.bookings.*') ? 'panel-nav-link-active' : '' }}">
                    <span>📅</span> Bookings
                </a>
                <a href="{{ route('vendor.profile.edit') }}"
                   class="panel-nav-link {{ request()->routeIs('vendor.profile.*') ? 'panel-nav-link-active' : '' }}">
                    <span>👤</span> Profile
                </a>
            </nav>
            <div class="p-4 border-t border-white/10">
                <p class="text-xs text-white/40 mb-1">Signed in as</p>
                <p class="text-sm text-white/80 font-medium truncate mb-3">{{ auth()->user()->name }}</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-white/50 hover:text-white transition">Logout →</button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white/80 backdrop-blur-xl border-b border-pitch-900/8 px-6 py-4 sticky top-0 z-10">
                <h1 class="font-display text-lg font-bold text-pitch-900">@yield('heading', 'Vendor Panel')</h1>
            </header>

            @if(session('success'))
                <div class="mx-6 mt-4"><div class="alert-success">{{ session('success') }}</div></div>
            @endif
            @if ($errors->any())
                <div class="mx-6 mt-4">
                    <div class="alert-error">
                        <ul class="list-disc list-inside">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                </div>
            @endif

            <main class="flex-1 p-6">@yield('content')</main>
        </div>
    </div>
</body>
</html>
