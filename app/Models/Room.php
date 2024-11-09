<?php

namespace App\Models;

use App\Filament\Resources\RoomResource\Pages\DeskBookings;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $casts = [
        'attributes' => 'array'
    ];

    protected $guarded = [];

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function desks()
    {
        return $this->hasMany(Desk::class);
    }

    public function deskBookings()
    {
        return $this->hasManyThrough(DeskBooking::class, Desk::class);
    }
}
