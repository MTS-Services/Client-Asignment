<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::create([
            'sort_order' => 1,
            'title' => 'Sample Book Title',
            'slug' => 'sample-book-title',
            'isbn' => '978-3-16-148410-0',
            'description' => 'This is a sample book description.',
            'category_id' => 1, // Assuming category with ID 1 exists
            'publisher_id' => 1, // Assuming publisher with ID 1 exists
            'rack_id' => 1, // Assuming rack with ID 1 exists
            'publication_date' => '2023-01-01',
            'pages' => 300,
            'language' => 'English',
            'price' => 19.99,
            'total_copies' => 10,
            'available_copies' => 10,
            'status' => Book::STATUS_AVAILABLE,
        ]);

        // You can add more sample books as needed
        Book::create([
            'sort_order' => 2,
            'title' => 'Another Sample Book',
            'slug' => 'another-sample-book',
            'isbn' => '978-1-23-456789-0',
            'description' => 'This is another sample book description.',
            'category_id' => 1, // Assuming category with ID 1 exists
            'publisher_id' => 1, // Assuming publisher with ID 1 exists
            'rack_id' => 1, // Assuming rack with ID 1 exists
            'publication_date' => '2023-02-01',
            'pages' => 250,
            'language' => 'English',
            'price' => 15.99,
            'total_copies' => 5,
            'available_copies' => 5,
            'status' => Book::STATUS_AVAILABLE,
        ]);

        Book::create([
            'sort_order' => 3,
            'title' => 'Third Sample Book',
            'slug' => 'third-sample-book',
            'isbn' => '978-0-12-345678-9',
            'description' => 'This is the third sample book description.',
            'category_id' => 1, // Assuming category with ID 1 exists
            'publisher_id' => 1, // Assuming publisher with ID 1 exists
            'rack_id' => 1, // Assuming rack with ID 1 exists
            'publication_date' => '2023-03-01',
            'pages' => 400,
            'language' => 'English',
            'price' => 25.99,
            'total_copies' => 8,
            'available_copies' => 8,
            'status' => Book::STATUS_AVAILABLE,
        ]);

    }
}
