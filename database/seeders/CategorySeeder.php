<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Category 1',
            'slug' => 'category-1',
            'description' => 'Category 1 description'
        ],
    );
    Category::create([
        'name' => 'Category 2',
        'slug' => 'category-2',
        'description' => 'Category 2 description'
    ]);
    }
}
