<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $casts = [
        'attributes' => 'array',
    ];

    protected $guarded = [];

    public function floors()
    {
        return $this->hasMany(Floor::class);
    }
}
