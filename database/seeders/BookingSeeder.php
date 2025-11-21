<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        Booking::truncate();

        Booking::create([
            'user_id' => 2,
            'trip_id' => 1,
            'number_of_adults' => 2,
            'number_of_children' => 1,
            'selected_transport' => 'Plane',
            'total_fee' => 1705 * 2 + (1705 * 0.8) + 250,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'start_date' => '2025-12-01',
            'end_date' => '2025-12-06',
        ]);

        Booking::create([
            'user_id' => 3,
            'trip_id' => 2,
            'number_of_adults' => 1,
            'number_of_children' => 0,
            'selected_transport' => 'Road',
            'total_fee' => 450 + 50,
            'status' => 'pending',
            'payment_status' => 'pending',
            'start_date' => '2025-07-10',
            'end_date' => '2025-07-13',
        ]);
    }
}

