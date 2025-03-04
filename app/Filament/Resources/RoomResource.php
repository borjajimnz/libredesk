<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = null;

    protected static ?string $navigationGroup = 'Administration';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('')->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Grid::make()->schema([
                        Forms\Components\Select::make('floor_id')
                            ->relationship(
                                'floor',
                                'name',
                                modifyQueryUsing: fn (Builder $query) => $query->with('place'),
                            )
                            ->searchable()
                            ->required()
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->place->name} -> {$record->name}")
                            ->preload(),
                        Forms\Components\FileUpload::make('attributes.image')
                            ->visible(fn ($record) => $record)
                            ->hintAction(
                                Forms\Components\Actions\Action::make('Configure Desks in Map')
                                    ->disabled(fn ($record) => data_get($record, 'attributes.image') === null)
                                    ->url(fn ($record) => route('book', [$record->id]))
                                    ->button()
                            )
                            ->disk('public'),
                    ]),

                ]),
                Repeater::make('desks')
                    ->relationship()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('attributes.image'),
                        Forms\Components\Hidden::make('attributes.position'),
                        Forms\Components\Textarea::make('attributes.description'),
                    ])->grid(4),
            ])
            ->columns(0);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('floor.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('floor.place.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->modifyQueryUsing(fn ($query) => $query->with('desks'));
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
            'bookings' => Pages\DeskBookings::route('/{record}/bookings'),
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\EditRoom::class,
            Pages\DeskBookings::class,
        ]);
    }
}
