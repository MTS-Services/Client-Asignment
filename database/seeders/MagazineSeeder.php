<?php

namespace Database\Seeders;

use App\Models\Magazine;
use Illuminate\Database\Seeder;

class MagazineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Magazine::create([
            'title' => 'Example Magazine',
            'slug' => 'example-magazine',
            'status' => '1',
            'description' => 'This is an example magazine.',
            'cover_image' => 'path/to/cover/image.jpg',
        ]);
        Magazine::create([
            'title' => 'Sample Magazine',
            'slug' => 'sample-magazine',
            'status' => '1',
            'description' => 'This is a sample magazine.',
            'cover_image' => 'path/to/sample/image.jpg',
        ]);
        Magazine::create([
            'title' => 'Demo Magazine',
            'slug' => 'demo-magazine',
            'status' => '1',
            'description' => 'This is a demo magazine.',
            'cover_image' => 'path/to/demo/image.jpg',
        ]);
    }
}
