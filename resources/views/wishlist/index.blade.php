@extends('layouts.main')

@section('title', 'Wishlist')

@section('content')
    <main class="container mx-auto px-4 py-8 md:px-6 lg:px-12 xl:px-16 min-h-screen">

    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6 border-b-2 border-orange-400 inline-block pb-1">
        My Wishlist
    </h1>

    @if($wishlistItems->isEmpty())
        <p class="text-gray-500 text-center mt-12">Your wishlist is empty.</p>
    @else
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach($wishlistItems as $item)
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                    
                    {{-- Trip Image --}}
                    <div class="relative overflow-hidden">
                        <img 
                            src="{{ asset($item->trip->cover_image_url) }}" 
                            alt="{{ $item->trip->title }}" 
                            class="w-full h-48 object-cover transition-transform duration-300 hover:scale-110"
                        >

                        {{-- Wishlist Toggle --}}
                        @auth
                            <livewire:wishlist-button :trip="$item->trip" :key="'wishlist-'.$item->trip->id" />
                        @endauth
                    </div>

                    {{-- Trip Details --}}
                    <div class="p-4">
                        {{-- Title --}}
                        <h3 class="text-sm font-semibold text-gray-900 mb-1 min-h-10 line-clamp-2">
                            {{ $item->trip->title }}
                        </h3>

                        {{-- Destination --}}
                        @if($item->trip->destination)
                            <p class="text-xs text-gray-600 mb-1 flex items-center gap-1">
                                <x-heroicon-o-map-pin class="w-3 h-3" />
                                {{ $item->trip->destination }}
                            </p>
                        @endif

                        {{-- Price and CTA --}}
                        <div class="flex items-center justify-between mt-4">
                            <div>
                                <p class="text-xs text-gray-500">From</p>
                                <p class="text-base font-bold text-orange-600">
                                    Ksh {{ number_format($item->trip->base_price_per_person, 0) }}
                                </p>
                            </div>
                            <a 
                                href="{{ route('trip.show', $item->trip->id) }}" 
                                class="group inline-flex items-center gap-1 text-sm font-medium text-gray-800 hover:text-blue-600 transition-colors"
                            >
                                More Info
                                <x-heroicon-o-arrow-up-right class="w-3 h-4 text-orange-600 group-hover:text-blue-600 transition-colors" />
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    @endif

</main>
@endsection
