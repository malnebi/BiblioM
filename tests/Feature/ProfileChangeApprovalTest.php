<?php

use App\Models\ProfileChangeRequest;
use App\Models\Library;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('shows the profile settings form to the signed-in user', function () {
    $user = User::factory()->create([
        'role_type' => 'Ученик',
        'role_details' => 'II-2',
    ]);
    Library::factory()->create(['owner_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('settings.edit'));

    $response->assertOk()
        ->assertSee('Подешавања профила')
        ->assertSee('Пошаљи измјене на одобрење')
        ->assertSee('value="Радник школе"', false)
        ->assertDontSee('value="Професор"', false)
        ->assertDontSee('value="Стручни сарадник"', false)
        ->assertSee('директор, професор (наставни предмет), педагог, психолог, библиотекар')
        ->assertSee('Разред и одјељење')
        ->assertSee('I-1')
        ->assertSee('II-2')
        ->assertSee('method="POST"', false)
        ->assertSee('name="_method" value="PUT"', false);
});

it('submits profile identity changes for approval without changing the active profile', function () {
    $user = User::factory()->create(['name' => 'Тренутно име']);

    $response = $this->actingAs($user)->put(route('settings.update'), [
        'name' => 'Ново име',
        'last_name' => 'Ново презиме',
    ]);

    $response->assertRedirect(route('settings.edit'))
        ->assertSessionHas('status', 'Захтјев је послат. Измјене профила и библиотеке биће примјењене након администраторског одобрења.');

    expect($user->fresh()->name)->toBe('Тренутно име');
    $this->assertDatabaseHas('profile_change_requests', [
        'user_id' => $user->id,
        'requested_name' => 'Ново име',
        'requested_last_name' => 'Ново презиме',
        'status' => 'pending',
    ]);
});

it('submits school role changes for approval without applying them immediately', function () {
    $user = User::factory()->create([
        'role_type' => 'Ученик',
        'role_details' => 'III-1',
    ]);

    $response = $this->actingAs($user)->put(route('settings.update'), [
        'name' => $user->name,
        'last_name' => $user->last_name,
        'role_type' => 'Друго',
        'role_details' => 'Више нисам у школи',
    ]);

    $response->assertRedirect(route('settings.edit'));
    expect($user->fresh()->role_type)->toBe('Ученик');
    $this->assertDatabaseHas('profile_change_requests', [
        'user_id' => $user->id,
        'requested_role_type' => 'Друго',
        'requested_role_details' => 'Више нисам у школи',
        'status' => 'pending',
    ]);
});

it('stores a selected class when changing to the student role', function () {
    $user = User::factory()->create([
        'role_type' => 'Радник школе',
        'role_details' => 'Математика',
    ]);

    $response = $this->actingAs($user)->put(route('settings.update'), [
        'name' => $user->name,
        'last_name' => $user->last_name,
        'role_type' => 'Ученик',
        'role_details' => 'II-3',
    ]);

    $response->assertRedirect(route('settings.edit'));
    $this->assertDatabaseHas('profile_change_requests', [
        'user_id' => $user->id,
        'requested_role_type' => 'Ученик',
        'requested_role_details' => 'II-3',
        'status' => 'pending',
    ]);
});

it('requires details for Друго and rejects unsupported profile roles', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->put(route('settings.update'), [
        'name' => $user->name,
        'last_name' => $user->last_name,
        'role_type' => 'Друго',
        'role_details' => '',
    ])->assertSessionHasErrors('role_details');

    $this->actingAs($user)->put(route('settings.update'), [
        'name' => $user->name,
        'last_name' => $user->last_name,
        'role_type' => 'Ученик',
        'role_details' => '',
    ])->assertSessionHasErrors('role_details');

    $this->actingAs($user)->put(route('settings.update'), [
        'name' => $user->name,
        'last_name' => $user->last_name,
        'role_type' => 'Администратор',
        'role_details' => 'Неприхваћена улога',
    ])->assertSessionHasErrors('role_type');

    $this->assertDatabaseMissing('profile_change_requests', ['user_id' => $user->id]);
});

it('only allows a user to edit their own profile settings', function () {
    $user = User::factory()->create();
    $anotherUser = User::factory()->create();

    $this->actingAs($user)
        ->get('/users/' . $anotherUser->id . '/edit')
        ->assertForbidden();
});

it('allows only admins to approve profile changes', function () {
    $user = User::factory()->create();
    $request = ProfileChangeRequest::create([
        'user_id' => $user->id,
        'requested_name' => 'Ново име',
        'requested_last_name' => 'Ново презиме',
    ]);

    $this->actingAs(User::factory()->create())
        ->patch(route('admin.profile-changes.approve', $request))
        ->assertForbidden();

    expect($user->fresh()->name)->not->toBe('Ново име');
});

it('shows pending profile changes on the admin dashboard', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();
    Library::factory()->create(['owner_id' => $admin->id]);
    $user = User::factory()->create(['name' => 'Стари подаци']);
    ProfileChangeRequest::create([
        'user_id' => $user->id,
        'requested_name' => 'Предложено име',
        'requested_last_name' => 'Предложено презиме',
        'requested_role_type' => 'Друго',
        'requested_role_details' => 'Више нисам у школи',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Предложено име')
        ->assertSee('Друго')
        ->assertSee('Више нисам у школи')
        ->assertSee('Одобри')
        ->assertSee('Одбиј');
});

it('applies requested profile changes after admin approval', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();
    $user = User::factory()->create(['name' => 'Старо име']);
    $request = ProfileChangeRequest::create([
        'user_id' => $user->id,
        'requested_name' => 'Одобрено име',
        'requested_last_name' => 'Одобрено презиме',
    ]);

    $response = $this->actingAs($admin)
        ->patch(route('admin.profile-changes.approve', $request));

    $response->assertRedirect();
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Одобрено име',
        'last_name' => 'Одобрено презиме',
    ]);
    $this->assertDatabaseHas('profile_change_requests', [
        'id' => $request->id,
        'status' => 'approved',
        'reviewed_by' => $admin->id,
    ]);
});

it('applies requested school role after admin approval', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();
    $user = User::factory()->create([
        'role_type' => 'Ученик',
        'role_details' => 'III-1',
    ]);
    $request = ProfileChangeRequest::create([
        'user_id' => $user->id,
        'requested_role_type' => 'Друго',
        'requested_role_details' => 'Више нисам у школи',
    ]);

    $this->actingAs($admin)->patch(route('admin.profile-changes.approve', $request));

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'role_type' => 'Друго',
        'role_details' => 'Више нисам у школи',
    ]);
});

it('rejects profile changes without applying them', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();
    $user = User::factory()->create(['name' => 'Старо име']);
    $request = ProfileChangeRequest::create([
        'user_id' => $user->id,
        'requested_name' => 'Неодобрено име',
        'requested_last_name' => 'Неприхваћено презиме',
    ]);

    $response = $this->actingAs($admin)
        ->delete(route('admin.profile-changes.reject', $request));

    $response->assertRedirect();
    expect($user->fresh()->name)->toBe('Старо име');
    $this->assertDatabaseHas('profile_change_requests', [
        'id' => $request->id,
        'status' => 'rejected',
        'reviewed_by' => $admin->id,
    ]);
});

it('holds a new profile photo until an admin approves it', function () {
    Storage::fake('public');
    $user = User::factory()->create(['user_photo' => null]);
    $photo = UploadedFile::fake()->createWithContent(
        'profile.png',
        base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/lN8AAAAASUVORK5CYII=')
    );

    $this->actingAs($user)->put(route('settings.update'), [
        'name' => $user->name,
        'last_name' => 'Корисник',
        'user_photo' => $photo,
    ])->assertRedirect(route('settings.edit'));

    $change = ProfileChangeRequest::where('user_id', $user->id)->firstOrFail();
    expect($user->fresh()->user_photo)->toBeNull();
    Storage::disk('public')->assertExists($change->requested_photo);

    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();
    $this->actingAs($admin)->patch(route('admin.profile-changes.approve', $change));

    expect($user->fresh()->user_photo)->toBe($change->requested_photo);
    Storage::disk('public')->assertExists($change->requested_photo);
});

it('submits and approves library name and logo changes with the profile request', function () {
    Storage::fake('public');
    $user = User::factory()->create([
        'name' => 'Старо име',
        'last_name' => 'Старо презиме',
    ]);
    $library = Library::factory()->create([
        'owner_id' => $user->id,
        'name' => 'Стара библиотека',
        'logo' => null,
    ]);
    $logo = UploadedFile::fake()->createWithContent(
        'logo.png',
        base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/lN8AAAAASUVORK5CYII=')
    );

    $this->actingAs($user)->put(route('settings.update'), [
        'name' => 'Ново име',
        'last_name' => 'Ново презиме',
        'library_name' => 'Нова библиотека',
        'library_logo' => $logo,
    ])->assertRedirect(route('settings.edit'));

    $change = ProfileChangeRequest::where('user_id', $user->id)->firstOrFail();
    expect($user->fresh()->name)->toBe('Старо име');
    expect($library->fresh()->name)->toBe('Стара библиотека');
    Storage::disk('public')->assertExists($change->requested_library_logo);

    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();
    $this->actingAs($admin)->patch(route('admin.profile-changes.approve', $change));

    expect($user->fresh()->name)->toBe('Ново име');
    expect($library->fresh()->name)->toBe('Нова библиотека');
    expect($library->fresh()->logo)->toBe($change->requested_library_logo);
    Storage::disk('public')->assertExists($change->requested_library_logo);
});