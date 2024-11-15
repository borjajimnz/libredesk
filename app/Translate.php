<?php

namespace App;

use App\Traits\Locale;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Traits\Macroable;

class Translate
{
    use Locale;

    public static function translate($data, $options = []): string
    {
        return self::t($data, $options);
    }
}
