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
        $start_time = $this->faker->dateTimeThisDecade()->getTimestamp();
        $end_time = $start_time + mt_rand(600, 64800);
        $stations = Station::all(['id', 'sub_id'])->toArray();
        $start_station = $stations[array_rand($stations)];
        $end_station = $stations[array_rand($stations)];

        return [
            'id' => $this->faker->unique()->numberbetween(PHP_INT_MAX >> 1, PHP_INT_MAX),
            'rideable_type' => 'electric_bike',
            'started_at' => $start_time,
            'ended_at' => $end_time,
            'start_station_id' => $start_station['id'],
            'start_station_sub_id' => $start_station['sub_id'],
            'end_station_id' => $end_station['id'],
            'end_station_sub_id' => $end_station['sub_id'],
            'start_location_latitude' => $this->faker->latitude(),
            'start_location_longitude' => $this->faker->longitude(),
            'end_location_latitude' => $this->faker->latitude(),
            'end_location_longitude' => $this->faker->longitude(),
            'member_casual' => 'member',
        ];
    }
}
