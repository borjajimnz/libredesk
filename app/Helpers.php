<?php

if (! function_exists('setting')) {
    function setting($key, $default = ''): mixed
    {
        try {
            return \App\Setting::get($key, $default);
        } catch (Exception $exception) {
            return $key;
        }
    }
}

if (! function_exists('translate')) {
    function translate($key, $options = []): string
    {
        return \App\Translate::translate($key, $options);
    }
}
