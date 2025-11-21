<?php

namespace App\Livewire;

use App\Models\Trip;
use Livewire\Component;
use Livewire\WithPagination;

class TripsList extends Component
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
        $search = trim($this->search);

        if (!empty($search)) {
            $trips = Trip::where('title', 'like', "%$search%")
                ->orWhere('destination', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%")
                ->latest()
                ->paginate(12);
        } else {
            $trips = Trip::latest()->paginate(12);
        }

        return view('livewire.trips-list', compact('trips'));
    }
}
