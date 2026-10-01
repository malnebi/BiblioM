<?php

use App\Actions\PromoteStudents;
use App\Models\User;
use Illuminate\Support\Carbon;

it('promotes approved students on the next grade and marks graduates as former students', function () {
    $firstGrade = User::factory()->create([
        'approved' => true,
        'role_type' => 'Ученик',
        'role_details' => 'I-1',
    ]);
    $secondGrade = User::factory()->create([
        'approved' => true,
        'role_type' => 'Ученик',
        'role_details' => 'II-4',
    ]);
    $thirdGrade = User::factory()->create([
        'approved' => true,
        'role_type' => 'Ученик',
        'role_details' => 'III-2',
    ]);
    $graduate = User::factory()->create([
        'approved' => true,
        'role_type' => 'Ученик',
        'role_details' => 'IV-3',
    ]);
    $unapproved = User::factory()->create([
        'approved' => false,
        'role_type' => 'Ученик',
        'role_details' => 'I-2',
    ]);

    expect((new PromoteStudents)())->toBe(4);
    expect($firstGrade->fresh()->role_details)->toBe('II-1');
    expect($secondGrade->fresh()->role_details)->toBe('III-4');
    expect($thirdGrade->fresh()->role_details)->toBe('IV-2');
    expect($graduate->fresh()->role_type)->toBe(User::FORMER_STUDENT);
    expect($graduate->fresh()->role_details)->toBeNull();
    expect($unapproved->fresh()->role_details)->toBe('I-2');
});

it('does not promote a student more than once when the promotion action is repeated', function () {
    $student = User::factory()->create([
        'approved' => true,
        'role_type' => 'Ученик',
        'role_details' => 'I1',
    ]);

    expect((new PromoteStudents)())->toBe(1);
    expect((new PromoteStudents)())->toBe(0);
    expect($student->fresh()->role_details)->toBe('II-1');
});

it('does not run the promotion command outside August 31', function () {
    $student = User::factory()->create([
        'approved' => true,
        'role_type' => 'Ученик',
        'role_details' => 'I-1',
    ]);
    Carbon::setTestNow(Carbon::parse('2026-10-01 12:00:00', 'Europe/Belgrade'));

    try {
        $this->artisan('students:promote')
            ->expectsOutput('Промоција ученика се извршава само 31. августа.')
            ->assertExitCode(0);
        expect($student->fresh()->role_details)->toBe('I-1');
    } finally {
        Carbon::setTestNow();
    }
});

it('runs the promotion command on August 31', function () {
    $student = User::factory()->create([
        'approved' => true,
        'role_type' => 'Ученик',
        'role_details' => 'I-1',
    ]);
    Carbon::setTestNow(Carbon::parse('2026-08-31 00:00:00', 'Europe/Belgrade'));

    try {
        $this->artisan('students:promote')
            ->expectsOutput('Ажурирано ученика: 1')
            ->assertExitCode(0);
        expect($student->fresh()->role_details)->toBe('II-1');
    } finally {
        Carbon::setTestNow();
    }
});