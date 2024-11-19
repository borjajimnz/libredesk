<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use App\Observers\SettingsObserver;
use App\Observers\UserObserver;
use App\Providers\Filament\AdminPanelProvider;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use TomatoPHP\FilamentSocial\Traits\InteractsWithSocials;

#[ObservedBy([UserObserver::class])]

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'data',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'data' => 'array',
            'role' => UserRole::class,
        ];
    }

    protected $appends = [
        'Admin'
    ];

    public function bookings()
    {
        return $this->hasMany(DeskBooking::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->role === UserRole::User && $panel->getId() === 'admin') {
            return false;
        }

        return true;
    }

    public function getAdminAttribute()
    {
        return $this->role === UserRole::Admin;
    }
}
