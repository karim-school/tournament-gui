<?php

namespace Database\Seeders;

use App\Enums\Membership;
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
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'membership' => Membership::GUEST,
            'is_admin' => true,
        ]);

        User::factory(10)->create();

        Station::factory()->count(5)->create();

        TripRecord::factory()->count(75)->create();
    }
}
