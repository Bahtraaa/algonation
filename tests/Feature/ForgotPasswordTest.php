<?php

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

it('returns the same response for registered and unregistered emails', function () {
    $user = User::factory()->create();

    $registered = $this->post(route('password.email'), ['email' => $user->email]);
    $unregistered = $this->post(route('password.email'), ['email' => 'tidak-ada@example.com']);

    $registered->assertRedirect();
    $unregistered->assertRedirect();

    expect($registered->getSession()->get('success'))
        ->toBe($unregistered->getSession()->get('success'))
        ->not->toBeNull();
});

it('does not reveal account existence through validation or status responses', function () {
    $this->post(route('password.email'), ['email' => 'bukan-akun@example.com'])
        ->assertRedirect()
        ->assertSessionMissing('errors');
});

it('creates a reset token and notification for a registered account', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->post(route('password.email'), ['email' => $user->email])
        ->assertRedirect();

    Notification::assertSentTo($user, ResetPasswordNotification::class);

    $this->assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);
});

it('does not send a notification or create a token for an unregistered email', function () {
    Notification::fake();

    $this->post(route('password.email'), ['email' => 'tidak-ada@example.com'])
        ->assertRedirect();

    Notification::assertNothingSent();

    $this->assertDatabaseMissing('password_reset_tokens', ['email' => 'tidak-ada@example.com']);
});

it('requests with the same account are rate limited and include retry_after', function () {
    Notification::fake();

    $user = User::factory()->create();
    $limit = (int) config('security.password_reset.max_attempts');

    foreach (range(1, $limit) as $i) {
        $this->postJson(route('password.email'), ['email' => $user->email])
            ->assertOk()
            ->assertJsonPath('message', 'Jika email tersebut terdaftar, instruksi reset password telah dikirim. Silakan periksa inbox email kamu.');
    }

    $response = $this->postJson(route('password.email'), ['email' => $user->email]);

    $response->assertStatus(429)
        ->assertJsonStructure(['message', 'retry_after'])
        ->assertJsonPath('message', 'Terlalu banyak permintaan. Silakan coba lagi nanti.')
        ->assertJsonMissingPath('email');

    expect($response->json('retry_after'))->toBeGreaterThanOrEqual(29 * 60);

    $this->assertDatabaseCount('password_reset_tokens', 1);
});

it('shows an invalid-link page instead of the reset form for an unusable token', function () {
    $user = User::factory()->create();

    $this->get(route('password.reset', [
        'token' => 'token-tidak-valid',
        'email' => $user->email,
    ]))
        ->assertOk()
        ->assertSee('Link Tidak Valid')
        ->assertDontSee('Masukkan password baru untuk akun kamu.');
});

it('shows an expired-link page when the token has passed its lifetime', function () {
    $user = User::factory()->create();
    $token = Password::broker()->createToken($user);

    DB::table('password_reset_tokens')
        ->where('email', $user->email)
        ->update(['created_at' => now()->subMinutes((int) config('auth.passwords.users.expire', 60) + 10)]);

    $this->get(route('password.reset', [
        'token' => $token,
        'email' => $user->email,
    ]))
        ->assertOk()
        ->assertSee('Link Reset Kedaluwarsa');
});

it('shows the reset form for a valid token', function () {
    $user = User::factory()->create();
    $token = Password::broker()->createToken($user);

    Notification::fake();

    $this->get(route('password.reset', [
        'token' => $token,
        'email' => $user->email,
    ]))->assertOk()->assertSee('token');
});

it('resets the password and invalidates the used token', function () {
    $user = User::factory()->create();
    $token = Password::broker()->createToken($user);

    $this->post(route('password.update'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'rahasia-baru-123',
        'password_confirmation' => 'rahasia-baru-123',
    ])->assertRedirect(route('login'));

    expect(Hash::check('rahasia-baru-123', $user->fresh()->password))->toBeTrue();

    $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);
});

it('does not allow a used token to be reused', function () {
    $user = User::factory()->create();
    $token = Password::broker()->createToken($user);

    $this->post(route('password.update'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'rahasia-baru-123',
        'password_confirmation' => 'rahasia-baru-123',
    ])->assertRedirect(route('login'));

    $this->post(route('password.update'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'rahasia-baru-456',
        'password_confirmation' => 'rahasia-baru-456',
    ])->assertSessionHasErrors('email');

    expect(Hash::check('rahasia-baru-456', $user->fresh()->password))->toBeFalse();
});

it('validates the new password policy', function () {
    $user = User::factory()->create();
    $token = Password::broker()->createToken($user);

    $this->post(route('password.update'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'pendek',
        'password_confirmation' => 'pendek',
    ])->assertSessionHasErrors('password');

    expect(Hash::check('pendek', $user->fresh()->password))->toBeFalse();
});
