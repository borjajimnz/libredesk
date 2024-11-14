<?php

namespace App\Livewire;

use App\Filament\Resources\RoomResource\Pages\DeskBookings;
use App\Models\Desk;
use App\Models\DeskBooking;
use App\Models\Floor;
use App\Models\Place;
use App\Models\Room;
use Carbon\Carbon;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Support\Enums\ActionSize;
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
            ->columns(12)
            ->schema([
                    DatePicker::make('date')
                        ->prefixIcon('heroicon-o-calendar')
                        ->minDate(now()->startOfDay())
                        ->label('')
                        ->required()
                        ->columnSpan(5)
                        ->live(),
                    Select::make('room')
                        ->prefixIcon('heroicon-o-building-office')
                        ->label('')
                        ->columnSpan(5)
                        ->required()
                        ->disabled(fn (Get $get) => !$get('date'))
                        ->options(fn (Get $get) => Room::query()
                            ->get()
                            ->pluck('name', 'id')
                        ),
                    Actions::make([
                        Action::make('book')
                            ->label('Book')
                            ->icon('heroicon-o-map')
                            ->button()
                            ->label('Continue')
                            ->action(function () use ($form) {
                                $form->fill($this->data);
                                $form->validate();

                                $this->redirect(route('book', [
                                    data_get($this, 'data.room'),
                                    data_get($this, 'data.date'),
                                ]));
                            })
                    ]),


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
