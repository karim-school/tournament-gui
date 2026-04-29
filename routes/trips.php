<?php

use App\Http\Controllers\TripController;

Route::resource('trips', TripController::class)->only(['index', 'show', 'create', 'store'])->withoutMiddleware(['auth']);
