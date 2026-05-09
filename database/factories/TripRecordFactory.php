<?php

namespace Database\Factories;

use App\Enums\RideType;
use App\Helpers\DateTimeFormatter;
use App\Models\Station;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TripRecordFactory extends Factory
{
    public function definition(): array
    {
        $users = User::all()->toArray();
        $stations = Station::all()->toArray();

        if (empty($users)) {
            $user = User::factory()->makeOne();
        }

        if (empty($stations)) {
            $stations = Station::factory()->count(10)->make()->toArray();
        }

        $user = $user ?? $users[array_rand($users)];
        $startStation = $stations[array_rand($stations)];
        $endStation = $stations[array_rand($stations)];

        while ($endStation['id'] === $startStation['id'] && \count($stations) > 1) {
            $endStation = $stations[array_rand($stations)];
        }

        $startTime = $this->faker->dateTimeThisYear();
        $endTime = clone $startTime;
        $endTime->modify(\sprintf('+%d seconds', $this->faker->numberBetween(300, 7200)));

        return [
            'user_id' => $user['id'],
            'start_station_id' => $startStation['id'],
            'end_station_id' => $endStation['id'],
            'started_at' => $startTime->format(DateTimeFormatter::ISO_DATETIME_BY_MINUTE),
            'ended_at' => $endTime->format(DateTimeFormatter::ISO_DATETIME_BY_MINUTE),
            'ride_type' => $this->faker->randomElement(RideType::cases()),
        ];
    }
}
