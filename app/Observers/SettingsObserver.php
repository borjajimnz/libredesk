<?php

namespace App\Observers;

use App\Models\Settings;
use Illuminate\Support\Facades\Cache;

class SettingsObserver
{
    /**
     * Handle the Settings "created" event.
     */
    public function created(Settings $settings): void
    {
        try {
            Cache::delete('settings_'.$settings->key);
        } catch (\Exception $exception) {

        }
    }

    /**
     * Handle the Settings "updated" event.
     */
    public function updated(Settings $settings): void
    {
        Cache::delete('settings_'.$settings->key);
    }

    /**
     * Handle the Settings "deleted" event.
     */
    public function deleting(Settings $settings): void
    {
        Cache::delete('settings_'.$settings->key);
    }
}
