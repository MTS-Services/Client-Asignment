<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Author::create([
            'name' => 'Author One',
            'biography' => 'Biography of Author One',
            'birth_date' => '1980-01-01',
            'death_date' => null,
            'nationality' => 'American',
            'image' => 'authors/author_one.jpg',
            'status' => Author::STATUS_ACTIVE,
        ]);
        Author::create([
            'name' => 'Author Two',
            'biography' => 'Biography of Author Two',
            'birth_date' => '1980-01-01',
            'death_date' => null,
            'nationality' => 'American',
            'image' => 'authors/author_two.jpg',
            'status' => Author::STATUS_ACTIVE,
        ]);
        Author::create([
            'name' => 'Author Three',
            'biography' => 'Biography of Author Three',
            'birth_date' => '1980-01-01',
            'death_date' => null,
            'nationality' => 'American',
            'image' => 'authors/author_three.jpg',
            'status' => Author::STATUS_ACTIVE,
        ]);
    }
}
