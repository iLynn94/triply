<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarReminder extends Model
{
    /** @use HasFactory<\Database\Factories\CalendarReminderFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trip_id',
        'reminder_date',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
