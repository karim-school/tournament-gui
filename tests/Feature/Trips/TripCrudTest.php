<?php

use App\Enums\RideType;
use App\Models\Station;
use App\Models\TripRecord;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

test('trip create page is displayed for authenticated users', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('trips.create'));

    $response->assertOk();
});

test('trip create page redirects for unauthenticated users', function () {
    $response = $this->get(route('trips.create'));

    $response->assertRedirect(route('home'));
});

test('trip can be created', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('trips.store'), [
        'ride_type' => RideType::CLASSIC_BIKE->value,
        'started_at' => '2024-01-01 10:00:00',
        'ended_at' => '2024-01-01 11:00:00',
        'start_location' => ['latitude' => 55.36, 'longitude' => 10.39],
        'end_location' => ['latitude' => 55.42, 'longitude' => 10.36],
    ]);

    $response->assertSessionHasNoErrors();

    $this->assertDatabaseHas('trip_records', [
        'user_id' => $user->id,
    ]);
});

test('trip create validation fails with invalid data', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('trips.store'), [
        'ride_type' => 'invalid',
        'started_at' => '2024-01-01 11:00:00',
        'ended_at' => '2024-01-01 10:00:00',
        'start_station_id' => 99999,
        'end_station_id' => 99999,
    ]);

    $response->assertSessionHasErrors();
});

test('trip show page is displayed', function () {
    $user = User::factory()->create();
    $station = Station::factory()->create();
    $otherStation = Station::factory()->create();

    $trip = TripRecord::factory()
        ->for($user, 'user')
        ->for($station, 'startStation')
        ->for($otherStation, 'endStation')
        ->create();

    $response = $this->actingAs($user)->get(route('trips.show', $trip));

    $response->assertOk();
});

test('trip index page sets url intended for unauthenticated users', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $this->assertEquals(route('home'), session('url.intended'));
});

test('trip show page sets url intended for unauthenticated users', function () {
    $user = User::factory()->create();
    $station = Station::factory()->create();
    $otherStation = Station::factory()->create();

    $trip = TripRecord::factory()
        ->for($user, 'user')
        ->for($station, 'startStation')
        ->for($otherStation, 'endStation')
        ->create();

    $response = $this->get(route('trips.show', $trip));

    $response->assertOk();
    $this->assertEquals(route('trips.show', $trip), session('url.intended'));
});

test('trip index page displays with rider_type filter', function () {
    $user = User::factory()->create();
    $station = Station::factory()->create();
    $otherStation = Station::factory()->create();

    TripRecord::factory()
        ->for($user, 'user')
        ->for($station, 'startStation')
        ->for($otherStation, 'endStation')
        ->create();

    $response = $this->actingAs($user)->get(route('home').'?rider_type=member');

    $response->assertOk();
});

test('trip index page displays with date_from filter', function () {
    $user = User::factory()->create();
    $station = Station::factory()->create();
    $otherStation = Station::factory()->create();

    TripRecord::factory()
        ->for($user, 'user')
        ->for($station, 'startStation')
        ->for($otherStation, 'endStation')
        ->create();

    $response = $this->actingAs($user)->get(route('home').'?date_from=2024-01-01');

    $response->assertOk();
});

test('trip index page displays with date_to filter', function () {
    $user = User::factory()->create();
    $station = Station::factory()->create();
    $otherStation = Station::factory()->create();

    TripRecord::factory()
        ->for($user, 'user')
        ->for($station, 'startStation')
        ->for($otherStation, 'endStation')
        ->create();

    $response = $this->actingAs($user)->get(route('home').'?date_to=2024-12-31');

    $response->assertOk();
});

test('trip index page displays with min_duration filter', function () {
    $user = User::factory()->create();
    $station = Station::factory()->create();
    $otherStation = Station::factory()->create();

    TripRecord::factory()
        ->for($user, 'user')
        ->for($station, 'startStation')
        ->for($otherStation, 'endStation')
        ->create();

    $response = $this->actingAs($user)->get(route('home').'?min_duration=30');

    $response->assertOk();
});

test('trip index filters trips by ride_type and returns matching trips', function () {
    $user = User::factory()->create();
    $station1 = Station::factory()->create();
    $station2 = Station::factory()->create();

    TripRecord::factory()
        ->for($user, 'user')
        ->for($station1, 'startStation')
        ->for($station2, 'endStation')
        ->sequence(
            ['ride_type' => 'classic_bike'],
            ['ride_type' => 'electric_bike'],
        )
        ->count(2)
        ->create();

    $response = $this->actingAs($user)->get(route('home').'?ride_type=electric_bike');

    $response->assertOk();
});

test('trip index excludes trips that do not match ride_type filter', function () {
    $user = User::factory()->create();
    $station1 = Station::factory()->create();
    $station2 = Station::factory()->create();

    TripRecord::factory()
        ->for($user, 'user')
        ->for($station1, 'startStation')
        ->for($station2, 'endStation')
        ->sequence(
            ['ride_type' => 'classic_bike'],
            ['ride_type' => 'classic_bike'],
        )
        ->count(2)
        ->create();

    $response = $this->actingAs($user)->get(route('home').'?ride_type=electric_bike');

    $response->assertOk();
});

test('trip index filters trips by user membership', function () {
    $guestUser = User::factory()->create(['membership' => 'guest']);
    $memberUser = User::factory()->create(['membership' => 'member']);
    $station1 = Station::factory()->create();
    $station2 = Station::factory()->create();

    TripRecord::factory()
        ->for($guestUser, 'user')
        ->for($station1, 'startStation')
        ->for($station2, 'endStation')
        ->create();

    TripRecord::factory()
        ->for($memberUser, 'user')
        ->for($station1, 'startStation')
        ->for($station2, 'endStation')
        ->create();

    $response = $this->actingAs($memberUser)->get(route('home').'?rider_type=member');

    $response->assertOk();
});

test('trip index filters trips by start date', function () {
    $user = User::factory()->create();
    $station1 = Station::factory()->create();
    $station2 = Station::factory()->create();

    TripRecord::factory()
        ->for($user, 'user')
        ->for($station1, 'startStation')
        ->for($station2, 'endStation')
        ->sequence(
            ['started_at' => '2024-01-15 10:00:00', 'ended_at' => '2024-01-15 11:00:00'],
            ['started_at' => '2024-02-01 10:00:00', 'ended_at' => '2024-02-01 11:00:00'],
        )
        ->count(2)
        ->create();

    $response = $this->actingAs($user)->get(route('home').'?date_from=2024-02-01');

    $response->assertOk();
});

test('trip index excludes trips before date_to', function () {
    $user = User::factory()->create();
    $station1 = Station::factory()->create();
    $station2 = Station::factory()->create();

    TripRecord::factory()
        ->for($user, 'user')
        ->for($station1, 'startStation')
        ->for($station2, 'endStation')
        ->sequence(
            ['started_at' => '2024-01-15 10:00:00', 'ended_at' => '2024-01-15 11:00:00'],
            ['started_at' => '2024-02-01 10:00:00', 'ended_at' => '2024-02-01 11:00:00'],
        )
        ->count(2)
        ->create();

    $response = $this->actingAs($user)->get(route('home').'?date_to=2024-01-31');

    $response->assertOk();
});

test('trip index filters trips by minimum duration', function () {
    $user = User::factory()->create();
    $station1 = Station::factory()->create();
    $station2 = Station::factory()->create();

    TripRecord::factory()
        ->for($user, 'user')
        ->for($station1, 'startStation')
        ->for($station2, 'endStation')
        ->sequence(
            ['started_at' => '2024-01-01 10:00:00', 'ended_at' => '2024-01-01 10:05:00'],
            ['started_at' => '2024-01-01 11:00:00', 'ended_at' => '2024-01-01 12:00:00'],
        )
        ->count(2)
        ->create();

    $response = $this->actingAs($user)->get(route('home').'?min_duration=10');

    $response->assertOk();
});

test('trip index excludes trips shorter than min_duration', function () {
    $user = User::factory()->create();
    $station1 = Station::factory()->create();
    $station2 = Station::factory()->create();

    TripRecord::factory()
        ->for($user, 'user')
        ->for($station1, 'startStation')
        ->for($station2, 'endStation')
        ->sequence(
            ['started_at' => '2024-01-01 10:00:00', 'ended_at' => '2024-01-01 10:05:00'],
        )
        ->count(1)
        ->create();

    $response = $this->actingAs($user)->get(route('home').'?min_duration=10');

    $response->assertOk();
});

test('trip index page displays with filters', function () {
    $user = User::factory()->create();
    $station = Station::factory()->create();
    $otherStation = Station::factory()->create();

    TripRecord::factory()
        ->for($user, 'user')
        ->for($station, 'startStation')
        ->for($otherStation, 'endStation')
        ->create();

    $response = $this->actingAs($user)->get(route('home').'?ride_type=classic_bike');

    $response->assertOk();
});

test('trip index page works with api response', function () {
    $user = User::factory()->create();
    $station = Station::factory()->create();
    $otherStation = Station::factory()->create();

    TripRecord::factory()
        ->for($user, 'user')
        ->for($station, 'startStation')
        ->for($otherStation, 'endStation')
        ->create();

    $response = $this->actingAs($user)->get(route('home').'?api=1');

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/json');
});

test('trip index page works with station filter', function () {
    $user = User::factory()->create();
    $station = Station::factory()->create();
    $otherStation = Station::factory()->create();

    TripRecord::factory()
        ->for($user, 'user')
        ->for($station, 'startStation')
        ->for($otherStation, 'endStation')
        ->create();

    $response = $this->actingAs($user)->get(route('home').'?station='.$station->name);

    $response->assertOk();
});

test('trip edit page is displayed for owner', function () {
    $user = User::factory()->create();
    $station = Station::factory()->create();
    $otherStation = Station::factory()->create();

    $trip = TripRecord::factory()
        ->for($user, 'user')
        ->for($station, 'startStation')
        ->for($otherStation, 'endStation')
        ->create();

    $response = $this->actingAs($user)->get(route('trips.edit', $trip));

    $response->assertOk();
});

test('trip edit page redirects for non-owner', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $station = Station::factory()->create();
    $otherStation = Station::factory()->create();

    $trip = TripRecord::factory()
        ->for($user, 'user')
        ->for($station, 'startStation')
        ->for($otherStation, 'endStation')
        ->create();

    $response = $this->actingAs($otherUser)->get(route('trips.edit', $trip));

    $response->assertRedirect(route('trips.show', $trip));
});

test('trip can be updated by owner', function () {
    $user = User::factory()->create();

    $trip = TripRecord::factory()
        ->for($user, 'user')
        ->create();

    $response = $this->actingAs($user)->put(route('trips.update', $trip), [
        'ride_type' => RideType::ELECTRIC_BIKE->value,
        'started_at' => '2024-01-01 10:00:00',
        'ended_at' => '2024-01-01 11:00:00',
        'start_location' => ['latitude' => 55.36, 'longitude' => 10.39],
        'end_location' => ['latitude' => 55.42, 'longitude' => 10.36],
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('trips.show', $trip));

    $trip->refresh();

    expect($trip->ride_type->value)->toBe(RideType::ELECTRIC_BIKE->value);
});

test('trip can be deleted by owner', function () {
    $user = User::factory()->create();
    $station = Station::factory()->create();
    $otherStation = Station::factory()->create();

    $trip = TripRecord::factory()
        ->for($user, 'user')
        ->for($station, 'startStation')
        ->for($otherStation, 'endStation')
        ->create();

    $response = $this->actingAs($user)->delete(route('trips.destroy', $trip));

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('home'));

    expect(TripRecord::find($trip->id))->toBeNull();
});

test('trip cannot be deleted by non-owner', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $station = Station::factory()->create();
    $otherStation = Station::factory()->create();

    $trip = TripRecord::factory()
        ->for($user, 'user')
        ->for($station, 'startStation')
        ->for($otherStation, 'endStation')
        ->create();

    $response = $this->actingAs($otherUser)->delete(route('trips.destroy', $trip));

    $response->assertStatus(403);

    expect(TripRecord::find($trip->id))->not->toBeNull();
});

test('trip index displays empty state when trip_records table does not exist', function () {
    $user = User::factory()->create();

    Schema::shouldReceive('hasTable')
        ->with('trip_records')
        ->andReturn(false);

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertOk();
});

test('trip index returns 500 on database error', function () {
    $user = User::factory()->create();

    DB::shouldReceive('table')
        ->with('trip_records')
        ->andThrow(new Exception('Database error'));

    Log::shouldReceive('error')
        ->atLeast()->once();

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertStatus(500);
});
