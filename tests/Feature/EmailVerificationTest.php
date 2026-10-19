<?php

use App\Models\User;
use App\Notifications\AccountApproved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

it('sends an approved user a welcome message with a signed email verification link', function () {
    Notification::fake();

    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();
    $user = User::factory()->unverified()->create(['approved' => false]);

    $this->actingAs($admin)
        ->patch(route('admin.users.approve', $user))
        ->assertRedirect();

    expect((bool) $user->fresh()->approved)->toBeTrue();
    expect($user->fresh()->email_verification_required)->toBeTrue();
    Notification::assertSentTo($user, AccountApproved::class);

    $notification = new AccountApproved;
    $message = $notification->toMail($user);

    expect($message->subject)->toBe('БиблиоМ – налог је одобрен')
        ->and($message->greeting)->toBe('Добро дошли, '.$user->name.'!')
        ->and($message->salutation)->toBe('Срдачно')
        ->and($message->actionUrl)->toContain('/email/verify/'.$user->id.'/')
        ->and(URL::hasValidSignature(Request::create($message->actionUrl)))->toBeTrue();

    $this->get($message->actionUrl)->assertRedirect(route('login'));
    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
});

it('does not send verification mail before admin approval', function () {
    Notification::fake();

    $this->post('/register', [
        'name' => 'Тест',
        'last_name' => 'Корисник',
        'role_type' => 'Родитељ',
        'email' => 'registration@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'numbering_mode' => 'automatic',
    ])->assertRedirect('/login');

    Notification::assertNothingSent();
    $this->assertDatabaseHas('users', [
        'email' => 'registration@example.com',
        'approved' => false,
        'email_verification_required' => true,
        'email_verified_at' => null,
    ]);
});

it('verifies an approved user through the signed link', function () {
    $user = User::factory()->unverified()->create([
        'approved' => true,
        'email_verification_required' => true,
    ]);
    $url = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(config('auth.verification.expire', 60)),
        ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
    );

    $this->get($url)
        ->assertRedirect(route('login'))
        ->assertSessionHas('message', 'Мејл адреса је потврђена. Сада се можете пријавити.');

    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
});

it('does not verify an unapproved user', function () {
    $user = User::factory()->unverified()->create(['approved' => false]);
    $url = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(config('auth.verification.expire', 60)),
        ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
    );

    $this->get($url)->assertForbidden();

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});

it('requires email verification before allowing an approved user to log in', function () {
    $user = User::factory()->unverified()->create([
        'approved' => true,
        'email_verification_required' => true,
    ]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('allows a verified approved user to log in', function () {
    $user = User::factory()->create(['approved' => true]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect('/home');

    $this->assertAuthenticatedAs($user);
});

it('preserves login for existing approved accounts', function () {
    $user = User::factory()->unverified()->create([
        'approved' => true,
        'email_verification_required' => false,
    ]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect('/home');

    $this->assertAuthenticatedAs($user);
});

it('does not allow an unapproved user to log in with valid credentials', function () {
    $user = User::factory()->create(['approved' => false]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});
