<?php

use App\Http\Controllers\TripController;

Route::resource('trips', TripController::class)->only(['show', 'create', 'store']);
