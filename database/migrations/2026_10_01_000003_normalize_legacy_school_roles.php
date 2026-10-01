<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->whereIn('role_type', ['Професор', 'Стручни сарадник'])
            ->update(['role_type' => 'Радник школе']);

        DB::table('profile_change_requests')
            ->whereIn('requested_role_type', ['Професор', 'Стручни сарадник'])
            ->update(['requested_role_type' => 'Радник школе']);
    }

    public function down(): void
    {
    }
};