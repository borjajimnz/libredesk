<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeskBooking extends Model
{
    use HasFactory;

    protected $casts = [
        'attributes' => 'array',
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    protected $guarded = [];

    public function desk()
    {
        return $this->belongsTo(Desk::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
