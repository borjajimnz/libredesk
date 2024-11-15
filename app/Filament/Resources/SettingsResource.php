<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingsResource\Pages;
use App\Filament\Resources\SettingsResource\RelationManagers;
use App\Models\Settings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettingsResource extends Resource
{
    protected static ?string $model = Settings::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    protected static $colors = [
        'slate' => 'slate',
        'gray' => 'gray',
        'zinc' => 'zinc',
        'neutral' => 'neutral',
        'stone' => 'stone',
        'red' => 'red',
        'orange' => 'orange',
        'amber' => 'amber',
        'yellow' => 'yellow',
        'lime' => 'lime',
        'green' => 'green',
        'emerald' => 'emerald',
        'teal' => 'teal',
        'cyan' => 'cyan',
        'sky' => 'sky',
        'blue' => 'blue',
        'indigo' => 'indigo',
        'violet' => 'violet',
        'purple' => 'purple',
        'fuchsia' => 'fuchsia',
        'pink' => 'pink',
        'rose' => 'rose',
    ];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                ->schema([
                    Forms\Components\TextInput::make('key')
                        ->readOnly(fn ($state) => in_array($state, config('libredesk.core_settings')))
                        ->lazy(),
                    Forms\Components\Select::make('data.type')
                        //  ->disabled(fn (Forms\Get $get) => in_array($get('key'), config('libredesk.core_settings')))
                        ->live()
                        ->default('text')
                        ->options([
                            'image' => 'image',
                            'text' => 'text',
                            'color' => 'color',
                            'options' => 'options',
                            'select' => 'select',
                        ]),
                    Forms\Components\TextInput::make('data.text_value')
                        ->visible(function (Forms\Get $get) {
                            return $get('data.type') === 'text';
                        }),
                    Forms\Components\FileUpload::make('data.image_value')
                        ->image()
                        ->imageEditor()
                        ->visible(function (Forms\Get $get) {
                            return $get('data.type') === 'image';
                        }),
                    Forms\Components\Select::make('data.color_value')
                        ->options(self::$colors)
                        ->visible(function (Forms\Get $get) {
                            return $get('data.type') === 'color';
                        }),
                    Forms\Components\TagsInput::make('data.options_value')
                        ->placeholder('Add option')
                        ->visible(function (Forms\Get $get) {
                            return $get('data.type') === 'options';
                        }),
                    Forms\Components\Select::make('data.select_value')
                        ->options(config('libredesk.languages'))
                        ->visible(function (Forms\Get $get) {
                            return $get('data.type') === 'select' && $get('key') === 'language';
                        }),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key'),
                Tables\Columns\TextColumn::make('data.value'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSettings::route('/create'),
            'edit' => Pages\EditSettings::route('/{record}/edit'),
        ];
    }
}
