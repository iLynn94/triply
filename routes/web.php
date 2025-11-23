<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CartController; // <--- 1. ADDED THIS IMPORT
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

// Public route - Guests can view trip details
Route::get('/trip/{trip}', [TripController::class, 'show'])->name('trip.show');

Route::get('/login', function () {
    return redirect()->route('sign-in');
})->name('login');

Route::middleware('guest')->group(function () {
    Route::get('/sign-in', [AuthController::class, 'showSignIn'])->name('login');;
    Route::post('/sign-in', [AuthController::class, 'signIn']);

    Route::get('/sign-up', [AuthController::class, 'showSignUp'])->name('sign-up');
    Route::post('/sign-up', [AuthController::class, 'signUp']);
});

Route::middleware('auth')->group(function () {
    // Email Verification Routes
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', [ProfileController::class, 'verifyEmail'])
        ->middleware(['signed'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [ProfileController::class, 'resendVerificationEmail'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');

    // <--- 2. REPLACED THE OLD CART ROUTE WITH THESE 3 NEW ROUTES --->
    // Show the cart staging area
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    
    // Add a trip to the staging area
    Route::post('/cart/add/{id}', [CartController::class, 'store'])->name('cart.add');
    
    // Remove a trip from the staging area
    Route::delete('/cart/remove/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    // <--- END OF CART CHANGES --->

    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/trips/{trip}/status', [DashboardController::class, 'updateTripStatus'])->name('dashboard.trips.status');
    Route::post('/dashboard/users/{user}/revoke-verification', [DashboardController::class, 'revokeVerification'])->name('dashboard.users.revoke-verification');
    Route::post('/dashboard/users/{user}/change-role', [DashboardController::class, 'changeUserRole'])->name('dashboard.users.change-role');

    Route::get('/profile', function () {
        return view('users.edit');
    })->name('profile');

    // Bookings index route to match navbar link
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings');

    // Create travel package route (alias for trips.create)
    Route::get('/create-travel-package', [TripController::class, 'create'])->name('create-travel-package');
    Route::post('/create-travel-package', [TripController::class, 'store']);

    // Trip resource routes (except show which is public)
    Route::resource('trips', TripController::class)->except(['show']);
    Route::resource('bookings', BookingController::class)->except(['index']);
    Route::resource('wishlist', WishlistController::class);
    Route::post('/wishlist/{trip}', [WishlistController::class, 'store'])->name('wishlist.store');

    Route::resource('rating', RatingController::class);

    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('/wishlist/toggle/{trip}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

     Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});