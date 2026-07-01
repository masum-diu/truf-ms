<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Turf extends Model
{
    protected $fillable = [
        'owner_id',
        'area_id',
        'name',
        'slug',
        'address',
        'description',
        'price_per_hour',
        'day_price',
        'night_price',
        'offday_price',
        'surface_type',
        'size',
        'amenities',
        'image',
        'open_time',
        'close_time',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'amenities' => 'array',
            'is_active' => 'boolean',
            'price_per_hour' => 'integer',
            'day_price' => 'integer',
            'night_price' => 'integer',
            'offday_price' => 'integer',
        ];
    }

    public function isOffDay(Carbon $date): bool
    {
        return in_array($date->dayOfWeek, [Carbon::FRIDAY, Carbon::SATURDAY], true);
    }

    public function resolveHourlyPrice(Carbon $date, string $period): int
    {
        if ($this->isOffDay($date)) {
            return $this->offday_price;
        }

        return $period === 'night' ? $this->night_price : $this->day_price;
    }

    public function slotPrice(Carbon $date, string $period, int $minutes = 90): int
    {
        return (int) round($this->resolveHourlyPrice($date, $period) * ($minutes / 60));
    }

    public function lowestHourlyPrice(): int
    {
        return min($this->day_price, $this->night_price, $this->offday_price);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
