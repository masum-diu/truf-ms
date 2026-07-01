<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Turf;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TurfController extends Controller
{
    private const SLOT_MINUTES = 90;

    private const NIGHT_START_HOUR = 18;
    public function show(Turf $turf, Request $request): View
    {
        abort_unless($turf->is_active, 404);

        $turf->load('area', 'owner');

        $date = $request->filled('date')
            ? Carbon::parse($request->date)
            : Carbon::today();

        $bookedSlots = Booking::query()
            ->where('turf_id', $turf->id)
            ->whereDate('booking_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get(['start_time', 'end_time']);

        $timeSlots = $this->generateTimeSlots($turf, $date, $bookedSlots);
        $daySlots = array_values(array_filter($timeSlots, fn ($s) => $s['period'] === 'day'));
        $nightSlots = array_values(array_filter($timeSlots, fn ($s) => $s['period'] === 'night'));

        $activePeriod = $request->get('period', 'day');
        if (! in_array($activePeriod, ['day', 'night'], true)) {
            $activePeriod = 'day';
        }
        if ($activePeriod === 'day' && empty($daySlots) && ! empty($nightSlots)) {
            $activePeriod = 'night';
        } elseif ($activePeriod === 'night' && empty($nightSlots) && ! empty($daySlots)) {
            $activePeriod = 'day';
        }

        $isOffDay = $turf->isOffDay($date);

        return view('turfs.show', compact('turf', 'date', 'daySlots', 'nightSlots', 'activePeriod', 'isOffDay'));
    }

    public function book(Request $request, Turf $turf): RedirectResponse
    {
        abort_unless($turf->is_active, 404);

        $validated = $request->validate([
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $start = Carbon::createFromFormat('H:i', $validated['start_time']);
        $end = Carbon::createFromFormat('H:i', $validated['end_time']);
        $minutes = $start->diffInMinutes($end);

        if ($minutes < self::SLOT_MINUTES) {
            return back()->withErrors(['start_time' => 'Minimum booking duration is 90 minutes.'])->withInput();
        }

        $conflict = Booking::query()
            ->where('turf_id', $turf->id)
            ->whereDate('booking_date', $validated['booking_date'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($validated) {
                $query->where('start_time', '<', $validated['end_time'])
                    ->where('end_time', '>', $validated['start_time']);
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors(['start_time' => 'This time slot is already booked. Please choose another time.'])->withInput();
        }

        $bookingDate = Carbon::parse($validated['booking_date']);
        $period = (int) $start->format('H') >= self::NIGHT_START_HOUR ? 'night' : 'day';

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'turf_id' => $turf->id,
            'booking_date' => $validated['booking_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'total_price' => $turf->slotPrice($bookingDate, $period, $minutes),
            'status' => 'pending',
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('bookings.show', $booking)
            ->with('success', 'Booking completed successfully!');
    }

    private function generateTimeSlots(Turf $turf, Carbon $date, $bookedSlots): array
    {
        $slots = [];
        $open = Carbon::parse($turf->open_time);
        $close = Carbon::parse($turf->close_time);

        $current = $open->copy();

        while ($current->lt($close)) {
            $end = $current->copy()->addMinutes(self::SLOT_MINUTES);
            if ($end->gt($close)) {
                break;
            }

            $startStr = $current->format('H:i');
            $endStr = $end->format('H:i');

            $isBooked = $bookedSlots->contains(function ($booking) use ($startStr, $endStr) {
                $bookedStart = Carbon::parse($booking->start_time)->format('H:i');
                $bookedEnd = Carbon::parse($booking->end_time)->format('H:i');

                return $startStr < $bookedEnd && $endStr > $bookedStart;
            });

            $isPast = $date->isToday() && $current->lt(Carbon::now());

            $period = (int) $current->format('H') >= self::NIGHT_START_HOUR ? 'night' : 'day';
            $slotPrice = $turf->slotPrice($date, $period, self::SLOT_MINUTES);

            $slots[] = [
                'start' => $startStr,
                'end' => $endStr,
                'label' => $current->format('g:i A').' - '.$end->format('g:i A'),
                'price' => $slotPrice,
                'available' => ! $isBooked && ! $isPast,
                'period' => $period,
            ];

            $current->addMinutes(self::SLOT_MINUTES);
        }

        return $slots;
    }
}
