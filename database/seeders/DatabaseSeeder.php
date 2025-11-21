<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        $this->call([
            UserSeeder::class,
            TripSeeder::class,
            BookingSeeder::class,
            RatingSeeder::class,
            WishlistSeeder::class,
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
