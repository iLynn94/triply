<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('destination');
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->text('description');
            $table->string('hotel_name');
            $table->string('type');
            $table->json('itinerary')->nullable(); // {"Day 1": ["Visit Museum","Dinner"], ...}
            $table->text('cover_image_url');
            $table->json('gallery')->nullable(); // other image urls ["url1", "url2", ...]
            $table->json('highlights')->nullable(); // ["Beach", "Hiking", ...]
            $table->integer('duration_days')->default(1);     
            $table->json('inclusions');    // ["Meals", "Hotel", ...]
            $table->json('exclusions')->nullable();    // ["Flights", "Personal Expenses", ...]       
            $table->decimal('base_price_per_person', 10, 2); // includes hotel
            $table->decimal('child_discount_percent', 5, 2)->default(0); // % discount for children
            $table->json('transport_options')->nullable(); // { "SGR": {"from": "Nairobi", "price": 20.0}, "Plane": {"from": "Mombasa", "price": 100.0}, "Airport Pickup": {"price": 30.0} }
            $table->text('notes')->nullable(); 
            $table->json('availability')->nullable(); // [{"from": "2025-12-01", "to": "2025-12-05"}, {"from": "2025-12-10", "to": "2025-12-15"} ]
            $table->timestamps();

            $table->index('destination');
            $table->index('hotel_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
