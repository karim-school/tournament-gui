<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;

class LogoutResponse implements LogoutResponseContract
{
    public function toResponse($request)
    {
        $previousUrl = url()->previous();

        // Get the route name or action for the previous URL
        $route = Route::getRoutes()->match(request()->create($previousUrl));

        // Check if the previous route had 'auth' middleware
        $isSecure = \in_array('auth', $route->gatherMiddleware());

        if (! $isSecure) {
            return redirect($previousUrl);
        }

        // Fallback for secure pages (don't send them back to a wall)
        return redirect(config('fortify.home'));
    }
}
