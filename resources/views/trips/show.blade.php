@extends('layouts.main')

@section('title', $trip->title)

@section('content')
<div class="container mx-auto px-4 py-8 md:px-8 lg:px-16 xl:px-32">
    
    {{-- Header Section --}}
    <div class="mb-6">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">{{ $trip->title }}</h1>
                <div class="flex flex-wrap items-center gap-4 text-gray-600">
                    <div class="flex items-center gap-1">
                        <x-heroicon-o-map-pin class="w-5 h-5 text-orange-600" />
                        <span>{{ $trip->destination }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <x-heroicon-o-tag class="w-5 h-5 text-orange-600" />
                        <span>{{ $trip->type }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <x-heroicon-o-calendar class="w-5 h-5 text-orange-600" />
                        <span>{{ $trip->duration_days }} {{ Str::plural('Day', $trip->duration_days) }}</span>
                    </div>
                </div>
            </div>

            {{-- Action Buttons (Wishlist/Edit) --}}
            <div class="ml-4 flex gap-2">
                @auth
                    {{-- Edit Button - Only for Trip Creator --}}
                    @if(auth()->id() === $trip->organizer_id)
                     <a href="{{ route('trips.edit', $trip) }}"
                        class="inline-flex items-center gap-2 px-3 py-1 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                        <x-heroicon-o-pencil class="w-5 h-5" />
                        <span class="hidden sm:inline">Edit Trip</span>
                     </a>
                    @endif

                    {{-- Wishlist Button - For All Logged In Users --}}
                    <livewire:wishlist-button :trip="$trip" variant="large" :key="'wishlist-show-'.$trip->id" />
                @endauth
            </div>
        </div>

        {{-- Price --}}
        <div class="mb-4">
            <p class="text-sm text-gray-500">From</p>
            <p class="text-3xl font-bold text-orange-600">Ksh {{ number_format($trip->base_price_per_person, 0) }}</p>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Left Column - Images and Details (Takes 2 columns) --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Image Gallery --}}
            <div>
                {{-- Main Image --}}
                <div class="relative w-full h-96 md:h-[500px] rounded-lg overflow-hidden mb-3">
                    <img 
                        id="mainImage" 
                        src="{{ $trip->cover_image_url }}" 
                        alt="{{ $trip->title }}"
                        class="w-full h-full object-cover"
                    >
                    <div class="absolute top-4 right-4 px-3 py-1.5 bg-white/90 backdrop-blur-sm rounded-full text-sm font-medium flex items-center gap-2">
                        <x-heroicon-o-photo class="w-4 h-4" />
                        <span>GALLERY</span>
                    </div>
                </div>

                {{-- Thumbnail Gallery --}}
                @php
                    $allImages = array_merge([$trip->cover_image_url], $trip->gallery ?? []);
                @endphp
                
                @if(count($allImages) > 1)
                <div class="grid grid-cols-4 md:grid-cols-5 gap-2">
                    @foreach($allImages as $index => $image)
                    <button 
                        onclick="changeMainImage('{{ $image }}', this)"
                        class="thumbnail-btn relative w-full h-20 md:h-24 rounded-lg overflow-hidden border-2 transition-all {{ $index === 0 ? 'border-orange-600' : 'border-gray-200 hover:border-orange-400' }}"
                    >
                        <img src="{{ $image }}" alt="Gallery image {{ $index + 1 }}" class="w-full h-full object-cover">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Tabs Section - Default Sheaf UI Styling --}}
            <x-ui.tabs>
                <x-ui.tab.group>
                    <x-ui.tab name="overview" label="Overview" />
                    <x-ui.tab name="rates" label="Rates" />
                    <x-ui.tab name="tour-plan" label="Itenary" />
                    <x-ui.tab name="inclusions" label="Inclusions" />
                </x-ui.tab.group>

                {{-- Overview Tab --}}
                <x-ui.tab.panel name="overview">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-xl font-semibold mb-3 text-gray-900">Description</h3>
                            <p class="text-gray-700 leading-relaxed">{{ $trip->description }}</p>
                        </div>

                        @if($trip->highlights && count($trip->highlights) > 0)
                        <div>
                            <h3 class="text-xl font-semibold mb-3 text-gray-900">Highlights</h3>
                            <ul class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($trip->highlights as $highlight)
                                <li class="flex items-start gap-2">
                                    <x-heroicon-o-check-circle class="w-5 h-5 text-orange-600 mt-0.5 shrink-0" />
                                    <span class="text-gray-700">{{ $highlight }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t">
                            <div>
                                <p class="text-sm text-gray-500">Hotel</p>
                                <p class="font-semibold text-gray-900">{{ $trip->hotel_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Duration</p>
                                <p class="font-semibold text-gray-900">{{ $trip->duration_days }} {{ Str::plural('Day', $trip->duration_days) }}</p>
                            </div>
                        </div>

                        @if($trip->notes)
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                            <h4 class="font-semibold text-orange-900 mb-2">Important Notes</h4>
                            <p class="text-orange-800 text-sm">{{ $trip->notes }}</p>
                        </div>
                        @endif
                    </div>
                </x-ui.tab.panel>

                {{-- Rates Tab --}}
                <x-ui.tab.panel name="rates">
                    <div class="space-y-4">
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-gray-700 font-medium">Adult (Base Price)</span>
                                <span class="text-2xl font-bold text-orange-600">Ksh {{ number_format($trip->base_price_per_person, 2) }}</span>
                            </div>
                            <p class="text-sm text-gray-600">Per person</p>
                        </div>

                        @if($trip->child_discount_percent > 0)
                        <div class="bg-green-50 rounded-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-gray-700 font-medium">Children Discount</span>
                                <span class="text-2xl font-bold text-green-600">{{ $trip->child_discount_percent }}% OFF</span>
                            </div>
                            <p class="text-sm text-gray-600">
                                Children pay Ksh {{ number_format($trip->base_price_per_person * (1 - $trip->child_discount_percent / 100), 2) }}
                            </p>
                        </div>
                        @endif

                        @if($trip->transport_options && count($trip->transport_options) > 0)
                        <div>
                            <h3 class="text-lg font-semibold mb-3 text-gray-900">Transport Options</h3>
                            <div class="space-y-2">
                                @foreach($trip->transport_options as $name => $details)
                                <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $name }}</p>
                                        @if(isset($details['from']))
                                        <p class="text-sm text-gray-600">From {{ $details['from'] }}</p>
                                        @endif
                                    </div>
                                    <span class="font-bold text-orange-600">+Ksh {{ number_format($details['price'], 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </x-ui.tab.panel>

                {{-- Tour Plan Tab --}}
                <x-ui.tab.panel name="tour-plan">
                    @if($trip->itinerary && count($trip->itinerary) > 0)
                    <div class="space-y-4">
                        @foreach($trip->itinerary as $day => $activities)
                        <div class="border-l-4 border-orange-600 pl-4 py-2">
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $day }}</h4>
                            <ul class="space-y-1">
                                @foreach($activities as $activity)
                                <li class="inline-flex items-start gap-2 text-gray-700">
                                    <x-heroicon-m-check-circle class="w-4 h-4 text-orange-600 mt-0.5 shrink-0" />
                                    <span>{{ $activity }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-600">Detailed itinerary coming soon...</p>
                    @endif
                </x-ui.tab.panel>

                {{-- Inclusions Tab --}}
                <x-ui.tab.panel name="inclusions">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Inclusions --}}
                        @if($trip->inclusions && count($trip->inclusions) > 0)
                        <div>
                            <h3 class="text-lg font-semibold mb-3 text-green-700 flex items-center gap-2">
                                <x-heroicon-o-check-circle class="w-6 h-6" />
                                Included
                            </h3>
                            <ul class="space-y-2">
                                @foreach($trip->inclusions as $inclusion)
                                <li class="flex items-start gap-2 text-gray-700">
                                    <x-heroicon-o-check class="w-5 h-5 text-green-600 mt-0.5 shrink-0" />
                                    <span>{{ $inclusion }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- Exclusions --}}
                        @if($trip->exclusions && count($trip->exclusions) > 0)
                        <div>
                            <h3 class="text-lg font-semibold mb-3 text-red-700 flex items-center gap-2">
                                <x-heroicon-o-x-circle class="w-6 h-6" />
                                Not Included
                            </h3>
                            <ul class="space-y-2">
                                @foreach($trip->exclusions as $exclusion)
                                <li class="flex items-start gap-2 text-gray-700">
                                    <x-heroicon-o-x-mark class="w-5 h-5 text-red-600 mt-0.5 shrink-0" />
                                    <span>{{ $exclusion }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </x-ui.tab.panel>
            </x-ui.tabs>
        </div>

        {{-- Right Column - Booking Form (Takes 1 column) --}}
        <div class="lg:col-span-1">
            <div class="sticky top-24">
                <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Book This Trip</h3>

                    @auth
                        {{-- Booking Form --}}
                        <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm" class="space-y-4">
                            @csrf
                            <input type="hidden" name="trip_id" value="{{ $trip->id }}">

                            {{-- Adults --}}
                            <div>
                                <label for="adults" class="block text-sm font-medium text-gray-700 mb-1">Adults *</label>
                                <input
                                    type="number"
                                    id="adults"
                                    name="number_of_adults"
                                    min="1"
                                    value="1"
                                    required
                                    onchange="calculatePrice()"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-600 focus:border-transparent"
                                />
                            </div>

                            {{-- Children --}}
                           <div>
                                <label for="children" class="block text-sm font-medium text-gray-700 mb-1">
                                    Children
                                    <small class="text-gray-500">(13 years and below *)</small>
                                </label>

                                <input
                                    type="number"
                                    id="children"
                                    name="number_of_children"
                                    min="0"
                                    value="0"
                                    onchange="calculatePrice()"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-600 focus:border-transparent"
                                />
                            </div>

                            {{-- Travel Date --}}
                            <div>
                                <label for="travel_date" class="block text-sm font-medium text-gray-700 mb-1">Travel Date *</label>
                                <input
                                    type="date"
                                    id="travel_date"
                                    name="start_date"
                                    required
                                    class="flatpickr w-full px-3 py-2 border rounded-lg @error('start_date') border-red-500 @enderror"
                                    placeholder="Select date"
                                >
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Transport Options --}}
                            @if($trip->transport_options && count($trip->transport_options) > 0)
                            <div>
                                <label for="transport" class="block text-sm font-medium text-gray-700 mb-1">Transport Option</label>
                                <select
                                    id="transport"
                                    name="selected_transport"
                                    onchange="calculatePrice()"
                                    class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all appearance-none cursor-pointer hover:border-gray-400"
                                    style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 20 20%22%3E%3Cpath stroke=%22%236b7280%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22 stroke-width=%221.5%22 d=%22M6 8l4 4 4-4%22/%3E%3C/svg%3E'); background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1.5em 1.5em; padding-right: 2.5rem;"
                                >
                                    <option value="">No transport</option>
                                    @foreach($trip->transport_options as $name => $details)
                                    <option value="{{ $name }}" data-price="{{ $details['price'] }}">
                                        {{ $name }} (+ Ksh {{ number_format($details['price'], 2) }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            {{-- Notes --}}
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Special Requests (Optional)</label>
                                <textarea
                                    id="notes"
                                    name="notes"
                                    rows="3"
                                    placeholder="Any special requirements..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-600 focus:border-transparent resize-none"
                                ></textarea>
                            </div>

                            {{-- Price Estimate --}}
                            <div class="bg-orange-50 border-2 border-orange-600 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-gray-700 font-medium">Estimated Total</span>
                                    <span id="estimatedPrice" class="text-2xl font-bold text-orange-600">
                                        Ksh {{ number_format($trip->base_price_per_person, 0) }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600">Final price may vary</p>
                            </div>

                            {{-- Submit Button --}}
                            <button 
                                type="submit" 
                                class="w-full py-3 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2"
                            >
                                Submit Booking
                            </button>
                        </form>
                    @else
                        {{-- Guest Message --}}
                        <div class="text-center py-6">
                            <x-heroicon-o-user-circle class="w-16 h-16 text-gray-400 mx-auto mb-3" />
                            <h4 class="text-base font-semibold text-gray-900 mb-2">Login Required</h4>
                            <p class="text-sm text-gray-600 mb-6">Please register or log in to book this trip</p>
                            <div class="flex flex-col gap-3">
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-medium text-sm">
                                    <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5" />
                                    Sign In
                                </a>
                                <a href="{{ route('sign-up') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white text-orange-600 border-2 border-orange-600 rounded-lg hover:bg-orange-50 transition-colors font-medium text-sm">
                                    <x-heroicon-o-user-plus class="w-5 h-5" />
                                    Sign Up
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Reviews Section --}}
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">Reviews</h2>

        @if($trip->ratings && $trip->ratings->count() > 0)
            <div class="space-y-6">
                @foreach($trip->ratings as $rating)
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-start gap-4">
                            {{-- User Photo --}}
                            <div class="flex-shrink-0">
                                @if($rating->user->profile_image_url)
                                    <img src="{{ $rating->user->profile_image_url }}" alt="{{ $rating->user->first_name }}" class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center">
                                        <span class="text-orange-600 font-semibold text-lg">
                                            {{ strtoupper(substr($rating->user->first_name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Review Content --}}
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $rating->user->first_name }} {{ $rating->user->last_name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $rating->created_at->format('M d, Y') }}</p>
                                    </div>

                                    {{-- Star Rating --}}
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 lg:w-6 lg:h-6 {{ $i <= $rating->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>

                                {{-- Comment --}}
                                @if($rating->comment)
                                    <p class="text-gray-700 leading-relaxed">{{ $rating->comment }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Reviews Yet</h3>
                <p class="text-gray-600">Be the first to review this trip!</p>
            </div>
        @endif
    </div>
</div>

{{-- JavaScript for Image Gallery and Price Calculation --}}
<script>
    // Change main image and update active thumbnail
    function changeMainImage(imageUrl, clickedButton) {
        document.getElementById('mainImage').src = imageUrl;

        // Remove active class from all thumbnails
        document.querySelectorAll('.thumbnail-btn').forEach(btn => {
            btn.classList.remove('border-orange-600');
            btn.classList.add('border-gray-200');
        });

        // Add active class to clicked thumbnail
        clickedButton.classList.remove('border-gray-200');
        clickedButton.classList.add('border-orange-600');
    }

    // Calculate price dynamically
    function calculatePrice() {
        const adults = parseInt(document.getElementById('adults')?.value || 0);
        const children = parseInt(document.getElementById('children')?.value || 0);
        const transportSelect = document.getElementById('transport');
        const transportPrice = transportSelect ? parseFloat(transportSelect.options[transportSelect.selectedIndex]?.dataset.price || 0) : 0;

        const basePrice = {{ $trip->base_price_per_person }};
        const childDiscount = {{ $trip->child_discount_percent }};

        const adultsTotal = adults * basePrice;
        const childrenTotal = children * basePrice * (1 - childDiscount / 100);
        const total = adultsTotal + childrenTotal + transportPrice;

        document.getElementById('estimatedPrice').textContent = `Ksh ${total.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`;
    }

    // Initialize flatpickr and price calculation on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize flatpickr
        if (window.flatpickr) {
            window.flatpickr("#travel_date", {
                minDate: "today",
                dateFormat: "j F Y",
                theme: "material_orange"
            });
        }

        // Initialize price calculation
        calculatePrice();
    });
</script>

{{-- Custom CSS to make active tabs orange --}}
<style>
    /* Override Sheaf UI tab active color to orange */
    [role="tab"][aria-selected="true"],
    [role="tab"][data-state="active"] {
        color: #ea580c !important;
        border-color: #ea580c !important;
    }
    
    [role="tab"]:hover {
        color: #ea580c;
    }
</style>
@endsection
