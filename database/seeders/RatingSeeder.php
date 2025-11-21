<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rating;

class RatingSeeder extends Seeder
{
    public function run(): void
    {
        Rating::truncate();

        Rating::create([
            'user_id' => 2,
            'trip_id' => 1,
            'rating' => 5,
            'comment' => 'Amazing trip! Highly recommended.',
        ]);

        Rating::create([
            'user_id' => 3,
            'trip_id' => 2,
            'rating' => 4,
            'comment' => 'Great safari experience!',
        ]);
    }
}
