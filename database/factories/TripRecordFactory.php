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

        if (empty($users)) {
            $user = User::factory()->makeOne();
        }

        $user = $user ?? $users[array_rand($users)];
        $stations = Station::factory()->count(2)->create();

        $startTime = $this->faker->dateTimeThisYear();
        $endTime = clone $startTime;
        $endTime->modify(\sprintf('+%d seconds', $this->faker->numberBetween(300, 7200)));

        return [
            'user_id' => $user['id'],
            'start_station_id' => $stations[0]->id,
            'end_station_id' => $stations[1]->id,
            'started_at' => $startTime->format(DateTimeFormatter::ISO_DATETIME_BY_MINUTE),
            'ended_at' => $endTime->format(DateTimeFormatter::ISO_DATETIME_BY_MINUTE),
            'ride_type' => $this->faker->randomElement(RideType::cases()),
        ];
    }
}
