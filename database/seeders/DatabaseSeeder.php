<?php

namespace Database\Seeders;

use App\Models\Desk;
use App\Models\Floor;
use App\Models\Place;
use App\Models\Room;
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
        Place::query()->create([
            'name' => 'Demo Office Location'
        ]);

        Floor::query()->create([
            'name' => 'First Floor',
            'place_id' => 1,
        ]);

        Room::query()->create([
            'name' => 'Demo Room',
            'attributes' => json_decode('{"image":"01JN4MWN7RSR02NVM3S7EB58H7.jpeg"}'),
            'floor_id' => 1,
        ]);

        Desk::query()->create([
            'name' => 'A1',
            'attributes' => json_decode('{"image":null,"position":null,"description":"Desk 1"}'),
            'room_id' => 1,
        ]);

        Desk::query()->create([
            'name' => 'A2',
            'attributes' => json_decode('{"image":null,"position":null,"description":"Desk 2"}'),
            'room_id' => 1,
        ]);

        Desk::query()->create([
            'name' => 'A3',
            'attributes' => json_decode('{"image":null,"position":null,"description":"Desk 3"}'),
            'room_id' => 1,
        ]);
    }
}
