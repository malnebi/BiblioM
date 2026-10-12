<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('libraries')
            ->join('users', 'users.id', '=', 'libraries.owner_id')
            ->select('libraries.id', 'libraries.name', 'users.name as owner_name')
            ->orderBy('libraries.id')
            ->get()
            ->each(function ($library) {
                if ($library->name === 'Библиотека ' . trim($library->owner_name)) {
                    DB::table('libraries')
                        ->where('id', $library->id)
                        ->update(['name' => 'Библио' . trim($library->owner_name)]);
                }
            });
    }

    public function down(): void
    {
        DB::table('libraries')
            ->join('users', 'users.id', '=', 'libraries.owner_id')
            ->select('libraries.id', 'libraries.name', 'users.name as owner_name')
            ->orderBy('libraries.id')
            ->get()
            ->each(function ($library) {
                if ($library->name === 'Библио' . trim($library->owner_name)) {
                    DB::table('libraries')
                        ->where('id', $library->id)
                        ->update(['name' => 'Библиотека ' . trim($library->owner_name)]);
                }
            });
    }
};
