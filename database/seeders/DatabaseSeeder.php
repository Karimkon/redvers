<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Station;
use App\Models\Battery;

use Database\Seeders\MotorcycleSeeder; // Add this at the top if not already imported

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create stations first
        $station1 = Station::create([
            'name' => 'Station A',
            'location' => 'Kampala',
            'latitude' => 0.3136,
            'longitude' => 32.5811,
        ]);

        $station2 = Station::create([
            'name' => 'Station B',
            'location' => 'Ntinda',
            'latitude' => 0.3530,
            'longitude' => 32.6123,
        ]);

        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@redvers.com',                 
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Agents (AFTER stations are created)
        User::create([
            'name' => 'Agent One',
            'email' => 'agent1@redvers.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'station_id' => $station1->id,
        ]);

        User::create([
            'name' => 'Agent Two',
            'email' => 'agent2@redvers.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'station_id' => $station2->id,
        ]);

        // Riders
        User::create([
            'name' => 'Rider One',
            'email' => 'rider1@redvers.com',
            'password' => Hash::make('password'),
            'role' => 'rider',
        ]);

        User::create([
            'name' => 'Rider Two',
            'email' => 'rider2@redvers.com',
            'password' => Hash::make('password'),
            'role' => 'rider',
        ]);

        // Batteries
        foreach (range(1, 5) as $i) {
            Battery::create([
                'serial_number' => 'redversbattery' . $i,
                'status' => ['in_stock', 'charging'][rand(0, 1)],
                'current_station_id' => [$station1->id, $station2->id][rand(0, 1)],
            ]);
        }

        // ✅ Add this line to seed motorcycle plans
        $this->call(MotorcycleSeeder::class);
    }
}
