<?php

namespace App\Observers;

use App\Models\User;
use Filament\Notifications\Notification;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function creating(User $user)
    {
        $allowedDomains = setting('allowed_register_domains');
        try {
            if (is_array($allowedDomains) && !empty($allowedDomains)) {
                $emailDomain = substr(strrchr($user->email, "@"), 1);
                if (!in_array($emailDomain, $allowedDomains)) {
                    throw new \Exception(translate('domain_not_allowed'));
                }
            }
        } catch (\Exception $exception) {
            Notification::make()
                ->danger()
                ->title($exception->getMessage())
                ->send();

            return false;
        }
    }


    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
