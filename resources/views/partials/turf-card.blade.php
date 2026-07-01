<div class="glass-card transition-all duration-300">
  <div class="turf-card-img flex items-center justify-center">
    <span class="text-7xl relative z-10 drop-shadow-lg">⚽</span>
    <div class="absolute bottom-3 left-3 z-10">
      <span class="badge-area">{{ $turf->area->name }}</span>
    </div>
    @if($turf->size)
      <div class="absolute top-3 right-3 z-10">
        <span class="badge-lime">{{ $turf->size }}</span>
      </div>
    @endif
  </div>
  <div class="p-5">
    <h3 class="font-display font-bold text-lg text-pitch-900 mb-1">{{ $turf->name }}</h3>
    <p class="text-pitch-900/50 text-sm mb-4 line-clamp-2">{{ $turf->address }}</p>
    <div class="flex items-center justify-between pt-4 border-t border-pitch-900/8">
      <div>
        <span class="font-display font-bold text-xl text-turf-700">From Tk {{ number_format($turf->lowestHourlyPrice()) }}</span>
        <span class="text-pitch-900/40 text-xs">/hour</span>
      </div>
      <a href="{{ route('turfs.show', $turf) }}" class="btn-lime text-xs px-4 py-2">
        Book Now
      </a>
    </div>
  </div>
</div>
