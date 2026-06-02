<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id', 'date',
        'start_time', 'end_time', 'is_booked'
    ];

    protected $casts = [
        'is_booked' => 'boolean',
        'date'      => 'date',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}