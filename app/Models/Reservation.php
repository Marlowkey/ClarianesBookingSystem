<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function availableTime()
    {
        return $this->belongsTo(AvailableTime::class, 'available_time_id');
    }

    public function user()
{
    return $this->belongsTo(User::class);
}

}
