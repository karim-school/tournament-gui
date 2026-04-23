<?php

namespace Database\Factories;

use App\Models\Station;
use App\Models\TripRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TripRecord>
 */
class TripRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start_time = fake()->dateTimeThisDecade()->getTimestamp();
        $end_time = $start_time + rand(600, 64800);
        $start_station = Station::factory()->make();
        return [
            'id' => mt_rand(PHP_INT_MAX >> 1, PHP_INT_MAX),
            'rideable_type' => 'electric_bike',
            'started_at' => $start_time,
            'ended_at' => $end_time,
            'start_station_id' => $start_station->id,
            'start_station_sub_id' => $start_station->sub_id,
            'end_station_id' => $start_station->id,
            'end_station_sub_id' => $start_station->sub_id,
            'start_location_latitude' => fake()->latitude(),
            'start_location_longitude' => fake()->longitude(),
            'end_location_latitude' => fake()->latitude(),
            'end_location_longitude' => fake()->longitude(),
            'member_casual' => 'member',
        ];
    }
}
