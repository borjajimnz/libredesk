<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Observers\SettingsObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([SettingsObserver::class])]
class Settings extends Model
{
    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    protected $guarded = [];
}
