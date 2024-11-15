<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', \App\Livewire\Welcome::class)
    ->middleware([\App\Http\Middleware\SetLanguage::class])
    ->name('welcome');


Route::get('/login', \Filament\Pages\Auth\Login::class)
    ->name('login');
Route::get('/logout', function () {
    Auth::logout();
    return redirect()->to(route('welcome'));
})->name('logout');

Route::get('/map/{roomId}/{date?}', \App\Livewire\Book::class)->middleware(['auth', \App\Http\Middleware\SetLanguage::class])->name('book');


Route::get('auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google-login');


Route::get('auth/google/callback', function () {
    $user = Socialite::driver('google')->stateless()->user();
    $googleId = $user->getId();
    $name = $user->getName();
    $email = $user->getEmail();

    // Verifica si el usuario ya existe
    $existingUser = User::query()->where('email', $email)->first();

    if ($existingUser) {
        // Si el usuario ya existe, lo logueamos
        Auth::login($existingUser);

        return redirect()->to(route('welcome'));
    } else {
        // Si no existe, lo creamos y luego lo logueamos
        $newUser = User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt(Str::random(16)), // Se genera una contraseña aleatoria segura
            // Puedes guardar el Google ID si es necesario
            'data' => [
                'google_id' => $googleId
            ]
        ]);

        Auth::login($newUser);
    }

    // Redirigir a la página deseada después del login
    return redirect(route('welcome'));
});
