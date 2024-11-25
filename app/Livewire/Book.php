<?php

namespace App\Livewire;

use App\Models\Desk;
use App\Models\DeskBooking;
use App\Models\Room;
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Book extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public $editMode = false;

    public $image;

    public $roomId;

    public $date;

    public $auth;

    public $desks = [];

    public function mount($roomId, $date = null): void
    {
        $user = Auth::user();
        if ($date === null) {
            if ($user->Admin) {
                $this->editMode = true;
            } else {
                redirect(route('welcome'));
            }
        }

        $this->loadDesks();

        if (! $date) {
            $this->editMode = true;
        }
        $this->date = Carbon::parse($date);
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
        $desk = Desk::query()
            ->where('id', $desk['id'])
            ->firstOrFail();

        $attributes = $desk->attributes;
        unset($attributes['position']);
        $desk->attributes = $attributes;
        $desk->save();
    }

    public function bookDesk($desk)
    {
        $desks = Desk::query()->where('room_id', $desk['room_id'])->get()->pluck('id');
        $booked = DeskBooking::query()
            ->where('user_id', Auth::id())
            ->whereIn('desk_id', $desks)
            ->whereDate('start', $this->date)
            ->first();

        if ($booked) {
            Notification::make()
                ->title(translate('only_allowed_to_book_once'))
                ->danger()
                ->send();

            return;
        }

        DeskBooking::query()->create([
            'desk_id' => data_get($desk, 'id'),
            'user_id' => Auth::id(),
            'start' => Carbon::parse($this->date)->startOfDay(),
            'end' => Carbon::parse($this->date)->endOfDay(),
        ]);

        Notification::make()
            ->title(translate('booked_successfully'))
            ->success()
            ->send();

        $this->loadDesks();
    }

    public function deleteBook($desk)
    {
        $booking = head($desk['bookings']);
        DeskBooking::query()
            ->where('id', data_get($booking, 'id'))
            ->delete();

        Notification::make()
            ->title(translate('booking_cancelled'))
            ->success()
            ->send();

        $this->loadDesks();
    }

    public function render()
    {
        return view('livewire.book');
    }

    public function loadDesks()
    {
        $room = Room::query()
            ->with(['desks.bookings' => function ($query) {
                $query->whereDate('start', $this->date);
            }, 'desks.bookings.user'])
            ->where('id', $this->roomId)
            ->first();

        if (! $room) {
            return;
        }

        $this->image = data_get($room, 'attributes.image');
        $this->desks = $room->desks
            ->map(function ($desk) {
                if (! empty(data_get($desk, 'attributes.position', []))) {
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
