<?php

namespace App;

use App\Models\Settings;
use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Traits\Macroable;

class Setting
{
    use Macroable;
    public static function get($key, $default = ''): mixed
    {
        // Intentamos obtener el valor desde la caché
        $cachedValue = Cache::get('settings_' . $key);

        // Si el valor no está en caché, consultamos la base de datos
        if (!$cachedValue) {
            $setting = Settings::query()->where('key', $key)->first(); // Ajusta según tu modelo y columna de base de datos

            // Si encontramos el valor, lo guardamos en caché
            if ($setting) {
                $cachedValue = $setting->data['value']; // Asumiendo que el valor está en 'data.value'
                Cache::forever('settings_' . $key, $cachedValue);
            }
        }

        // Devuelvo el valor encontrado o el valor por defecto si no se encontró
        return $cachedValue ?? $default;
    }

    public static function getThemeColor(): array
    {
         return Color::all()[setting('theme_color')] ?? Color::Blue;
    }
}
