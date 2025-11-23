<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWishlistRequest;
use App\Http\Requests\UpdateWishlistRequest;
use App\Models\Trip;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('wishlist.index');
    }

    public function toggle(Trip $trip) {
        $userId = auth()->id();

        $wishlist = Wishlist::where('user_id', $userId)
            ->where('trip_id', $trip->id)
            ->first();

        if ($wishlist) {
            // Remove from wishlist
            $wishlist->delete();
            return back()->with('success', 'Removed from wishlist');
        } else {
            // Add to wishlist
            Wishlist::create([
                'user_id' => $userId,
                'trip_id' => $trip->id,
            ]);
            return back()->with('success', 'Added to wishlist');
        }
    }

    public function toggle(Trip $trip) {
        $userId = auth()->id();

        $wishlist = Wishlist::where('user_id', $userId)
            ->where('trip_id', $trip->id)
            ->first();

        if ($wishlist) {
            // Remove from wishlist
            $wishlist->delete();
            return back()->with('success', 'Removed from wishlist');
        } else {
            // Add to wishlist
            Wishlist::create([
                'user_id' => $userId,
                'trip_id' => $trip->id,
            ]);
            return back()->with('success', 'Added to wishlist');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($trip)
    {
        // 
    }

    /**
     * Display the specified resource.
     */
    public function show(Wishlist $wishlist)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wishlist $wishlist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWishlistRequest $request, Wishlist $wishlist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wishlist $wishlist)
    {
        //
    }
}