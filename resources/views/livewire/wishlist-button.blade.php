<button
    wire:click="toggle"
    class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm px-1.5 py-1 rounded-full hover:bg-white transition-all duration-200 hover:scale-110"
    type="button"
>
    @if($isInWishlist)
        <x-heroicon-s-bookmark class="w-4 h-4 text-orange-600" />
    @else
        <x-heroicon-o-bookmark class="w-4 h-4 text-gray-800" />
    @endif
</button>
