<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Traits\AuditColumnsTrait;
use App\Models\Book;
use Illuminate\Database\Eloquent\SoftDeletes;

return new class extends Migration
{
    use SoftDeletes, AuditColumnsTrait;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sort_order')->default(0);
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('isbn')->unique()->nullable();
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('category_id')->index();
            $table->unsignedBigInteger('publisher_id')->index();
            $table->unsignedBigInteger('rack_id')->index();
            $table->date('publication_date')->nullable();
            $table->integer('pages')->nullable();
            $table->string('language')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->string('cover_image')->nullable();
            $table->integer('total_copies')->default(1);
            $table->integer('available_copies')->default(1);
            $table->tinyInteger('status')->default(Book::STATUS_AVAILABLE); // 1: Available, 2: Checked Out, 3: Reserved
            $table->string('file')->nullable();


            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('publisher_id')->references('id')->on('publishers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('rack_id')->references('id')->on('racks')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();
            $this->addAdminAuditColumns($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
