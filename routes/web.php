<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

// Public route - Guests can view trip details
Route::get('/trip/{trip}', [TripController::class, 'show'])->name('trip.show');

Route::middleware('guest')->group(function () {
    Route::get('/sign-in', [AuthController::class, 'showSignIn'])->name('sign-in');
    Route::post('/sign-in', [AuthController::class, 'signIn']);

    Route::get('/sign-up', [AuthController::class, 'showSignUp'])->name('sign-up');
    Route::post('/sign-up', [AuthController::class, 'signUp']);
});

Route::middleware('auth')->group(function () {

    Route::get('/cart', function () {
        return view('cart.index');
    })->name('cart');

    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::get('/profile', function () {
        return view('users.edit');
    })->name('profile');

    // Bookings index route to match navbar link
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings');

    // Trip resource routes (except show which is public)
    Route::resource('trip', TripController::class)->except(['show']);
    Route::resource('booking', BookingController::class)->except(['index']);
    Route::resource('wishlist', WishlistController::class);
    Route::post('/wishlist/{trip}', [WishlistController::class, 'store'])->name('wishlist.store');

    Route::resource('rating', RatingController::class);

    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});