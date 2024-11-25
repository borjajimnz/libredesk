<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Welcome extends Component
{
    public $bookings = [];

    public $name = null;

    public function mount()
    {
        if (Auth::check()) {
            $user = Auth::user()->load('bookings');
            $this->name = ucfirst($user->name);
            $this->bookings = $user->bookings->toArray();
        }
    }

    public function render()
    {
        return view('livewire.welcome');
    }
}
