@extends('layouts.app')

@section('title', 'Home')

@section('content')
{{-- Hero --}}
<div class="pitch-pattern relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-pitch-900/50 pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full opacity-20 pointer-events-none"
         style="background: radial-gradient(circle, #a3e635 0%, transparent 70%)"></div>
    <div class="relative max-w-7xl mx-auto px-4 py-20 md:py-28 text-center">
        <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-1.5 text-lime-300 text-xs font-medium mb-6 backdrop-blur-sm">
            <span class="w-1.5 h-1.5 rounded-full bg-lime-400 animate-pulse"></span>
            Live turf availability across Dhaka
        </div>
        <h1 class="font-display text-5xl md:text-7xl font-extrabold text-white tracking-tight mb-5 leading-none">
            Play on the<br><span class="text-lime-400">Best Turfs</span>
        </h1>
        <p class="text-white/60 text-lg max-w-xl mx-auto mb-8">Find and book premium football turfs in your area. 90-minute slots, instant booking.</p>
        <a href="#areas" class="btn-lime text-base px-8 py-3">Browse Areas ↓</a>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-12">
  <section class="mb-14" id="areas">
    <div class="flex items-end justify-between mb-8">
      <div>
        <p class="text-lime-500 text-sm font-semibold uppercase tracking-widest mb-1">Locations</p>
        <h2 class="section-title">Browse by Area</h2>
      </div>
      @if($selectedArea)
        <a href="{{ route('home') }}" class="text-sm text-turf-700 hover:text-lime-500 font-medium transition">← All areas</a>
      @endif
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
      @foreach($areas as $area)
        <a href="{{ route('home', ['area' => $area->slug]) }}"
           class="area-chip {{ $selectedArea?->id === $area->id ? 'area-chip-active' : 'area-chip-default' }}">
          <p class="font-display font-bold text-base">{{ $area->name }}</p>
          <p class="text-xs mt-1 opacity-70">{{ $area->active_turfs_count }} turf{{ $area->active_turfs_count !== 1 ? 's' : '' }}</p>
        </a>
      @endforeach
    </div>
  </section>

  @if($selectedArea)
    <section class="mb-14">
      <div class="mb-8">
        <p class="text-lime-500 text-sm font-semibold uppercase tracking-widest mb-1">{{ $selectedArea->name }}</p>
        <h2 class="section-title">Available Turfs</h2>
      </div>

      @if($turfs->isEmpty())
        <div class="glass-card p-16 text-center text-pitch-900/50">
          <p class="text-4xl mb-3">🏟️</p>
          <p class="text-lg font-medium">No turfs in this area yet.</p>
        </div>
      @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($turfs as $turf)
            @include('partials.turf-card', ['turf' => $turf])
          @endforeach
        </div>
      @endif
    </section>
  @endif

  @if(!$selectedArea && $featuredTurfs->isNotEmpty())
    <section>
      <div class="mb-8">
        <p class="text-lime-500 text-sm font-semibold uppercase tracking-widest mb-1">Top Picks</p>
        <h2 class="section-title">Popular Turfs</h2>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($featuredTurfs as $turf)
          @include('partials.turf-card', ['turf' => $turf])
        @endforeach
      </div>
    </section>
  @endif
</div>
@endsection
