<?php

namespace App\Livewire;

use App\Models\User;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Profile extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public $image;

    public $roomId;

    public $date;

    public function mount()
    {
        $profile = Auth::user();
        $this->data = $profile->toArray();
        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Section::make([
                    Grid::make(2)
                        ->schema([
                            Grid::make(4)
                                ->columnSpan(1)
                                ->schema([
                                    TextInput::make('name')->columnSpan(4),
                                    TextInput::make('email')
                                        ->email(true)
                                        ->unique(User::class, 'email', ignorable: Auth::user())
                                        ->columnSpan(4),
                                    TextInput::make('password')
                                        ->hidden(config('app.env') === 'demo')
                                        ->password()
                                        ->revealable(true)
                                        ->columnSpan(4),
                                ]),
                            FileUpload::make('profile_photo_path')
                                ->image() // Si es una imagen
                                ->disk('public')
                                ->columnSpan(1),
                        ]),

                    Actions::make([
                        Action::make('update')
                            ->action(function () use ($form) {
                                $state = $this->form($form)->getState();
                                $form->fill($state);

                                $user = Auth::user();
                                $user->name = data_get($state, 'name');
                                $user->email = data_get($state, 'email');
                                $user->profile_photo_path = data_get($state, 'profile_photo_path');

                                if (data_get($this, 'data.password')) {
                                    $user->password = Hash::make(data_get($state, 'data.password'));
                                }

                                $user->save();

                                Notification::make()
                                    ->success()
                                    ->title(translate('updated_successfully'))
                                    ->send();
                            }),
                    ]),
                ])->heading(translate('profile_deltails')),
                Section::make([
                    TextInput::make('email_confirm')
                        ->helperText(translate('confirm_your_email'))
                        ->columnSpan(6)
                        ->inlineLabel(true),
                    Actions::make([
                        Action::make('delete')
                            ->label(translate('delete'))
                            ->color('danger')
                            ->requiresConfirmation()
                            ->action(function () {
                                $user = Auth::user();
                                if (data_get($this, 'data.email_confirm') !== null && data_get($this, 'data.email_confirm') === $user->email) {
                                    $user->delete();

                                    Notification::make()
                                        ->success()
                                        ->title(translate('deleted_successfully'))
                                        ->send();
                                } else {
                                    Notification::make()
                                        ->danger()
                                        ->title(translate('email_not_match'))
                                        ->send();
                                }
                            }),
                    ])->columnSpan(6),
                ])
                    ->hidden(config('app.env') === 'demo')
                    ->heading(translate('account_deletion'))
                    ->columns(12),
            ])
            ->statePath('data');
    }

    public function update()
    {
    }

    public function render()
    {
        return view('livewire.profile');
    }
}
