<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Desk extends Model
{
    use HasFactory;

    protected $casts = [
        'attributes' => 'array',
        'active' => 'boolean',
    ];

    protected $guarded = [];

    protected $appends = ['latlng', 'imageStorage'];

    public function getLatlngAttribute()
    {
        return data_get($this, 'attributes.latlng');
    }

    public function getImageStorageAttribute()
    {
        return Storage::url(data_get($this, 'attributes.image'));
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function bookings()
    {
        return $this->hasMany(DeskBooking::class);
    }
}
