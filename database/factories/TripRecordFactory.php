<?php

namespace Database\Factories;

use App\Models\Station;
use Illuminate\Database\Eloquent\Factories\Factory;

class TripRecordFactory extends Factory
{
    public function definition(): array
    {
        $stations = Station::all()->toArray();

        if (empty($stations)) {
            $stations = [
                ['id' => 1, 'sub_id' => 0, 'name' => 'Central Station'],
                ['id' => 2, 'sub_id' => 0, 'name' => 'North Station'],
                ['id' => 3, 'sub_id' => 0, 'name' => 'South Station'],
                ['id' => 4, 'sub_id' => 0, 'name' => 'East Station'],
            ];
        }

        $startStation = $stations[array_rand($stations)];
        $endStation = $stations[array_rand($stations)];

        while ($endStation['id'] === $startStation['id'] && \count($stations) > 1) {
            $endStation = $stations[array_rand($stations)];
        }

        $startTime = $this->faker->dateTimeThisYear();
        $endTime = clone $startTime;
        $endTime->modify(\sprintf('+%d seconds', $this->faker->numberBetween(300, 7200)));

        return [
            'id' => $this->faker->unique()->numberBetween(PHP_INT_MAX >> 1, PHP_INT_MAX),
            'rideable_type' => $this->faker->randomElement(['electric_bike', 'classic_bike']),
            'started_at' => $startTime->format('Y-m-d H:i:s'),
            'ended_at' => $endTime->format('Y-m-d H:i:s'),
            'start_station_id' => $startStation['id'],
            'start_station_sub_id' => $startStation['sub_id'],
            'end_station_id' => $endStation['id'],
            'end_station_sub_id' => $endStation['sub_id'],
            'member_casual' => $this->faker->randomElement(['member', 'casual']),
        ];
    }
}
