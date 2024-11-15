<?php

namespace App\Traits;

trait Locale
{
    public static function t($name, $options = []): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __( 'app.' . $name, $options);
    }
}
