<?php

namespace App\Livewire;

use App\Filament\Resources\RoomResource\Pages\DeskBookings;
use App\Models\Desk;
use App\Models\DeskBooking;
use App\Models\Floor;
use App\Models\Place;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Support\Enums\ActionSize;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\View\View;
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
                                        ->unique(User::class, 'email', ignoreRecord: false)
                                        ->columnSpan(4),
                                    TextInput::make('password')
                                        ->password()
                                        ->revealable(true)
                                        ->columnSpan(4),
                                ]),
                            FileUpload::make('profile_photo_path')->columnSpan(1),
                        ]),


                    Actions::make([
                        Action::make('update')
                            ->action(function () use ($form) {
                                $form->fill($this->data);

                                $user = Auth::user();
                                $user->name = data_get($this, 'data.name');
                                $user->email = data_get($this, 'data.email');
                                $user->profile_photo_path = data_get($this, 'data.profile_photo_path');

                                if (data_get($this, 'data.password')) {
                                    $user->password = Hash::make(data_get($this, 'data.password'));
                                }

                                $user->save();

                                Notification::make()
                                    ->success()
                                    ->title(translate('updated_successfully'))
                                    ->send();
                            })
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
                    ])->columnSpan(6),
                ])->heading(translate('account_deletion'))
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
