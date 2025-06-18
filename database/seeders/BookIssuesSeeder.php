<?php

namespace Database\Seeders;

use App\Models\BookIssues;
use Illuminate\Database\Seeder;

class BookIssuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BookIssues::create([
            'issue_code' => 'ISSUE-002',
            'user_id' => 2,
            'book_id' => 2,
            'issued_by' => 1,
            'returned_by' => 2,
            'issue_date' => now(),
            'due_date' => now()->addDays(14),
            'status' => BookIssues::STATUS_PENDING,
            'fine_amount' => 0,
            'notes' => 'Second issue of the book.',
            // ✅ Do not add stray values like [], '', or 0 without a key
        ]);

        BookIssues::create([
            'issue_code' => 'ISSUE-003',
            'user_id' => 1, // Assuming user with ID 1 exists
            'book_id' => 1, // Assuming book with ID 1 exists
            'issued_by' => 1, // Assuming admin with ID 1 exists
            'returned_by' => 1,
            'issue_date' => now(),
            'due_date' => now()->addDays(14),
            'status' => BookIssues::STATUS_PENDING,
            'fine_amount' => 0.00,
            'notes' => 'Third issue of the book.',
        ]);
    }
}
