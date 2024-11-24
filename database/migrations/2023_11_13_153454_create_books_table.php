<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Library;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Library::class, 'library_id');
            $table->integer('lib_book_id'); // Add this line
            $table->string('author_fname', 100)->nullable();
            $table->string('author_lname', 100)->nullable();
            $table->string('title', 200)->nullable();
            $table->string('publisher_name', 100)->nullable();
            $table->string('publisher_place', 100)->nullable();
            $table->string('year', 10)->nullable();
            $table->tinyInteger('loan')->default(0);
            $table->unsignedBigInteger('lib_user_id')->nullable();
            $table->timestamps();
            $table->boolean('featured')->default(false);
            $table->foreign('lib_user_id')->references('id')->on('users')->onDelete('cascade');
  
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
