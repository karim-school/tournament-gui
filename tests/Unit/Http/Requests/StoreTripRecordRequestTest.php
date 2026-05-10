<?php

use App\Enums\RideType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

test('store trip record request has required rules', function () {
    $validator = Validator::make([
        'ride_type' => RideType::CLASSIC_BIKE->value,
        'started_at' => '2024-01-01 10:00:00',
        'ended_at' => '2024-01-01 11:00:00',
        'start_station_id' => 1,
        'end_station_id' => 1,
    ], [
        'ride_type' => ['required', 'string'],
        'started_at' => ['required', 'date'],
        'ended_at' => ['required', 'date'],
        'start_station_id' => ['required', 'integer'],
        'end_station_id' => ['required', 'integer'],
    ]);

    expect($validator->passes())->toBeTrue();
});

test('store trip record request validates ride type enum values', function ($rideType) {
    $validator = Validator::make([
        'ride_type' => $rideType->value,
    ], [
        'ride_type' => ['required', Rule::in(RideType::cases())],
    ]);

    expect($validator->passes())->toBeTrue();
})->with(RideType::cases());

test('store trip record request rejects invalid ride type', function () {
    $validator = Validator::make([
        'ride_type' => 'invalid_type',
    ], [
        'ride_type' => ['required', Rule::in(RideType::cases())],
    ]);

    expect($validator->passes())->toBeFalse();
});

test('store trip record request validates start time before end time', function () {
    $validator = Validator::make([
        'started_at' => '2024-01-01 12:00:00',
        'ended_at' => '2024-01-01 11:00:00',
    ], [
        'started_at' => ['required', 'date', 'before:ended_at'],
        'ended_at' => ['required', 'date', 'after:started_at'],
    ]);

    expect($validator->passes())->toBeFalse();
});

test('store trip record request validates stations exist', function () {
    $validator = Validator::make([
        'start_station_id' => 99999,
        'end_station_id' => 99999,
    ], [
        'start_station_id' => ['required', 'integer', 'exists:stations,id'],
        'end_station_id' => ['required', 'integer', 'exists:stations,id'],
    ]);

    expect($validator->passes())->toBeFalse();
});
