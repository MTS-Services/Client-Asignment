<?php

namespace Database\Seeders;

use App\Models\Newspaper;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewspaperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Newspaper::create([
            'title' => 'Example Newspaper',
            'slug' => 'example-newspaper',
            'status' => '1',
            'description' => 'This is an example newspaper.',
            'cover_image' => 'path/to/cover/image.jpg',
        ]);
        Newspaper::create([
            'title' => 'Sample Newspaper',
            'slug' => 'sample-newspaper',
            'status' => '1',
            'description' => 'This is a sample newspaper.',
            'cover_image' => 'path/to/sample/image.jpg',
        ]);
        Newspaper::create([
            'title' => 'Demo Newspaper',
            'slug' => 'demo-newspaper',
            'status' => '1',
            'description' => 'This is a demo newspaper.',
            'cover_image' => 'path/to/demo/image.jpg',
        ]);
    }
}
