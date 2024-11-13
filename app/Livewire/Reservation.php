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
use Filament\Forms\Components\Section;
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

class Reservation extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public $image;
    public $roomId;
    public $date;


    public function form(Form $form): Form
    {
        return $form
            ->columns(4)
            ->schema([

                    DatePicker::make('date')
                        ->required()
                        ->lazy(),
                    Select::make('place')
                        ->required()
                        ->live()
                        ->disabled(fn (Get $get) => !$get('date'))
                        ->options(Place::query()->get()->pluck('name', 'id')),
                    Select::make('floor')
                        ->required()
                        ->live()
                        ->disabled(fn (Get $get) => !$get('place'))
                        ->options(fn (Get $get) => Floor::query()
                            ->where('place_id', $get('place'))
                            ->get()
                            ->pluck('name', 'id')
                        ),
                    Select::make('room')
                        ->required()
                        ->disabled(fn (Get $get) => !$get('floor'))
                        ->options(fn (Get $get) => Room::query()
                            ->where('floor_id', $get('floor'))
                            ->get()
                            ->pluck('name', 'id')
                        )

            ])
            ->statePath('data');
    }

    public function create()
    {
        if (!data_get($this, 'data.date') ||
            !data_get($this, 'data.room')) {
            return;
        }

        return $this->redirect(route('book', [
            data_get($this, 'data.room'),
            data_get($this, 'data.date'),
        ]));
    }

    public function render()
    {
        return view('livewire.reservation');
    }
}
