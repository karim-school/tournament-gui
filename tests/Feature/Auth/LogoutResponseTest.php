<?php

use App\Models\Station;
use App\Models\TripRecord;
use App\Models\User;

test('logout redirects to fortify home when previous URL was protected', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('trips.create'));

    $response = $this->post(route('logout'));

    $response->assertRedirect(config('fortify.home'));
});

test('logout redirects back to previous URL when it was not protected', function () {
    $user = User::factory()->create();
    $station = Station::factory()->create();
    $otherStation = Station::factory()->create();

    $trip = TripRecord::factory()
        ->for($user, 'user')
        ->for($station, 'startStation')
        ->for($otherStation, 'endStation')
        ->create();

    $response = $this->actingAs($user)
        ->from(route('trips.show', $trip))
        ->post(route('logout'));

    $response->assertRedirect(route('trips.show', $trip));
});
