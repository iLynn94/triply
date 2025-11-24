<div>
    {{-- Section Header with Title and Search Bar --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="shrink-0">
            <h2 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight border-b-2 border-orange-400 inline-block pb-0.5">
                My Wishlist
            </h2>
        </div>

        <div class="shrink-0 w-full md:w-auto md:max-w-sm">
            <x-ui.input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Search trips..."
                leftIcon="magnifying-glass"
                clearable
                x-on:clear="$wire.clearSearch()"
            />
        </div>
    </div>

    {{-- Trips Grid --}}
    @if($trips->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-5">
            @foreach($trips as $trip)
                <x-trip-card :trip="$trip" />
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $trips->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <div class="max-w-md mx-auto bg-white rounded-lg shadow p-8">
                <x-heroicon-o-magnifying-glass class="w-16 h-16 text-gray-400 mx-auto mb-4" />
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No trips found</h3>
                <p class="text-gray-600">
                    @if($search)
                        No results for "{{ $search }}". Try a different search term.
                    @else
                        No trips available at the moment.
                    @endif
                </p>
                @if($search)
                    <button
                        wire:click="clearSearch"
                        class="inline-block mt-4 text-blue-600 hover:text-blue-700 font-medium"
                    >
                        Clear search
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>