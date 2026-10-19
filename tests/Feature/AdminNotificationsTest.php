<?php

use App\Models\ProfileChangeRequest;
use App\Models\Tag;
use App\Models\User;

it('shows administrators counts and links for unresolved admin requests', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();
    $registration = User::factory()->create(['approved' => 0]);
    $profileChange = ProfileChangeRequest::create([
        'user_id' => User::factory()->create(['approved' => 1])->id,
        'requested_name' => 'Ново име',
    ]);
    $tag = Tag::create(['name' => 'Нови жанр', 'approved' => false]);

    $this->actingAs($admin)
        ->get('/privacy')
        ->assertOk()
        ->assertSee('data-notification-count="admin"', false)
        ->assertSee('Нови административни захтјеви')
        ->assertSee('Нови захтјеви за регистрацију: 1')
        ->assertSee('Захтјеви за измјену профила: 1')
        ->assertSee('Предлози нових ознака за књиге: 1')
        ->assertSee(route('admin.dashboard'));

    $this->actingAs($admin)
        ->delete(route('admin.users.reject', $registration))
        ->assertRedirect();
    $this->actingAs($admin)
        ->delete(route('admin.profile-changes.reject', $profileChange))
        ->assertRedirect();
    $this->actingAs($admin)
        ->delete(route('admin.tags.reject', $tag))
        ->assertRedirect();

    $this->actingAs($admin)
        ->get('/privacy')
        ->assertOk()
        ->assertDontSee('data-notification-count="admin"', false);
});

it('does not show admin notifications to regular users', function () {
    User::factory()->create(['approved' => 0]);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/privacy')
        ->assertOk()
        ->assertDontSee('data-notification-count="admin"', false);
});
