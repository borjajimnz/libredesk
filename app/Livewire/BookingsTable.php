<?php

namespace App\Livewire;

use App\Models\DeskBooking;
use Carbon\Carbon;
use Filament\Actions\ActionGroup;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BookingsTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->heading('upcoming bookings')
            ->query(DeskBooking::query()
                ->with('desk.room.floor.place')
                ->where('user_id', Auth::id()))
            ->columns([
                TextColumn::make('start')->dateTime(),
                TextColumn::make('end')->dateTime(),
                TextColumn::make('desk.name'),
                TextColumn::make('desk.room.name'),
                TextColumn::make('desk.room.floor.name'),
                TextColumn::make('desk.room.floor.place.name'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                Action::make('manage')
                    ->button()
                    ->color('primary')
                    ->size('xs')
                ->url(function ($record) {
                    return route('book', [$record->desk->room->id, Carbon::parse($record->start)->format('Y-m-d')]);
                }),
                \Filament\Tables\Actions\ActionGroup::make([
                    Action::make('release')
                        ->requiresConfirmation()
                        ->color('danger')
                        ->action(fn ($record) => $record->delete()),
                ])
            ], ActionsPosition::BeforeColumns)
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.bookings-table');
    }
}
