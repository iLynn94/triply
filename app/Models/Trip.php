<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'destination',
        'organizer_id',
        'description',
        'hotel_name',
        'type',
        'itinerary',
        'cover_image_url',
        'gallery',
        'highlights',
        'duration_days',
        'inclusions',
        'exclusions',
        'base_price_per_person',
        'child_discount_percent',
        'transport_options',
        'notes',
        'availability',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'itinerary' => 'array',
        'gallery' => 'array',
        'highlights' => 'array',
        'inclusions' => 'array',
        'exclusions' => 'array',
        'transport_options' => 'array',
        'availability' => 'array',
    ];

    /**
     * Relationships
     */
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Helper methods
     */

    // Check if a given date is available
    public function isAvailableOn($date)
    {
        if (empty($this->availability)) {
            return true; // if no availability set, assume always available
        }

        $date = is_string($date) ? \Carbon\Carbon::parse($date) : $date;

        foreach ($this->availability as $range) {
            $from = \Carbon\Carbon::parse($range['from']);
            $to = \Carbon\Carbon::parse($range['to']);
            if ($date->between($from, $to)) {
                return true;
            }
        }

        return false;
    }

    // Get transport price for a selected option
    public function getTransportPrice($option)
    {
        if (empty($this->transport_options)) {
            return 0;
        }

        $optionData = $this->transport_options[$option] ?? null;

        if (!$optionData) {
            return 0;
        }

        return $optionData['price'] ?? 0;
    }

    // Calculate price for a booking
    public function calculateBookingPrice($adults = 1, $children = 0, $transportOption = null)
    {
        $baseTotal = $this->base_price_per_person * $adults;

        $childrenTotal = $this->base_price_per_person * $children * (1 - $this->child_discount_percent / 100);

        $transportTotal = $this->getTransportPrice($transportOption);

        return $baseTotal + $childrenTotal + $transportTotal;
    }

    // Ratings for this trip
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    // Users who have this trip in wishlist
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    // Check if trip is in user's wishlist
    public function isInWishlist($userId = null)
    {
        $userId = $userId ?? auth()->id();

        if (!$userId) {
            return false;
        }

        return $this->wishlists()->where('user_id', $userId)->exists();
    }

    // Calendar reminders
    public function calendarReminders()
    {
        return $this->hasMany(CalendarReminder::class);
    }
}
