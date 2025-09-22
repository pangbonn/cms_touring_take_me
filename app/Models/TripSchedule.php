<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class TripSchedule extends Model
{
    protected $fillable = [
        'trip_id',
        'departure_date',
        'return_date',
        'max_participants',
        'price',
        'is_active'
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'max_participants' => 'integer',
    ];

    /**
     * Get the trip that owns the schedule.
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Get formatted departure date in Thai
     */
    public function getDepartureDateThaiAttribute(): string
    {
        return Carbon::parse($this->departure_date)->locale('th')->isoFormat('DD MMMM YYYY');
    }

    /**
     * Get formatted return date in Thai
     */
    public function getReturnDateThaiAttribute(): string
    {
        if (!$this->return_date) {
            return '';
        }
        return Carbon::parse($this->return_date)->locale('th')->isoFormat('DD MMMM YYYY');
    }

    /**
     * Get duration in Thai format
     */
    public function getDurationAttribute(): string
    {
        if (!$this->return_date) {
            return '1 วัน';
        }

        $departure = Carbon::parse($this->departure_date);
        $return = Carbon::parse($this->return_date);
        $days = $departure->diffInDays($return) + 1;

        if ($days == 1) {
            return '1 วัน';
        } else {
            return $days . ' วัน ' . ($days - 1) . ' คืน';
        }
    }
}
