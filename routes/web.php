<?php

use App\Http\Controllers\TripController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

// Route::inertia('/', 'Welcome', [
//    'canRegister' => Features::enabled(Features::registration()),
// ])->name('home');

// Route::middleware(['auth', 'verified'])->group(function () {
//    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
// });

Route::get('/', [TripController::class, 'index'])->name('home');

Route::middleware(['auth'])->prefix('users')->group(function () {
    Route::put('/{user}/upgrade', [UserController::class, 'upgrade'])->name('upgrade');
});

require __DIR__.'/settings.php';
require __DIR__.'/trips.php';
