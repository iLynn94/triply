<?php
// app/View/Components/TripCard.php

namespace App\View\Components;

use App\Models\Trip;
use Illuminate\View\Component;

class TripCard extends Component
{
    public Trip $trip;

    /**
     * Create a new component instance.
     */
    public function __construct(Trip $trip)
    {
        $this->trip = $trip;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.trip-card');
    }
}