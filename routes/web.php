<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\Welcome::class)->name('welcome');
Route::get('/login', \Filament\Pages\Auth\Login::class)->name('login');
Route::get('/logout', function () {
    Auth::logout();
    return redirect()->to(route('welcome'));
})->name('logout');
Route::get('/map/{roomId}/{date?}', \App\Livewire\Book::class)->middleware(['auth'])->name('book');
