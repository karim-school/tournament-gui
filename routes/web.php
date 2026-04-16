<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::inertia('/', 'RankList')->name('ranklist');

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'add']);
