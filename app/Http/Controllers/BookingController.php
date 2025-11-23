<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\Trip;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('bookings.index');
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
      public function store(Request $request)
      {
        $validated = $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'number_of_adults' => 'required|integer|min:1',
            'number_of_children' => 'nullable|integer|min:0',
            'start_date' => 'required|date|after_or_equal:today',
            'selected_transport' => 'nullable|string',
        ]);

        $trip = Trip::findOrFail($validated['trip_id']);

        // Calculate total fee
        $totalFee = $trip->calculateBookingPrice(
            $validated['number_of_adults'],
            $validated['number_of_children'] ?? 0,
            $validated['selected_transport'] ?? null
        );

        // Create booking
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'trip_id' => $trip->id,
            'number_of_adults' => $validated['number_of_adults'],
            'number_of_children' => $validated['number_of_children'] ?? 0,
            'selected_transport' => $validated['selected_transport'],
            'total_fee' => $totalFee,
            'start_date' => $validated['start_date'],
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        return redirect()->route('bookings')->with('success', 'Booking created successfully!');
    }

    /**`
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
