<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;

    protected $casts = [
        'attributes' => 'array',
    ];

    protected $guarded = [];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
