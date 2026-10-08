<?php

use App\Models\User;
use App\Notifications\UserPasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

it('sends a password reset link and resets the password with it', function () {
    Notification::fake();
    $user = User::factory()->create([
        'approved' => true,
        'email_verification_required' => false,
    ]);

    $this->post(route('password.email'), ['email' => $user->email])
        ->assertRedirect()
        ->assertSessionHas('status', 'Послали смо линк за промјену лозинке.');

    $this->get(route('password.request'))
        ->assertOk()
        ->assertSee('Послали смо линк за промјену лозинке.')
        ->assertSee('Назад на пријаву')
        ->assertDontSee('Мејл адреса')
        ->assertDontSee('Пошаљи линк');

    Notification::assertSentTo($user, UserPasswordReset::class);
    $notification = Notification::sent($user, UserPasswordReset::class)->first();
    $resetUrl = $notification->toMail($user)->actionUrl;

    expect($resetUrl)->toContain(route('password.reset', ['token' => $notification->token], false));
    $this->assertStringStartsWith(rtrim(config('app.url'), '/'), $resetUrl);
    $this->get($resetUrl)->assertOk()->assertSee('Постављање нове лозинке');

    $this->post(route('password.update'), [
        'token' => $notification->token,
        'email' => $user->email,
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ])->assertRedirect(route('login'))
        ->assertSessionHas('message', 'Лозинка је промијењена. Сада се можете пријавити.');

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue()
        ->and((bool) $user->fresh()->approved)->toBeTrue()
        ->and($user->fresh()->email_verification_required)->toBeFalse();

    $this->post(route('password.update'), [
        'token' => $notification->token,
        'email' => $user->email,
        'password' => 'another-password',
        'password_confirmation' => 'another-password',
    ])->assertSessionHasErrors('email');

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();

    $this->assertGuest();
    $this->post('/login', [
        'email' => $user->email,
        'password' => 'new-password',
    ])->assertRedirect('/home');
});

it('uses the same response when no account exists for a requested address', function () {
    Notification::fake();

    $this->post(route('password.email'), ['email' => 'unknown@example.com'])
        ->assertRedirect()
        ->assertSessionHas('status', 'Послали смо линк за промјену лозинке.');

    Notification::assertNothingSent();
});

it('rejects invalid or already-used reset tokens', function () {
    Notification::fake();
    $user = User::factory()->create();

    $this->post(route('password.update'), [
        'token' => 'invalid-token',
        'email' => $user->email,
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ])->assertSessionHasErrors('email');

    expect(Hash::check('password', $user->fresh()->password))->toBeTrue();
});
