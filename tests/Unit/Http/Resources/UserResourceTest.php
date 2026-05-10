<?php

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

test('user resource transforms to array', function () {
    $user = User::factory()->create();

    $resource = new UserResource($user);
    $array = $resource->toArray(new Request);

    expect($array)->toHaveKeys(['id', 'name', 'email', 'avatar', 'email_verified_at', 'created_at', 'updated_at', 'membership'])
        ->and($array['id'])->toBe($user->id)
        ->and($array['name'])->toBe($user->name)
        ->and($array['email'])->toBe($user->email);
});

test('user resource includes membership', function () {
    $user = User::factory()->create();

    $resource = new UserResource($user);
    $array = $resource->toArray(new Request);

    expect($array['membership'])->toBe($user->membership);
});

test('user resource includes timestamps', function () {
    $user = User::factory()->create();

    $resource = new UserResource($user);
    $array = $resource->toArray(new Request);

    expect($array['created_at'])->toEqual($user->created_at)
        ->and($array['updated_at'])->toEqual($user->updated_at);
});
