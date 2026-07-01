@extends('layouts.app')

@section('title', $turf->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
      <div class="turf-card-img rounded-3xl h-72 flex items-center justify-center mb-6">
        <span class="text-9xl relative z-10 drop-shadow-2xl">⚽</span>
      </div>

      <div class="glass-card p-7">
        <div class="flex items-start justify-between mb-5 gap-4">
          <div>
            <span class="badge-area mb-2 inline-block">{{ $turf->area->name }}, Dhaka</span>
            <h1 class="font-display text-3xl md:text-4xl font-bold text-pitch-900">{{ $turf->name }}</h1>
          </div>
          <div class="text-right shrink-0">
            <p class="text-pitch-900/40 text-xs uppercase tracking-wide mb-1">From</p>
            <p class="font-display text-3xl font-bold text-turf-700">Tk {{ number_format($turf->lowestHourlyPrice()) }}</p>
            <p class="text-pitch-900/40 text-sm">per hour · 90 min slots</p>
          </div>
        </div>

        <div class="grid grid-cols-3 gap-3 mb-5">
          <div class="bg-pitch-900/4 rounded-2xl p-3 text-center">
            <p class="text-xs text-pitch-900/40 uppercase">☀️ Day</p>
            <p class="font-semibold text-turf-700 mt-1">Tk {{ number_format($turf->day_price) }}</p>
            <p class="text-xs text-pitch-900/40">/hr · before 6 PM</p>
          </div>
          <div class="bg-pitch-900/4 rounded-2xl p-3 text-center">
            <p class="text-xs text-pitch-900/40 uppercase">🌙 Night</p>
            <p class="font-semibold text-turf-700 mt-1">Tk {{ number_format($turf->night_price) }}</p>
            <p class="text-xs text-pitch-900/40">/hr · from 6 PM</p>
          </div>
          <div class="bg-pitch-900/4 rounded-2xl p-3 text-center">
            <p class="text-xs text-pitch-900/40 uppercase">📅 Off-Day</p>
            <p class="font-semibold text-turf-700 mt-1">Tk {{ number_format($turf->offday_price) }}</p>
            <p class="text-xs text-pitch-900/40">/hr · Fri &amp; Sat</p>
          </div>
        </div>

        <p class="text-pitch-900/60 mb-4 flex items-center gap-1.5">
          <span>📍</span> {{ $turf->address }}
        </p>

        @if($turf->description)
          <p class="text-pitch-900/60 mb-6 leading-relaxed">{{ $turf->description }}</p>
        @endif

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
          @foreach([
            ['label' => 'Surface', 'value' => match($turf->surface_type) { 'artificial_grass' => 'Artificial', 'natural_grass' => 'Natural', 'indoor' => 'Indoor', default => $turf->surface_type }],
            ['label' => 'Size', 'value' => $turf->size ?? 'N/A'],
            ['label' => 'Opens', 'value' => \Carbon\Carbon::parse($turf->open_time)->format('g:i A')],
            ['label' => 'Closes', 'value' => \Carbon\Carbon::parse($turf->close_time)->format('g:i A')],
          ] as $stat)
            <div class="bg-pitch-900/4 rounded-2xl p-4 text-center">
              <p class="text-xs text-pitch-900/40 uppercase tracking-wide">{{ $stat['label'] }}</p>
              <p class="font-semibold text-sm mt-1 text-pitch-900">{{ $stat['value'] }}</p>
            </div>
          @endforeach
        </div>

        @if($turf->amenities)
          <div>
            <h3 class="font-display font-semibold mb-3 text-pitch-900">Amenities</h3>
            <div class="flex flex-wrap gap-2">
              @foreach($turf->amenities as $amenity)
                <span class="badge-lime">{{ $amenity }}</span>
              @endforeach
            </div>
          </div>
        @endif
      </div>
    </div>

    <div>
      <div class="glass-card p-6 sticky top-20">
        <h2 class="font-display text-xl font-bold text-pitch-900 mb-1">Book This Turf</h2>
        <p class="text-pitch-900/40 text-sm mb-5">Select a 90-minute slot</p>

        @guest
          <div class="bg-amber-400/10 border border-amber-400/30 rounded-2xl p-4 mb-5 text-sm text-amber-800">
            Please <a href="{{ route('login') }}" class="font-bold underline">sign in</a> to book.
          </div>
        @endguest

        <form method="GET" action="{{ route('turfs.show', $turf) }}" class="mb-5">
          <label class="form-label">Select Date</label>
          <input type="date" name="date" value="{{ $date->format('Y-m-d') }}"
                 min="{{ now()->format('Y-m-d') }}"
                 class="form-input"
                 onchange="this.form.submit()">
          <input type="hidden" name="period" id="period-input" value="{{ $activePeriod }}">
        </form>

        <p class="form-label mb-3">{{ $date->format('d M, Y') }} — 90 min slots</p>

        @if($isOffDay)
          <div class="bg-amber-400/10 border border-amber-400/30 rounded-2xl p-3 mb-4 text-sm text-amber-800">
            Off-day pricing applies — Tk {{ number_format($turf->offday_price) }}/hr for all slots (Friday &amp; Saturday).
          </div>
        @endif

        {{-- Day / Night tabs --}}
        <div class="flex gap-2 mb-4 p-1 bg-pitch-900/5 rounded-2xl">
          <button type="button" id="tab-day" onclick="switchSlotTab('day')"
                  class="slot-tab {{ $activePeriod === 'day' ? 'slot-tab-day-active' : 'slot-tab-inactive' }}">
            ☀️ Day
            <span class="ml-1 text-xs opacity-60">(before 6 PM)</span>
          </button>
          <button type="button" id="tab-night" onclick="switchSlotTab('night')"
                  class="slot-tab {{ $activePeriod === 'night' ? 'slot-tab-night-active' : 'slot-tab-inactive' }}">
            🌙 Night
            <span class="ml-1 text-xs opacity-60">(from 6 PM)</span>
          </button>
        </div>

        {{-- Day slots --}}
        <div id="slots-day" class="slot-panel {{ $activePeriod !== 'day' ? 'hidden' : '' }}">
          <div class="grid grid-cols-2 gap-2 mb-6 max-h-52 overflow-y-auto pr-1">
            @forelse($daySlots as $slot)
              <button type="button"
                      data-start="{{ $slot['start'] }}"
                      data-end="{{ $slot['end'] }}"
                      data-price="{{ $slot['price'] }}"
                      onclick="selectSlot(this)"
                      class="slot-btn {{ $slot['available'] ? 'slot-available' : 'slot-unavailable' }}"
                      {{ !$slot['available'] ? 'disabled' : '' }}>
                <span class="block">{{ $slot['label'] }}</span>
                <span class="block text-xs mt-0.5 opacity-70">Tk {{ number_format($slot['price']) }}</span>
              </button>
            @empty
              <p class="col-span-2 text-sm text-pitch-900/40 text-center py-4">No day slots available</p>
            @endforelse
          </div>
        </div>

        {{-- Night slots --}}
        <div id="slots-night" class="slot-panel {{ $activePeriod !== 'night' ? 'hidden' : '' }}">
          <div class="grid grid-cols-2 gap-2 mb-6 max-h-52 overflow-y-auto pr-1">
            @forelse($nightSlots as $slot)
              <button type="button"
                      data-start="{{ $slot['start'] }}"
                      data-end="{{ $slot['end'] }}"
                      data-price="{{ $slot['price'] }}"
                      onclick="selectSlot(this)"
                      class="slot-btn {{ $slot['available'] ? 'slot-available' : 'slot-unavailable' }}"
                      {{ !$slot['available'] ? 'disabled' : '' }}>
                <span class="block">{{ $slot['label'] }}</span>
                <span class="block text-xs mt-0.5 opacity-70">Tk {{ number_format($slot['price']) }}</span>
              </button>
            @empty
              <p class="col-span-2 text-sm text-pitch-900/40 text-center py-4">No night slots available</p>
            @endforelse
          </div>
        </div>

        @auth
          <form method="POST" action="{{ route('turfs.book', $turf) }}" id="booking-form">
            @csrf
            <input type="hidden" name="booking_date" value="{{ $date->format('Y-m-d') }}">
            <input type="hidden" name="start_time" id="start_time" value="">
            <input type="hidden" name="end_time" id="end_time" value="">

            <div class="space-y-3 mb-5">
              <div>
                <label class="form-label">Name</label>
                <input type="text" name="customer_name" value="{{ auth()->user()->name }}" class="form-input" required>
              </div>
              <div>
                <label class="form-label">Phone</label>
                <input type="text" name="customer_phone" value="{{ auth()->user()->phone }}" class="form-input" required>
              </div>
              <div>
                <label class="form-label">Notes <span class="text-pitch-900/30">(optional)</span></label>
                <textarea name="notes" rows="2" class="form-input"></textarea>
              </div>
            </div>

            <button type="submit" id="book-btn" disabled class="btn-lime w-full py-3 disabled:opacity-40 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none">
              <span id="book-btn-label">Confirm Booking</span>
            </button>
          </form>
        @endauth
      </div>
    </div>
  </div>
</div>

<script>
let activeTab = '{{ $activePeriod }}';

function switchSlotTab(period) {
  activeTab = period;
  document.getElementById('period-input').value = period;

  document.getElementById('slots-day').classList.toggle('hidden', period !== 'day');
  document.getElementById('slots-night').classList.toggle('hidden', period !== 'night');

  const tabDay = document.getElementById('tab-day');
  const tabNight = document.getElementById('tab-night');
  tabDay.className = 'slot-tab ' + (period === 'day' ? 'slot-tab-day-active' : 'slot-tab-inactive');
  tabNight.className = 'slot-tab ' + (period === 'night' ? 'slot-tab-night-active' : 'slot-tab-inactive');

  clearSlotSelection();
}

function clearSlotSelection() {
  document.querySelectorAll('.slot-btn:not([disabled])').forEach(b => {
    b.classList.remove('slot-selected');
    b.classList.add('slot-available');
  });
  document.getElementById('start_time').value = '';
  document.getElementById('end_time').value = '';
  const bookBtn = document.getElementById('book-btn');
  if (bookBtn) bookBtn.disabled = true;
  const bookLabel = document.getElementById('book-btn-label');
  if (bookLabel) bookLabel.textContent = 'Confirm Booking';
}

function selectSlot(btn) {
  clearSlotSelection();
  btn.classList.add('slot-selected');
  btn.classList.remove('slot-available');
  document.getElementById('start_time').value = btn.dataset.start;
  document.getElementById('end_time').value = btn.dataset.end;
  document.getElementById('book-btn').disabled = false;
  const price = btn.dataset.price;
  const bookLabel = document.getElementById('book-btn-label');
  if (bookLabel && price) {
    bookLabel.textContent = 'Confirm Booking — Tk ' + Number(price).toLocaleString();
  }
}
</script>
@endsection
