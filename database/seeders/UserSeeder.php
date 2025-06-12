<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'User',
            'username' => 'user',
            'email_verified_at' => now(),
            'email' => 'user@dev.com',
            'password' => 'user@dev.com',
        ]);
        User::create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'testuser@dev.com',
            'password' => 'testuser@dev.com',
        ]);
    }
}
