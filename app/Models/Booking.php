<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'user_id',
        'trip_id',
        'number_of_adults',
        'number_of_children',
        'selected_transport',
        'total_fee',
        'status',
        'payment_status',
        'start_date',
        'end_date',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_fee' => 'decimal:2',
    ];

    /**
     * Relationships
     */

    // The trip this booking belongs to
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    // The user who made the booking
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper methods
     */

    // Calculate total fee dynamically
    public function calculateTotalFee()
    {
        $trip = $this->trip;

        if (!$trip) {
            return 0;
        }

        $adults = $this->number_of_adults ?? 0;
        $children = $this->number_of_children ?? 0;
        $transportOption = $this->selected_transport;

        return $trip->calculateBookingPrice($adults, $children, $transportOption);
    }

    // Check if booking dates are within trip availability
    public function isWithinAvailability()
    {
        $trip = $this->trip;

        if (!$trip || empty($trip->availability)) {
            return true;
        }

        foreach ($trip->availability as $range) {
            $rangeFrom = \Carbon\Carbon::parse($range['from']);
            $rangeTo = \Carbon\Carbon::parse($range['to']);

            $start = $this->start_date;
            $end = $this->end_date ?? $this->start_date;

            if ($start->between($rangeFrom, $rangeTo) && $end->between($rangeFrom, $rangeTo)) {
                return true;
            }
        }

        return false;
    }
}
