<?php

namespace App\Livewire;

use App\Models\Trip;
use App\Models\Wishlist;
use Livewire\Component;

class WishlistButton extends Component
{
    public Trip $trip;
    public bool $isInWishlist = false;

    public function mount()
    {
        $this->checkWishlistStatus();
    }

    public function checkWishlistStatus()
    {
        $this->isInWishlist = $this->trip->isInWishlist();
    }

    public function toggle()
    {
        if (!auth()->check()) {
            $this->dispatch('notify', [
                'type' => 'error',
                'content' => 'Please sign in to add trips to your wishlist',
            ]);
            return;
        }

        try {
            if ($this->isInWishlist) {
                // Remove from wishlist
                Wishlist::where('user_id', auth()->id())
                    ->where('trip_id', $this->trip->id)
                    ->delete();

                $this->isInWishlist = false;

                $this->dispatch('notify', [
                    'type' => 'success',
                    'content' => 'Trip removed from your wishlist',
                ]);
            } else {
                // Add to wishlist
                Wishlist::create([
                    'user_id' => auth()->id(),
                    'trip_id' => $this->trip->id,
                ]);

                $this->isInWishlist = true;

                $this->dispatch('notify', [
                    'type' => 'success',
                    'content' => 'Trip added to your wishlist',
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'content' => 'Something went wrong. Please try again.',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.wishlist-button');
    }
}
