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
        $bookings = Booking::with(['trip.ratings', 'trip.organizer'])
            ->where('user_id', auth()->id())
            ->orderBy('start_date', 'desc')
            ->get();

        return view('bookings.index', compact('bookings'));
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
    public function update(Request $request, Booking $booking)
    {
        // Ensure user owns this booking
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Only allow updates if trip is upcoming
        if ($booking->start_date->isPast()) {
            return back()->with('error', 'Cannot update past bookings');
        }

        $validated = $request->validate([
            'number_of_adults' => 'required|integer|min:1',
            'number_of_children' => 'nullable|integer|min:0',
            'start_date' => 'required|date|after_or_equal:today',
            'selected_transport' => 'nullable|string',
            'status' => 'nullable|in:pending,confirmed,cancelled',
        ]);

        // Recalculate total fee
        $totalFee = $booking->trip->calculateBookingPrice(
            $validated['number_of_adults'],
            $validated['number_of_children'] ?? 0,
            $validated['selected_transport'] ?? null
        );

        $booking->update([
            'number_of_adults' => $validated['number_of_adults'],
            'number_of_children' => $validated['number_of_children'] ?? 0,
            'selected_transport' => $validated['selected_transport'],
            'total_fee' => $totalFee,
            'start_date' => $validated['start_date'],
            'status' => $validated['status'] ?? $booking->status,
        ]);

        return back()->with('success', 'Booking updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        // Ensure user owns this booking
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Only allow deletion if not paid
        if ($booking->payment_status === 'paid') {
            return back()->with('error', 'Cannot cancel paid bookings. Please contact support.');
        }

        $booking->delete();

        return redirect()->route('bookings')->with('success', 'Booking cancelled successfully!');
    }
}