<?php

use App\Http\Controllers\TripController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

// Route::inertia('/', 'Welcome', [
//    'canRegister' => Features::enabled(Features::registration()),
// ])->name('home');

// Route::middleware(['auth', 'verified'])->group(function () {
//    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
// });

Route::get('/', [TripController::class, 'index'])->name('home');

require __DIR__.'/settings.php';
require __DIR__.'/trips.php';
