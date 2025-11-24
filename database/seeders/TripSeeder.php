<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trip;

class TripSeeder extends Seeder
{
    public function run(): void
    {
        Trip::truncate();

        $trips = [
            [
                'title' => '6 Days Zanzibar Beach Holiday Experience',
                'destination' => 'Zanzibar, Tanzania',
                'organizer_id' => 1, // admin
                'status' => 'open',
                'type' => 'Beach',
                'description' => 'Zanzibar is a beautiful archipelago known for white sandy beaches, rich history, Stone Town, spice tours, marine life, and cultural diversity.',
                'hotel_name' => 'Zanzibar Beach Resort',
                'cover_image_url' => 'https://images.unsplash.com/photo-1590523741831-ab7e8b8f9c7f?w=1200',
                'gallery' => [
                    "https://images.unsplash.com/photo-1583417319070-4a69db38a482?w=1200",
                    "https://images.unsplash.com/photo-1505881502353-a1986add3762?w=1200",
                    "https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=1200"
                ],
                'highlights' => [
                    "Stone Town Tour", "Spice Tour", "Swimming with Turtles",
                    "Safari Blue", "Sunset Cruise"
                ],
                'duration_days' => 6,
                'itinerary' => [
                    "Day 1" => ["Arrival & Hotel Check-in", "Relax at the Beach"],
                    "Day 2" => ["Stone Town Tour", "Spice Market Experience"],
                    "Day 3" => ["Safari Blue Excursion", "Dolphin Watching"],
                    "Day 4" => ["Snorkeling Adventure", "Turtle Sanctuary Visit"],
                    "Day 5" => ["Beach Day", "Sunset Cruise"],
                    "Day 6" => ["Checkout & Departure"]
                ],
                'inclusions' => [
                    "5 Nights Accommodation",
                    "Meals As Per Hotel",
                    "Return Hotel Transfers",
                    "Return Flights via Safari Link",
                    "Travel Insurance",
                    "Safari Blue Excursion",
                    "Stone Town Tour",
                    "Spice Tour"
                ],
                'exclusions' => [
                    "Lunch On Final Day",
                    "Yellow Fever Certificate",
                    "Tips & Extra Activities",
                    "Passport Fees",
                    "Entry Fees To Select Sites"
                ],
                'base_price_per_person' => 170500,
                'child_discount_percent' => 20,
                'transport_options' => [
                    "Plane" => ["from" => "Nairobi", "price" => 25000],
                    "Airport Pickup" => ["price" => 3000]
                ],
                'notes' => "Carry sunscreen, swimwear, passport, sunglasses, hat and EA pass.",
            ],

            [
                'title' => '3 Days Masai Mara Safari Adventure',
                'destination' => 'Masai Mara, Kenya',
                'organizer_id' => 1,
                'type' => 'Safari',
                'description' => 'Explore the world-famous Masai Mara with wildlife drives, Maasai cultural visits, and breathtaking landscapes.',
                'hotel_name' => 'Mara Serena Safari Lodge',
                'cover_image_url' => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=1200',
                'gallery' => [
                    "https://images.unsplash.com/photo-1707410436272-1bcc71ecafb3?w=1200",
                    "https://images.unsplash.com/photo-1549366021-9f761d450615?w=1200",
                    "https://images.unsplash.com/photo-1580145575237-75fec2a0320b?w=1200"
                ],
                'highlights' => [
                    "Game Drives", "Big 5", "Maasai Village Visit"
                ],
                'duration_days' => 3,
                'itinerary' => [
                    "Day 1" => ["Drive from Nairobi", "Afternoon Game Drive"],
                    "Day 2" => ["Full Day Game Drive", "Picnic Lunch"],
                    "Day 3" => ["Morning Drive", "Return to Nairobi"]
                ],
                'inclusions' => ["Meals", "Park Fees", "Accommodation"],
                'exclusions' => ["Drinks", "Tips"],
                'base_price_per_person' => 45000,
                'child_discount_percent' => 10,
                'transport_options' => [
                    "Road" => ["from" => "Nairobi", "price" => 50],
                    "Plane" => ["from" => "Wilson Airport", "price" => 150]
                ],
                'notes' => "Best time to visit is July–October (Migration season)."
            ],

            [
                'title' => '4 Days Diani Beach Relaxation Retreat',
                'destination' => 'Diani Beach, Kenya',
                'organizer_id' => 1,
                'status' => 'open',
                'type' => 'Beach',
                'description' => 'Experience soft white sands, crystal clear waters, and water sports at one of Africa’s top beaches.',
                'hotel_name' => 'Swahili Beach Resort',
                'cover_image_url' => 'https://images.unsplash.com/photo-1505142468610-359e7d316be0?w=1200',
                'gallery' => [
                    "https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200",
                    "https://images.unsplash.com/photo-1519046904884-53103b34b206?w=1200",
                    "https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=1200"
                ],
                'highlights' => ["Jet Skiing", "Sky Diving", "Snorkeling"],
                'duration_days' => 4,
                'itinerary' => [
                    "Day 1" => ["Arrival & Beach Relaxation"],
                    "Day 2" => ["Snorkeling & Water Sports"],
                    "Day 3" => ["Local Excursions"],
                    "Day 4" => ["Departure"]
                ],
                'inclusions' => ["Breakfast", "Accommodation", "Transfers"],
                'exclusions' => ["Flights", "Personal Costs"],
                'base_price_per_person' => 85000,
                'child_discount_percent' => 15,
                'transport_options' => [
                    "SGR" => ["from" => "Nairobi", "price" => 25],
                    "Plane" => ["from" => "Wilson Airport", "price" => 120]
                ],
                'notes' => "Perfect for couples and families."
            ],

            [
                'title' => '5 Days Mt Kenya Hiking Expedition',
                'destination' => 'Mt Kenya National Park',
                'organizer_id' => 1,
                'status' => 'open',
                'type' => 'Hiking',
                'description' => 'Climb Africa’s second-highest mountain through scenic routes and experience alpine ecosystems.',
                'hotel_name' => 'Old Moses Camp',
                'cover_image_url' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=1200',
                'gallery' => [
                    "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200",
                    "https://images.unsplash.com/photo-1551632811-561732d1e306?w=1200",
                    "https://images.unsplash.com/photo-1486870591958-9b9d0d1dda99?w=1200"
                ],
                'highlights' => ["Summit Attempt", "Forest Trails", "Waterfalls"],
                'duration_days' => 5,
                'itinerary' => [
                    "Day 1" => ["Nairobi → Nanyuki", "Briefing"],
                    "Day 2" => ["Hike to Old Moses"],
                    "Day 3" => ["Hike to Shiptons"],
                    "Day 4" => ["Summit Attempt"],
                    "Day 5" => ["Descend & Return"]
                ],
                'inclusions' => ["Guide", "Meals", "Park Fees"],
                'exclusions' => ["Gear Rental"],
                'base_price_per_person' => 60000,
                'child_discount_percent' => 0,
                'transport_options' => [
                    "Road" => ["from" => "Nairobi", "price" => 40]
                ],
                'notes' => "Intermediate hiking fitness required."
            ],

            [
                'title' => '2 Days Nairobi City Cultural Tour',
                'destination' => 'Nairobi, Kenya',
                'organizer_id' => 1,
                'status' => 'open',
                'type' => 'Cultural',
                'description' => 'Perfect for tourists wanting to explore Nairobi’s culture, museums, wildlife & food scene.',
                'hotel_name' => 'Sarova Stanley',
                'cover_image_url' => 'https://images.unsplash.com/photo-1611348524140-53c9a25263d6?w=1200',
                'gallery' => [
                    "https://images.unsplash.com/photo-1523805009345-7448845a9e53?w=1200",
                    "https://images.unsplash.com/photo-1669127300649-940337f1487e?w=1200",
                    "https://images.unsplash.com/photo-1635595358293-03620e36be48?w=1200"
                ],
                'highlights' => ["Museum", "Giraffe Centre", "Karen Blixen Museum"],
                'duration_days' => 2,
                'itinerary' => [
                    "Day 1" => ["City Tour", "Museum Visit"],
                    "Day 2" => ["Giraffe Centre", "Dinner in Karen"]
                ],
                'inclusions' => ["Transport", "Entry Fees"],
                'exclusions' => ["Meals"],
                'base_price_per_person' => 18000,
                'child_discount_percent' => 10,
                'transport_options' => [
                    "Road" => ["from" => "Nairobi", "price" => 1000]
                ],
                'notes' => "Good quick trip for first-time visitors."
            ],

            [
                'title' => '5 Days Luxury Maldives Escape',
                'destination' => 'Maldives',
                'organizer_id' => 1,
                'status' => 'open',
                'type' => 'Luxury',
                'description' => 'Experience turquoise waters and private villas in the world’s most luxurious holiday destination.',
                'hotel_name' => 'Coco Palm Resort',
                'cover_image_url' => 'https://images.unsplash.com/photo-1573843981267-be1999ff37cd?w=1200',
                'gallery' => [
                    "https://images.unsplash.com/photo-1514282401047-d79a71a590e8?w=1200",
                    "https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200",
                    "https://images.unsplash.com/photo-1540202404-d0c7fe46a087?w=1200"
                ],
                'highlights' => ["Overwater Villas", "Spa", "Dolphin Cruise"],
                'duration_days' => 5,
                'itinerary' => [
                    "Day 1" => ["Arrival & Resort Check-in"],
                    "Day 2" => ["Spa + Beach"],
                    "Day 3" => ["Water Sports"],
                    "Day 4" => ["Island Cruise"],
                    "Day 5" => ["Departure"]
                ],
                'inclusions' => ["Flights", "Meals", "Accommodation"],
                'exclusions' => ["Alcohol"],
                'base_price_per_person' => 350000,
                'child_discount_percent' => 15,
                'transport_options' => [
                    "Plane" => ["from" => "Nairobi", "price" => 90000]
                ],
                'notes' => "Perfect for honeymoons."
            ],
        ];

        foreach ($trips as $trip) {
            Trip::create($trip);
        }
    }
}

