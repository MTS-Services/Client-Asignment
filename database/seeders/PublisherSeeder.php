<?php

namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Publisher::create([
            'name' => 'Publisher One',
            'email' => 'publisher1@dev.com',
            'slug' => 'publisher-one',
            'address' => '123 Publisher St, City, Country',
            'phone' => '123-456-7890',
            'website' => 'https://publisher1.dev',
            'status' => Publisher::STATUS_ACTIVE,
        ]);
        Publisher::create([
            'name' => 'Publisher Two',
            'email' => 'publisher2@dev.com',
            'slug' => 'publisher-two',
            'address' => '456 Publisher Ave, City, Country',
            'phone' => '987-654-3210',
            'website' => 'https://publisher2.dev',
            'status' => Publisher::STATUS_ACTIVE,
        ]);
        Publisher::create([
            'name' => 'Publisher Three',
            'email' => 'publisher3@dev.com',
            'slug' => 'publisher-three',
            'address' => '789 Publisher Blvd, City, Country',
            'phone' => '456-789-0123',
            'website' => 'https://publisher3.dev',
            'status' => Publisher::STATUS_ACTIVE,
        ]);

    }
}
