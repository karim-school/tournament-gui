<?php

use App\Models\Station;
use App\Models\TripRecord;
use App\Models\User;
use Carbon\CarbonImmutable;

test('trip record belongs to user', function () {
    $user = User::factory()->create();
    $station = Station::factory()->create();

    $trip = TripRecord::factory()
        ->for($user, 'user')
        ->for($station, 'startStation')
        ->for($station, 'endStation')
        ->create();

    expect($trip->user)->toBeInstanceOf(User::class)
        ->and($trip->user->id)->toBe($user->id);
});

test('trip record belongs to start station', function () {
    $user = User::factory()->create();
    $station = Station::factory()->create();

    $trip = TripRecord::factory()
        ->for($user, 'user')
        ->for($station, 'startStation')
        ->for($station, 'endStation')
        ->create();

    expect($trip->startStation)->toBeInstanceOf(Station::class)
        ->and($trip->startStation->id)->toBe($station->id);
});

test('trip record belongs to end station', function () {
    $user = User::factory()->create();
    $station = Station::factory()->create();

    $trip = TripRecord::factory()
        ->for($user, 'user')
        ->for($station, 'startStation')
        ->for($station, 'endStation')
        ->create();

    expect($trip->endStation)->toBeInstanceOf(Station::class)
        ->and($trip->endStation->id)->toBe($station->id);
});

test('trip record has start station data attribute', function () {
    $user = User::factory()->create();
    $station = Station::factory()->create();

    $trip = TripRecord::factory()
        ->for($user, 'user')
        ->for($station, 'startStation')
        ->for($station, 'endStation')
        ->create();

    $data = $trip->start_station_data;

    expect($data)->toBeArray()
        ->and($data['id'])->toBe($station->id)
        ->and($data['name'])->toBe($station->name)
        ->and($data['location']['latitude'])->toBe($station->latitude)
        ->and($data['location']['longitude'])->toBe($station->longitude);
});

test('trip record has end station data attribute', function () {
    $user = User::factory()->create();
    $station = Station::factory()->create();

    $trip = TripRecord::factory()
        ->for($user, 'user')
        ->for($station, 'startStation')
        ->for($station, 'endStation')
        ->create();

    $data = $trip->end_station_data;

    expect($data)->toBeArray()
        ->and($data['id'])->toBe($station->id)
        ->and($data['name'])->toBe($station->name)
        ->and($data['location']['latitude'])->toBe($station->latitude)
        ->and($data['location']['longitude'])->toBe($station->longitude);
});

test('trip record casts started_at and ended_at as datetime', function () {
    $user = User::factory()->create();
    $station = Station::factory()->create();

    $trip = TripRecord::factory()
        ->for($user, 'user')
        ->for($station, 'startStation')
        ->for($station, 'endStation')
        ->create();

    expect($trip->started_at)->toBeInstanceOf(CarbonImmutable::class)
        ->and($trip->ended_at)->toBeInstanceOf(CarbonImmutable::class);
});

test('trip record has non-incrementing key', function () {
    $user = User::factory()->create();
    $station = Station::factory()->create();

    $trip = TripRecord::factory()
        ->for($user, 'user')
        ->for($station, 'startStation')
        ->for($station, 'endStation')
        ->create();

    expect($trip->incrementing)->toBeFalse()
        ->and($trip->getKeyType())->toBe('string');
});
