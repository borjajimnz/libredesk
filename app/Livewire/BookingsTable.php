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
use Illuminate\Support\HtmlString;
use Livewire\Component;

class BookingsTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->striped()
            ->query(DeskBooking::query()
                ->with('desk.room.floor.place')
                ->where('start', '>', now()->subDay())
                ->where('user_id', Auth::id()))
            ->columns([
                TextColumn::make('start')->date()
                    ->label('Date')
                    ->description(fn ($state) => $state->diffForHumans()),
                TextColumn::make('desk.name')
                    ->html()
                    ->formatStateUsing(function ($record) {
                        return new HtmlString('<strong>'.$record->desk->name. '</strong>') . ' (' . $record->desk->room->name . ')';
                    })
                    ->description(function ($record) {
                        return $record->desk->room->floor->name . ' / ' . $record->desk->room->floor->place->name;
                    }),
            ])
            ->filters([
                // ...
            ])
            ->recordUrl(function ($record) {
                return route('book', [$record->desk->room->id, Carbon::parse($record->start)->format('Y-m-d')]);
            })
            ->actions([
                Action::make('view map')
                    ->tooltip('Map')
                    ->iconButton()
                    ->icon('heroicon-o-map')
                    ->url(function ($record) {
                        return route('book', [$record->desk->room->id, Carbon::parse($record->start)->format('Y-m-d')]);
                    }),
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
