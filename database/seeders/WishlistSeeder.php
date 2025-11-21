<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wishlist;

class WishlistSeeder extends Seeder
{
    public function run(): void
    {
        Wishlist::truncate();

        Wishlist::create([
            'user_id' => 2,
            'trip_id' => 3,
        ]);

        Wishlist::create([
            'user_id' => 3,
            'trip_id' => 1,
        ]);
    }
}
