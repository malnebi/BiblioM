<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->timestamp('library_confirmation_viewed_at')->nullable();
        });

        DB::table('loans')
            ->where('active', 1)
            ->where('description', 'potpisano')
            ->update(['library_confirmation_viewed_at' => DB::raw('updated_at')]);
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn('library_confirmation_viewed_at');
        });
    }
};
