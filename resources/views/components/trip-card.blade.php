<div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
    {{-- Trip Image --}}
    <div class="relative overflow-hidden">
        <img
            src="{{ $trip->cover_image_url }}"
            alt="{{ $trip->title }}"
            class="w-full h-48 object-cover transition-transform duration-300 hover:scale-110"
        >

        @auth
            <livewire:wishlist-button :trip="$trip" :key="'wishlist-'.$trip->id" />
        @else
            <div class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-full text-xs font-semibold text-gray-800">
                {{ $trip->duration_days }} {{ Str::plural('Day', $trip->duration_days) }}
            </div>
        @endauth
    </div>

    {{-- Trip Details --}}
    <div class="p-4">
        {{-- Title --}}
        <h3 class="text-sm font-semibold text-gray-900 mb-1 min-h-10 line-clamp-2">
            {{ $trip->title }}
        </h3>

        {{-- Destination --}}
        @if($trip->destination)
            <p class="text-xs text-gray-600 mb-1 flex items-center gap-1">
                <x-heroicon-o-map-pin class="w-3 h-3" />
                {{ $trip->destination }}
            </p>
        @endif

        {{-- Price and CTA --}}
        <div class="flex items-center justify-between mt-4">
            <div>
                <p class="text-xs text-gray-500">From</p>
                <p class="text-base font-bold text-orange-600">
                    Ksh {{ number_format($trip->base_price_per_person, 0) }}
                </p>
            </div>
            <a
                href="{{ route('trip.show', $trip->id) }}"
                class="group inline-flex items-center gap-1 text-sm font-medium text-gray-800 hover:text-blue-600 transition-colors"
            >
                More Info
                <x-heroicon-o-arrow-up-right class="w-3 h-4 text-orange-600 group-hover:text-blue-600 transition-colors" />
            </a>
        </div>
    </div>
</div>
