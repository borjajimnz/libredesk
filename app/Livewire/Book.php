<?php

namespace App\Livewire;

use App\Filament\Resources\RoomResource\Pages\DeskBookings;
use App\Models\Desk;
use App\Models\DeskBooking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Book extends Component
{
    public $latitude = 51.505;
    public $editMode = true;
    public $longitude = -0.09;
    public $zoom = 13;
    public $image;
    public $roomId;
    public $date;
    public $desks;

    public function mount()
    {
        $this->loadDesks();

        $this->date = now()->toString();
    }

    public function updateZoom($newZoom)
    {
        $this->zoom = $newZoom;
        $this->dispatch('zoom-updated', zoom: $this->zoom);
    }

    public function hola()
    {
        $this->zoom = 33;
    }

    public function saveDesk($desk, $position)
    {
        $desk = Desk::query()->find($desk['id']);
        $attributes = $desk->attributes;
        $attributes['position'] = $position;
        $desk->attributes = $attributes;
        $desk->save();
    }

    public function deleteDesk($desk)
    {
        $desk = Desk::query()->find($desk['id']);
        $attributes = $desk->attributes;
        unset($attributes['position']);
        $desk->attributes = $attributes;
        $desk->save();
    }

    public function bookDesk($desk)
    {
        DeskBooking::query()->create([
            'desk_id' => data_get($desk, 'id'),
            'start' => Carbon::parse($this->date)->startOfDay(),
            'end' => Carbon::parse($this->date)->endOfDay(),
        ]);

        $this->loadDesks();
    }

    public function deleteBook($desk)
    {
        $booking = head($desk['bookings']);
        DeskBooking::query()
            ->where('id', data_get($booking, 'id'))
            ->delete();

        $this->loadDesks();
    }

    public function render()
    {
        return view('livewire.book');
    }

    public function loadDesks()
    {
        $room = Room::query()
            ->with('desks.bookings')
            ->first();

        $this->image = Storage::url(data_get($room, 'attributes.image'));
        $this->roomId = data_get($room, 'id');
        $this->desks = $room->desks
            ->map(function ($desk) {
                if (!empty(data_get($desk, 'attributes.position', []))) {
                    $desk->lat = data_get($desk, 'attributes.position.lat');
                    $desk->lng = data_get($desk, 'attributes.position.lng');
                    $desk->placedInMap = true;
                } else {
                    $desk->lat = 0;
                    $desk->lng = 0;
                    $desk->placedInMap = false;
                }
                return $desk;
            })->toArray();
    }
}
