<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'turf_id',
        'booking_date',
        'start_time',
        'end_time',
        'total_price',
        'status',
        'customer_name',
        'customer_phone',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'total_price' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function turf(): BelongsTo
    {
        return $this->belongsTo(Turf::class);
    }
}
