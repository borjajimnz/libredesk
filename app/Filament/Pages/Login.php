<?php

namespace App\Filament\Pages;

class Login extends \Filament\Pages\Auth\Login
{
    public function mount(): void
    {
        parent::mount();

        if (config('libredesk.demo') === false) {
            return;
        }

        $this->form->fill([
            'email' => 'admin@example.com',
            'password' => 'password',
            'remember' => true,
        ]);
    }
}
