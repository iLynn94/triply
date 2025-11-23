<button
    wire:click="toggle"
    class="{{ $variant === 'large' ? 'p-3 rounded-full hover:bg-gray-100 transition-colors' : 'absolute top-2 right-2 bg-white/90 backdrop-blur-sm px-1.5 py-1 rounded-full hover:bg-white transition-all duration-200 hover:scale-110' }}"
    type="button"
    title="{{ $isInWishlist ? 'Remove from wishlist' : 'Add to wishlist' }}"
>
    @if($isInWishlist)
        <x-heroicon-s-heart class="{{ $variant === 'large' ? 'w-7 h-7' : 'h-3 w-3 sm:w-4 sm:h-4' }} text-red-600" />
    @else
        <x-heroicon-o-heart class="{{ $variant === 'large' ? 'w-7 h-7 text-gray-600' : 'h-3 w-3 sm:w-4 sm:h-4 text-gray-800' }}" />
    @endif
</button>
