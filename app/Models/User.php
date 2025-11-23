<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'citizenship',
        'profile_image_url',
        'password',
        'role',
        'is_verified',
    ];

    /**
     * Attributes hidden from serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
        ];
    }

    // RELATIONSHIPS

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function calendarReminders()
    {
        return $this->hasMany(CalendarReminder::class);
    }

    public function budgetSplits()
    {
        return $this->hasMany(BudgetSplit::class);
    }

    // If organizers have trips:
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function isAdmin(): bool {
        return $this->role === 'admin';
    }

    public function isVerified(): bool {
        return $this->is_verified && $this->email_verified_at !== null;
    }
}
