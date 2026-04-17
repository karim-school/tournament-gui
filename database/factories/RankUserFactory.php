<?php

namespace Database\Factories;

use App\Models\RankUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RankUser>
 */
class RankUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $games = rand(1, 100);
        $wins = rand(0, $games);
        $points = 3 * $wins + ($games - $wins);
        return [
            'name' => fake('da_DK')->name(),
            'points' => $points,
            'wins' => $wins,
            'games' => $games,
        ];
    }
}
