<?php

namespace Database\Factories;

use App\Models\Station;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Station>
 */
class StationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->streetName(),
            'latitude' => $this->faker->latitude(54.7, 57.2),
            'longitude' => $this->faker->longitude(8.2, 12.5),
        ];
    }

    protected function withFaker()
    {
        return fake('da_DK');
    }
}
