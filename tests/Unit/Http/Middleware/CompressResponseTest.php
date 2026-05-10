<?php

use App\Http\Middleware\CompressResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    $this->middleware = new CompressResponse;
});

test('compress response middleware passes through when no accept encoding header', function () {
    $request = Request::create('/test');
    $response = new Response('test content');

    $next = fn ($req) => $response;

    $result = $this->middleware->handle($request, $next);

    expect($result->getContent())->toBe('test content')
        ->and($result->headers->has('Content-Encoding'))
        ->toBeFalse();
});

test('compress response middleware compresses with gzip', function () {
    $request = Request::create('/test');
    $request->headers->set('Accept-Encoding', 'gzip');
    $response = new Response('test content');

    $next = fn ($req) => $response;

    $result = $this->middleware->handle($request, $next);

    expect($result->headers->get('Content-Encoding'))->toBe('gzip');
});

test('compress response middleware does not compress without gzip', function () {
    $request = Request::create('/test');
    $request->headers->set('Accept-Encoding', 'deflate');
    $response = new Response('test content');

    $next = fn ($req) => $response;

    $result = $this->middleware->handle($request, $next);

    expect($result->headers->has('Content-Encoding'))->toBeFalse();
});

test('compress response middleware prefers gzip over other encodings', function () {
    $request = Request::create('/test');
    $request->headers->set('Accept-Encoding', 'deflate, gzip, br');
    $response = new Response('test content');

    $next = fn ($req) => $response;

    $result = $this->middleware->handle($request, $next);

    expect($result->headers->get('Content-Encoding'))->toBe('gzip');
});
