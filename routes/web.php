<?php

use App\Http\Controllers\TripController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'index'])->name('home');

Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/{user}', [UserController::class, 'view'])->name('view');
    Route::post('/', [UserController::class, 'add'])->name('add');
    Route::delete('/{user}', [UserController::class, 'delete'])->name('delete');
});

Route::resource('trips', TripController::class)->only(['index', 'show']);
