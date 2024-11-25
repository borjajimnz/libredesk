<?php

namespace App;

use App\Traits\Locale;

class Translate
{
    use Locale;

    public static function translate($data, $options = []): string
    {
        return self::t($data, $options);
    }
}
