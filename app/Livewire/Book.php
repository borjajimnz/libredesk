<?php

namespace App\Livewire;

use App\Filament\Resources\RoomResource\Pages\DeskBookings;
use App\Models\Desk;
use App\Models\DeskBooking;
use App\Models\Floor;
use App\Models\Place;
use App\Models\Room;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Book extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    public $editMode = false;
    public $image;
    public $roomId;
    public $date;
    public $desks = [];

    public function mount($roomId, $date): void
    {
        $this->loadDesks();
        $this->form->fill();

        if (!$date) {
            $this->editMode = true;
        }
        $this->date = Carbon::parse($date);
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(4)
            ->schema([
                DatePicker::make('date'),
                Select::make('place')
                    ->live()
                    ->options(Place::query()->get()->pluck('name', 'id')),
                Select::make('floor')
                    ->live()
                    ->disabled(fn (Get $get) => !$get('place'))
                    ->options(fn (Get $get) => Floor::query()
                        ->where('place_id', $get('place'))
                        ->get()
                        ->pluck('name', 'id')
                    ),
                Select::make('room')
                    ->live()
                    ->disabled(fn (Get $get) => !$get('floor'))
                    ->options(fn (Get $get) => Room::query()
                        ->where('floor_id', $get('floor'))
                        ->get()
                        ->pluck('name', 'id')
                    ),
                TextInput::make('action')
                    ->visible(function (Get $get) {
                        if ($get('room')) {
                            $this->roomId = $get('room');
                            $this->loadDesks();
                        }

                        return false;
                    })
            ])
            ->statePath('data');
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
            ->where('id', $this->roomId)
            ->first();

        if ($this->roomId) {

        }

        if (!$room) {
            return;
        }

        $this->image = Storage::url(data_get($room, 'attributes.image'));
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
