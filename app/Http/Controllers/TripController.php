<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTripRequest;
use App\Http\Requests\UpdateTripRequest;
use App\Models\Trip;
use App\Services\CloudinaryService;
use Illuminate\Support\Facades\Log;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('trips.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTripRequest $request)
    {
        try {
            // Get validated data
            $validated = $request->validated();

            // Decode JSON fields
            if (isset($validated['gallery'])) {
                $validated['gallery'] = json_decode($validated['gallery'], true);
            }
            if (isset($validated['highlights'])) {
                $validated['highlights'] = json_decode($validated['highlights'], true);
            }
            if (isset($validated['inclusions'])) {
                $validated['inclusions'] = json_decode($validated['inclusions'], true);
            }
            if (isset($validated['exclusions'])) {
                $validated['exclusions'] = json_decode($validated['exclusions'], true);
            }
            if (isset($validated['transport_options'])) {
                $validated['transport_options'] = json_decode($validated['transport_options'], true);
            }
            if (isset($validated['itinerary'])) {
                $validated['itinerary'] = json_decode($validated['itinerary'], true);
            }

            // Add the organizer_id (current authenticated user)
            $validated['organizer_id'] = auth()->id();

            // Create the trip
            $trip = Trip::create($validated);

            // Redirect to trip page with success message
            return redirect()->route('trip.show', $trip->id)
                ->with('success', 'Trip package created successfully!');

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Trip creation failed: ' . $e->getMessage());

            // Redirect back with error
            return back()
                ->withInput()
                ->with('error', 'Failed to create trip package. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Trip $trip) {
        // Load ratings with user relationship
        $trip->load(['ratings' => function($query) {
            $query->with('user')->latest();
        }]);

        return view('trips.show', compact('trip'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trip $trip) {
        return view('trips.edit', compact('trip'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTripRequest $request, Trip $trip)
    {
        try {
            // Get validated data
            $validated = $request->validated();

            // Handle cover image update
            if (isset($validated['cover_image_url']) && isset($validated['old_cover_image_url'])) {
                // Delete old cover image from Cloudinary
                CloudinaryService::deleteImage($validated['old_cover_image_url']);
                unset($validated['old_cover_image_url']); // Remove from data to be saved
            }

            // Handle removed gallery images
            if (isset($validated['removed_gallery_images'])) {
                $removedImages = json_decode($validated['removed_gallery_images'], true);
                if (is_array($removedImages) && !empty($removedImages)) {
                    CloudinaryService::deleteImages($removedImages);
                }
                unset($validated['removed_gallery_images']); // Remove from data to be saved
            }

            // Decode JSON fields
            if (isset($validated['gallery'])) {
                $validated['gallery'] = json_decode($validated['gallery'], true);
            }
            if (isset($validated['highlights'])) {
                $validated['highlights'] = json_decode($validated['highlights'], true);
            }
            if (isset($validated['inclusions'])) {
                $validated['inclusions'] = json_decode($validated['inclusions'], true);
            }
            if (isset($validated['exclusions'])) {
                $validated['exclusions'] = json_decode($validated['exclusions'], true);
            }
            if (isset($validated['transport_options'])) {
                $validated['transport_options'] = json_decode($validated['transport_options'], true);
            }
            if (isset($validated['itinerary'])) {
                $validated['itinerary'] = json_decode($validated['itinerary'], true);
            }

            // Update the trip
            $trip->update($validated);

            // Redirect to trip page with success message
            return redirect()->route('trip.show', $trip->id)
                ->with('success', 'Trip package updated successfully!');

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Trip update failed: ' . $e->getMessage());

            // Redirect back with error
            return back()
                ->withInput()
                ->with('error', 'Failed to update trip package. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trip $trip)
    {
        try {
            // Delete cover image from Cloudinary
            if ($trip->cover_image_url) {
                CloudinaryService::deleteImage($trip->cover_image_url);
            }

            // Delete all gallery images from Cloudinary
            if ($trip->gallery && is_array($trip->gallery) && !empty($trip->gallery)) {
                CloudinaryService::deleteImages($trip->gallery);
            }

            // Delete the trip from database
            $trip->delete();

            // Redirect to homepage with success message
            return redirect()->route('home')
                ->with('success', 'Trip package deleted successfully!');

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Trip deletion failed: ' . $e->getMessage());

            // Redirect back with error
            return back()
                ->with('error', 'Failed to delete trip package. Please try again.');
        }
    }
}
