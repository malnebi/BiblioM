<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->boolean('approved')->default(true);
            $table->foreignId('book_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('suggested_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->dropConstrainedForeignId('book_id');
            $table->dropConstrainedForeignId('suggested_by');
            $table->dropColumn('approved');
        });
    }
};