<?php

use App\Models\Library;
use App\Models\User;

it('shows the matching Biblio prefix in the live library name suggestion', function () {
    $this->get('/register')
        ->assertOk()
        ->assertSee('placeholder="БиблиоИме корисника"', false)
        ->assertSee('libraryNameInput.placeholder = `Библио${userName}`;', false);
});

it('uses a Biblio-prefixed default library name when none is provided at registration', function () {
    $this->post('/register', [
        'name' => 'Маја',
        'last_name' => 'Тест',
        'role_type' => 'Родитељ',
        'email' => 'maja@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'numbering_mode' => 'automatic',
    ])->assertRedirect('/login');

    $this->assertDatabaseHas('libraries', [
        'name' => 'БиблиоМаја',
    ]);
});

it('preserves a custom library name provided at registration', function () {
    $this->post('/register', [
        'name' => 'Маја',
        'last_name' => 'Тест',
        'role_type' => 'Родитељ',
        'email' => 'maja-custom@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'library' => 'Читалачки кутак',
        'numbering_mode' => 'automatic',
    ])->assertRedirect('/login');

    $this->assertDatabaseHas('libraries', [
        'name' => 'Читалачки кутак',
    ]);
});

it('updates existing default library names without changing custom names', function () {
    $owner = User::factory()->create(['name' => 'Маја']);
    $defaultLibrary = Library::factory()->create([
        'owner_id' => $owner->id,
        'name' => 'Библиотека Маја',
    ]);
    $customLibrary = Library::factory()->create([
        'owner_id' => User::factory(),
        'name' => 'Мој кутак',
    ]);

    $migration = require database_path('migrations/2026_10_12_000000_update_default_library_names.php');
    $migration->up();

    expect($defaultLibrary->fresh()->name)->toBe('БиблиоМаја')
        ->and($customLibrary->fresh()->name)->toBe('Мој кутак');
});
