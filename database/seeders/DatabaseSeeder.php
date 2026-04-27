<?php

namespace Database\Seeders;

use App\Models\Station;
use App\Models\TripRecord;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Station::factory()
            ->forEachSequence(
                ['id' => 1],
                ['id' => 2],
                ['id' => 3],
                ['id' => 4],
                ['id' => 4, 'sub_id' => 1],
            )->create();

        TripRecord::factory()->count(10)->create();
    }
}
