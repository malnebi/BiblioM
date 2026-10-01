<?php

use App\Models\User;

it('offers the new school role choices on the registration form', function () {
    $response = $this->get('/register');

    $response->assertOk()
        ->assertSee('value="Ученик"', false)
        ->assertSee('value="Радник школе"', false)
        ->assertSee('value="Родитељ"', false)
        ->assertSee('value="Друго"', false)
        ->assertDontSee('value="Професор"', false)
        ->assertDontSee('value="Стручни сарадник"', false)
        ->assertSee('директор, професор (наставни предмет), педагог, психолог, библиотекар');
});

it('lets admins correct the role and details of a pending user', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();
    $user = User::factory()->create([
        'role_type' => 'Друго',
        'role_details' => 'Пријатељ',
        'approved' => 0,
    ]);

    $response = $this->actingAs($admin)->patch(route('admin.users.update-role', $user), [
        'role_type' => 'Родитељ',
        'role_details' => 'Родитељ ученика',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'role_type' => 'Родитељ',
        'role_details' => 'Родитељ ученика',
    ]);
});

it('requires an explanation when a pending user role is Друго', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();
    $user = User::factory()->create(['approved' => 0]);

    $response = $this->actingAs($admin)->patch(route('admin.users.update-role', $user), [
        'role_type' => 'Друго',
        'role_details' => '',
    ]);

    $response->assertSessionHasErrors('role_details');
});

it('requires registration applicants to describe the Друго role', function () {
    $response = $this->from('/register')->post('/register', [
        'name' => 'Тест',
        'last_name' => 'Корисник',
        'role_type' => 'Друго',
        'role_details_input' => '',
        'email' => 'test-role@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors('role_details_input');
    $this->assertDatabaseMissing('users', ['email' => 'test-role@example.com']);
});

it('lets admins set a pending user as a school worker with a job description', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();
    $user = User::factory()->create(['approved' => 0]);

    $response = $this->actingAs($admin)->patch(route('admin.users.update-role', $user), [
        'role_type' => 'Радник школе',
        'role_details' => 'Педагог',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'role_type' => 'Радник школе',
        'role_details' => 'Педагог',
    ]);
});

it('requires a job description for a school worker during registration', function () {
    $attributes = [
        'name' => 'Тест',
        'last_name' => 'Корисник',
        'role_type' => 'Радник школе',
        'email' => 'school-worker@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'numbering_mode' => 'automatic',
    ];

    $this->from('/register')->post('/register', $attributes)
        ->assertSessionHasErrors('role_details_input');

    $this->post('/register', $attributes + ['role_details_input' => 'Библиотекар'])
        ->assertRedirect('/login');

    $this->assertDatabaseHas('users', [
        'email' => 'school-worker@example.com',
        'role_type' => 'Радник школе',
        'role_details' => 'Библиотекар',
    ]);
});