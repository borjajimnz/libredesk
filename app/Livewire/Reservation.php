<?php

namespace App\Livewire;

use App\Models\Room;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
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
                    ->placeholder(translate('choose_date'))
                    ->minDate(now()->startOfDay())
                    ->label('')
                    ->required()
                    ->columnSpan(5)
                    ->lazy()
                    ->closeOnDateSelection(),
                Select::make('room')
                    ->prefixIcon('heroicon-o-building-office')
                    ->label('')
                    ->columnSpan(5)
                    ->required()
                    ->options(
                        fn (Get $get) => Room::query()
                        ->get()
                        ->pluck('name', 'id')
                    ),
                Actions::make([
                    Action::make('continue')
                        ->icon('heroicon-o-map')
                        ->button()
                        ->action(function () use ($form) {
                            $form->fill($this->data);
                            $form->validate();

                            $this->redirect(route('book', [
                                data_get($this, 'data.room'),
                                data_get($this, 'data.date'),
                            ]));
                        }),
                ]),

            ])
            ->statePath('data');
    }

    public function create()
    {
        if (! data_get($this, 'data.date') ||
            ! data_get($this, 'data.room')) {
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
