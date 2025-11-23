<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRatingRequest;
use App\Http\Requests\UpdateRatingRequest;
use App\Models\Rating;
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function index()
    {
        $trips = Trip::whereHas('bookings', function ($q) {
            $q->where('user_id', Auth::id())
              ->where('status', 'confirmed');
        })->with('rating')->get();

        return view('ratings.index', compact('trips'));
    }

    public function create()
    {
        $trip = Trip::findOrFail(request('trip_id'));

        return view('ratings.create', compact('trip'));
    }

    public function store(StoreRatingRequest $request)
    {
        Rating::updateOrCreate(
            ['user_id' => Auth::id(), 'trip_id' => $request->trip_id],
            ['rating' => $request->rating, 'comment' => $request->comment]
        );

        return redirect()->route('rating.index')->with('success', 'Rating submitted!');
    }

    public function edit(Rating $rating)
    {
        if ($rating->user_id !== Auth::id()) abort(403);

        return view('ratings.edit', compact('rating'));
    }

    public function update(UpdateRatingRequest $request, Rating $rating)
    {
        if ($rating->user_id !== Auth::id()) abort(403);

        $rating->update($request->validated());

        return redirect()->route('rating.index')->with('success', 'Rating updated!');
    }
}
