<?php

use App\Models\User;

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