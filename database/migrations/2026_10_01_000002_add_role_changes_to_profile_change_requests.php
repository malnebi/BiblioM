<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profile_change_requests', function (Blueprint $table) {
            $table->string('requested_role_type')->nullable();
            $table->string('requested_role_details')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('profile_change_requests', function (Blueprint $table) {
            $table->dropColumn(['requested_role_type', 'requested_role_details']);
        });
    }
};