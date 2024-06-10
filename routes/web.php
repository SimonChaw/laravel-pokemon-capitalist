<?php

use App\Http\Controllers\PokemonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;



Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // WILD AREA - GAME!
    Route::get('/pokemon/encounter', [PokemonController::class, 'encounter'])->name('pokemon.encounter');
    Route::post('/pokemon/catch', [PokemonController::class, 'catch'])->name('pokemon.catch');

    // SHOP!
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::post('/shop/buy/{item_id}', [ShopController::class, 'buy'])->name('shop.buy');
    Route::post('/shop/sell/{pokemon_id}', [ShopController::class, 'sell'])->name('shop.sell');

    // EDIT PROFILE!
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
