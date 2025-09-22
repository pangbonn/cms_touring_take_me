<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Booking extends Model
{
    protected $fillable = [
        'booking_id',
        'trip_id',
        'trip_schedule_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_line_id',
        'guests',
        'notes',
        'total_price',
        'booking_date',
        'status',
        'source'
    ];

    protected $casts = [
        'booking_date' => 'datetime',
        'total_price' => 'decimal:2',
        'guests' => 'integer',
    ];

    /**
     * Get the trip that owns the booking.
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Get the trip schedule that owns the booking.
     */
    public function tripSchedule(): BelongsTo
    {
        return $this->belongsTo(TripSchedule::class);
    }

    /**
     * Generate unique booking ID
     */
    public static function generateBookingId(): string
    {
        return 'BK' . time() . rand(1000, 9999);
    }

    /**
     * Get formatted booking date in Thai
     */
    public function getBookingDateThaiAttribute(): string
    {
        return Carbon::parse($this->booking_date)->locale('th')->isoFormat('DD MMMM YYYY');
    }
}
