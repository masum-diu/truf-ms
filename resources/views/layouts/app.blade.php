<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'TurfBook Dhaka') - {{ config('app.name', 'TurfBook') }}</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col pitch-pattern-light text-pitch-900">
    <nav class="site-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('home') }}" class="nav-brand">
                    <span class="nav-brand-icon">⚽</span>
                    <span>Turf<span class="text-lime-400">Book</span></span>
                </a>
                <div class="flex items-center gap-1 sm:gap-2">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">Home</a>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="nav-link">Admin</a>
                        @elseif(auth()->user()->isVendor())
                            <a href="{{ route('vendor.dashboard') }}" class="nav-link">Vendor</a>
                        @else
                            <a href="{{ route('bookings.index') }}" class="nav-link">My Bookings</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline ml-1">
                            @csrf
                            <button type="submit" class="nav-link">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">Login</a>
                        <a href="{{ route('register') }}" class="btn-lime ml-2">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
            <div class="alert-success">{{ session('success') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
            <div class="alert-error">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="pitch-pattern text-white/60 py-10 mt-auto border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="nav-brand-icon text-sm w-8 h-8">⚽</span>
                <span class="font-display font-bold text-white">Turf<span class="text-lime-400">Book</span></span>
            </div>
            <p class="text-sm">Book premium turfs across Dhaka — 90 min slots</p>
            <p class="text-xs text-white/30">© {{ date('Y') }} TurfBook Dhaka</p>
        </div>
    </footer>
</body>
</html>
