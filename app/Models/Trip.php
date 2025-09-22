<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'cover_image',
        'sample_images',
        'itinerary',
        'total_cost',
        'personal_items',
        'area_info',
        'rental_equipment',
        'show_itinerary',
        'show_total_cost',
        'show_personal_items',
        'show_rental_equipment',
        'show_schedule',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_itinerary' => 'boolean',
        'show_total_cost' => 'boolean',
        'show_personal_items' => 'boolean',
        'show_rental_equipment' => 'boolean',
        'show_schedule' => 'boolean',
        'sample_images' => 'array',
    ];

    /**
     * Get the trip schedules for the trip.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(TripSchedule::class);
    }

    /**
     * Get the bookings for the trip.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
