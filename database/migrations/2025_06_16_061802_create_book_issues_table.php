<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Traits\AuditColumnsTrait;
use App\Models\BookIssues;
use Illuminate\Database\Eloquent\SoftDeletes;

return new class extends Migration {
    use SoftDeletes, AuditColumnsTrait;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('book_issues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sort_order')->default(0);
            $table->string('issue_code')->unique();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('issued_by')->nullable();
            $table->date('issue_date');
            $table->date('due_date')->nullable();
            $table->date('return_date')->nullable();
            $table->unsignedBigInteger('returned_by')->nullable();

            $table->tinyInteger('status')->default(BookIssues::STATUS_PENDING); // 1: Pending, 2: Issued, 3: Returned, 4: Overdue, 5: Lost
            $table->decimal('fine_amount', 8, 2)->default(0);
            $table->boolean('fine_status')->nullable();
            $table->text('notes')->nullable();


            // Foreign keys with cascade
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('issued_by')->references('id')->on('admins')->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('returned_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');

            $table->timestamps();
            $table->softDeletes();
            $this->addMorphedAuditColumns($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_issues');
    }
};
