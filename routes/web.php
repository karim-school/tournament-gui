<?php

use App\Http\Controllers\TripController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TripController::class, 'index'])->name('home');

Route::resource('trips', TripController::class)->only(['index', 'show']);
