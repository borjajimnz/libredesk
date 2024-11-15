<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasLabel
{
    case Admin = 'admin';
    case User = 'user';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::User => 'User',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Admin => 'purple',
            self::User => 'success',
        };
    }
}
