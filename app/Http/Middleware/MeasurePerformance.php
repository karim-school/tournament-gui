<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MeasurePerformance
{
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        $response = $next($request);

        $durationMs = (microtime(true) - $start) * 1000;

        Log::info('sli_response_time', [
            'uri' => $request->uri(),
            'method' => $request->method(),
            'duration_ms' => round($durationMs, 2),
        ]);

        $response->headers->set('X-Response-Time-Ms', round($durationMs, 2));

        return $response;
    }
}
