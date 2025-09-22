<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TripCancellation extends Model
{
    protected $fillable = [
        'trip_name',
        'trip_description',
        'trip_date',
        'trip_price',
        'min_participants',
        'max_participants',
        'cancellation_type',
        'cancellation_conditions',
        'refund_policy',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'trip_date' => 'date',
            'trip_price' => 'decimal:2',
            'cancellation_conditions' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user who created this trip
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get formatted trip price
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->trip_price, 2) . ' บาท';
    }

    /**
     * Get formatted trip date
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->trip_date->format('d/m/Y');
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'active' => 'badge-success',
            'cancelled' => 'badge-error',
            'completed' => 'badge-info',
            default => 'badge-neutral'
        };
    }

    /**
     * Get cancellation type badge class
     */
    public function getCancellationTypeBadgeClassAttribute(): string
    {
        return match($this->cancellation_type) {
            'automatic' => 'badge-warning',
            'manual' => 'badge-primary',
            default => 'badge-neutral'
        };
    }

    /**
     * Get participants range
     */
    public function getParticipantsRangeAttribute(): string
    {
        if ($this->max_participants) {
            return "{$this->min_participants} - {$this->max_participants} คน";
        }
        return "ขั้นต่ำ {$this->min_participants} คน";
    }

    /**
     * Check if trip can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return $this->status === 'active' && $this->trip_date > now();
    }

    /**
     * Get default cancellation conditions
     */
    public static function getDefaultCancellationConditions(): array
    {
        return [
            [
                'days_before' => 30,
                'refund_percentage' => 100,
                'description' => 'ยกเลิกก่อน 30 วัน - คืนเงิน 100%'
            ],
            [
                'days_before' => 15,
                'refund_percentage' => 80,
                'description' => 'ยกเลิกก่อน 15 วัน - คืนเงิน 80%'
            ],
            [
                'days_before' => 7,
                'refund_percentage' => 50,
                'description' => 'ยกเลิกก่อน 7 วัน - คืนเงิน 50%'
            ],
            [
                'days_before' => 3,
                'refund_percentage' => 25,
                'description' => 'ยกเลิกก่อน 3 วัน - คืนเงิน 25%'
            ],
            [
                'days_before' => 0,
                'refund_percentage' => 0,
                'description' => 'ยกเลิกในวันเดินทาง - ไม่คืนเงิน'
            ]
        ];
    }
}
