<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', [UserController::class, 'index'])->name('home');
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/{user}', [UserController::class, 'view'])->name('users.view');
Route::post('/users', [UserController::class, 'add'])->name('users.add');
Route::delete('/users/{user}', [UserController::class, 'delete'])->name('users.delete');
