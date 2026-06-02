<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'provider_id', 'service_id',
        'time_slot_id', 'booking_date', 'booking_time',
        'address', 'notes', 'status', 'total_price'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'total_price'  => 'decimal:2',
    ];

    public function user()     { return $this->belongsTo(User::class); }
    public function provider() { return $this->belongsTo(Provider::class); }
    public function service()  { return $this->belongsTo(Service::class); }
    public function timeSlot() { return $this->belongsTo(TimeSlot::class); }
}