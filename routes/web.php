<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\Welcome::class)->name('welcome');
Route::get('/login', \Filament\Pages\Auth\Login::class)->name('login');
Route::get('/book', \App\Livewire\Book::class)->middleware(['auth'])->name('book');
