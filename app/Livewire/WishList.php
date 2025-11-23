<?php

namespace App\Livewire;

use App\Models\Trip;
use Livewire\Component;
use Livewire\WithPagination;

class WishList extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected $rules = [
        'search' => 'nullable|string|max:50',
    ];

    public function updatingSearch()
    {
        $this->validate();
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function render()
    {
        $userId = auth()->id();
        $search = trim($this->search);

        // Query trips that are in the user's wishlist
        $query = Trip::whereHas('wishlists', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        });

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('destination', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        }

        $trips = $query->latest()->paginate(12);

        return view('livewire.wish-list', compact('trips'));
    }
}