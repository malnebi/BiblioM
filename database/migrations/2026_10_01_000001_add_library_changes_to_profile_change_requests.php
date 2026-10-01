<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profile_change_requests', function (Blueprint $table) {
            $table->foreignId('library_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('requested_library_name')->nullable();
            $table->string('requested_library_logo')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('profile_change_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('library_id');
            $table->dropColumn(['requested_library_name', 'requested_library_logo']);
        });
    }
};