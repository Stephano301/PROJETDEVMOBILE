<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'phone', 'address', 'city',
        'bio', 'photo', 'hourly_rate', 'rating',
        'reviews_count', 'is_available', 'is_verified'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'is_verified'  => 'boolean',
        'hourly_rate'  => 'decimal:2',
        'rating'       => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'provider_service');
    }

    public function timeSlots()
    {
        return $this->hasMany(TimeSlot::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}