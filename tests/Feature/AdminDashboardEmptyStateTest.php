<?php

use App\Models\User;

it('shows a single empty state instead of empty request tables', function () {
    $admin = User::factory()->create(['approved' => true]);
    $admin->forceFill(['role' => 'admin'])->save();

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk()
        ->assertSee('Тренутно нема захтјева за администратора.')
        ->assertDontSee('<table', false)
        ->assertDontSee('Захтjеви за регистрацију')
        ->assertDontSee('Захтјеви за измјену профила')
        ->assertDontSee('Приједлози нових ознака за књиге');
});

it('shows only request tables that contain pending items', function () {
    $admin = User::factory()->create(['approved' => true]);
    $admin->forceFill(['role' => 'admin'])->save();
    User::factory()->create([
        'name' => 'Pending applicant',
        'approved' => false,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk()
        ->assertSee('Pending applicant')
        ->assertSee('<table', false)
        ->assertDontSee('Тренутно нема захтјева за администратора.')
        ->assertDontSee('Захтјеви за измјену профила')
        ->assertDontSee('Приједлози нових ознака за књиге');
});
