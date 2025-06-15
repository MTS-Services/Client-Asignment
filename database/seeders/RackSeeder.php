<?php

namespace Database\Seeders;

use App\Models\Rack;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rack::create([
            'rack_number' => 'Rack 1',
            'location' => 'Location A',
            'capacity' => 42,
            'description' => 'This is the first rack.',
        ]);
        Rack::create([
            'rack_number' => 'Rack 2',
            'location' => 'Location B',
            'capacity' => 36,
            'description' => 'This is the second rack.',
        ]);
        Rack::create([
            'rack_number' => 'Rack 3',
            'location' => 'Location C',
            'capacity' => 50,
            'description' => 'This is the third rack.',
        ]);
    }
}
