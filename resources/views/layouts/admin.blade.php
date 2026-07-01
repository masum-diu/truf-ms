<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'TurfBook') }}</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-surface pitch-pattern-light">
    <div class="flex min-h-screen">
        <aside class="panel-sidebar">
            <div class="p-6 border-b border-white/10">
                <a href="{{ route('home') }}" class="nav-brand text-base">
                    <span class="nav-brand-icon text-sm w-8 h-8">⚽</span>
                    <span>Turf<span class="text-amber-glow">Book</span></span>
                </a>
                <p class="text-amber-glow/70 text-xs mt-2 font-medium uppercase tracking-widest">Admin Panel</p>
            </div>
            <nav class="flex-1 p-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="panel-nav-link {{ request()->routeIs('admin.dashboard') ? 'panel-nav-link-admin-active' : '' }}">
                    <span>📊</span> Dashboard
                </a>
                <a href="{{ route('admin.vendors.index') }}"
                   class="panel-nav-link {{ request()->routeIs('admin.vendors.index') || request()->routeIs('admin.vendors.show') || request()->routeIs('admin.vendors.edit') ? 'panel-nav-link-admin-active' : '' }}">
                    <span>👥</span> All Vendors
                </a>
                <a href="{{ route('admin.vendors.create') }}"
                   class="panel-nav-link {{ request()->routeIs('admin.vendors.create') ? 'panel-nav-link-admin-active' : '' }}">
                    <span>➕</span> Create Vendor
                </a>
                <a href="{{ route('admin.turfs.index') }}"
                   class="panel-nav-link {{ request()->routeIs('admin.turfs.*') ? 'panel-nav-link-admin-active' : '' }}">
                    <span>🏟️</span> All Turfs
                </a>
                <a href="{{ route('admin.bookings.index') }}"
                   class="panel-nav-link {{ request()->routeIs('admin.bookings.*') ? 'panel-nav-link-admin-active' : '' }}">
                    <span>📅</span> All Bookings
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
                <div class="flex items-center justify-between gap-4 flex-wrap">
                    <h1 class="font-display text-lg font-bold text-pitch-900">@yield('heading', 'Admin Panel')</h1>
                    <nav class="flex items-center gap-1 flex-wrap">
                        <a href="{{ route('admin.dashboard') }}"
                           class="px-3 py-1.5 rounded-xl text-sm transition {{ request()->routeIs('admin.dashboard') ? 'bg-pitch-900/8 text-pitch-900 font-medium' : 'text-pitch-900/60 hover:bg-pitch-900/5' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.vendors.index') }}"
                           class="px-3 py-1.5 rounded-xl text-sm transition {{ request()->routeIs('admin.vendors.index') || request()->routeIs('admin.vendors.show') || request()->routeIs('admin.vendors.edit') ? 'bg-pitch-900/8 text-pitch-900 font-medium' : 'text-pitch-900/60 hover:bg-pitch-900/5' }}">
                            Vendors
                        </a>
                        <a href="{{ route('admin.vendors.create') }}" class="btn-panel-admin">
                            + Create Vendor
                        </a>
                        <a href="{{ route('admin.turfs.index') }}"
                           class="hidden sm:inline-block px-3 py-1.5 rounded-xl text-sm transition {{ request()->routeIs('admin.turfs.*') ? 'bg-pitch-900/8 text-pitch-900 font-medium' : 'text-pitch-900/60 hover:bg-pitch-900/5' }}">
                            Turfs
                        </a>
                        <a href="{{ route('admin.bookings.index') }}"
                           class="hidden sm:inline-block px-3 py-1.5 rounded-xl text-sm transition {{ request()->routeIs('admin.bookings.*') ? 'bg-pitch-900/8 text-pitch-900 font-medium' : 'text-pitch-900/60 hover:bg-pitch-900/5' }}">
                            Bookings
                        </a>
                    </nav>
                </div>
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
