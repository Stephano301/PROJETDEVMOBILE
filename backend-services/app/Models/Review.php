<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'provider_id',
        'booking_id', 'rating', 'comment'
    ];

    public function user()    { return $this->belongsTo(User::class); }
    public function provider(){ return $this->belongsTo(Provider::class); }
    public function booking() { return $this->belongsTo(Booking::class); }
}