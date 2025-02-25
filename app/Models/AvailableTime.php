<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableTime extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $guarded = [];

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'available_time_id');
    }

    public function unavailableTimeSlots()
    {
        return $this->hasMany(UnavailableTimeSlot::class);
    }
}
