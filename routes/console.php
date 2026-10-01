<?php

use Illuminate\Foundation\Inspiring;
use App\Actions\PromoteStudents;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('students:promote', function () {
    if (now('Europe/Belgrade')->format('m-d') !== '08-31') {
        $this->warn('Промоција ученика се извршава само 31. августа.');

        return 0;
    }

    $promoted = app(PromoteStudents::class)();
    $this->info("Ажурирано ученика: {$promoted}");
})->purpose('Промовише ученике у наредни разред 31. августа');
