<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompressResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!$request->hasHeader('Accept-Encoding')) {
            return $response;
        }

        $acceptedEncodings = preg_split(
            '/,\\s*/',
            $request->header('Accept-Encoding'),
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        if (in_array('gzip', $acceptedEncodings)) {
            $response->setContent(gzencode($response->getContent()));
            $response->headers->set('Content-Encoding', 'gzip');
        }

        return $response;
    }
}
