<?php

if (!function_exists('setting')) {
    function setting($key, $default = ''): mixed
    {
        return \App\Setting::get($key, $default);
    }
}

if (!function_exists('translate')) {
    function translate($key, $options = []): string
    {
        return \App\Translate::translate($key, $options);
    }
}
