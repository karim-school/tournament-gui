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

        $timeMs = (microtime(true) - $start) * 1000;

        Log::info('sli_response_time', [
            'uri' => $request->uri()->toString(),
            'method' => $request->method(),
            'time_ms' => round($timeMs, 2),
        ]);

        $response->headers->set('X-Response-Time-Ms', round($timeMs, 2));

        return $response;
    }
}
