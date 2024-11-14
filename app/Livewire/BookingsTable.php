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
                TextColumn::make('desk.room.floor.place.name'),
                TextColumn::make('desk.name')
                    ->description(function ($record) {
                        return $record->desk->room->name . ' / ' . $record->desk->room->floor->name;
                    }),
                TextColumn::make('start')->dateTime()
                    ->label('Date')
                ->description(fn ($state) => $state->diffForHumans()),
            ])
            ->filters([
                // ...
            ])
            ->recordUrl(function ($record) {
                return route('book', [$record->desk->room->id, Carbon::parse($record->start)->format('Y-m-d')]);
            })
            ->actions([
                \Filament\Tables\Actions\ActionGroup::make([
                    Action::make('release')
                        ->requiresConfirmation()
                        ->color('danger')
                        ->action(fn ($record) => $record->delete()),
                ])->label('Manage')
            ], ActionsPosition::AfterColumns)
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.bookings-table');
    }
}
