<?php

use App\Http\Resources\TripRecordResource;
use App\Models\Station;
use App\Models\TripRecord;
use App\Models\User;
use Illuminate\Http\Request;

test('trip record resource transforms to array', function () {
    $user = User::factory()->create();
    $station = Station::factory()->create();

    $trip = TripRecord::factory()
        ->for($user, 'user')
        ->for($station, 'startStation')
        ->for($station, 'endStation')
        ->create();

    $resource = new TripRecordResource($trip);
    $array = $resource->toArray(new Request);

    expect($array)->toHaveKeys(['id', 'user', 'start_station', 'end_station', 'started_at', 'ended_at', 'ride_type'])
        ->and($array['id'])->toBe((string) $trip->id)
        ->and($array['started_at'])->toBe($trip->started_at->getTimestamp() * 1000)
        ->and($array['ended_at'])->toBe($trip->ended_at->getTimestamp() * 1000);
});

test('trip record resource includes user resource', function () {
    $user = User::factory()->create();
    $station = Station::factory()->create();

    $trip = TripRecord::factory()
        ->for($user, 'user')
        ->for($station, 'startStation')
        ->for($station, 'endStation')
        ->create();

    $resource = new TripRecordResource($trip);
    $array = $resource->toArray(new Request);

    expect($array['user'])->toHaveKeys(['id', 'name', 'email', 'membership']);
});

test('trip record resource includes station data', function () {
    $user = User::factory()->create();
    $station = Station::factory()->create();

    $trip = TripRecord::factory()
        ->for($user, 'user')
        ->for($station, 'startStation')
        ->for($station, 'endStation')
        ->create();

    $resource = new TripRecordResource($trip);
    $array = $resource->toArray(new Request);

    expect($array['start_station'])->toHaveKeys(['id', 'name', 'location'])
        ->and($array['start_station']['location'])->toHaveKeys(['latitude', 'longitude'])
        ->and($array['end_station'])->toHaveKeys(['id', 'name', 'location']);
});
