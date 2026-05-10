<?php

use App\Enums\RideType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

test('update trip record request has required rules', function () {
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

test('update trip record request validates ride type enum values', function ($rideType) {
    $validator = Validator::make([
        'ride_type' => $rideType->value,
    ], [
        'ride_type' => ['required', Rule::in(RideType::cases())],
    ]);

    expect($validator->passes())->toBeTrue();
})->with(RideType::cases());

test('update trip record request rejects invalid ride type', function () {
    $validator = Validator::make([
        'ride_type' => 'invalid_type',
    ], [
        'ride_type' => ['required', Rule::in(RideType::cases())],
    ]);

    expect($validator->passes())->toBeFalse();
});

test('update trip record request validates end time after start time', function () {
    $validator = Validator::make([
        'started_at' => '2024-01-01 12:00:00',
        'ended_at' => '2024-01-01 11:00:00',
    ], [
        'ended_at' => ['required', 'date', 'after:started_at'],
    ]);

    expect($validator->passes())->toBeFalse();
});
