<?php

use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Cache::flush();
    Mail::fake();
});

function extractResetToken(): ?string
{
    $resetUrl = null;

    try {
        Mail::assertSent(ResetPasswordMail::class, function ($mail) use (&$resetUrl) {
            $resetUrl = $mail->resetUrl;

            return true;
        });
    } catch (Throwable) {
        return null;
    }

    if (! $resetUrl || ! preg_match('#/reset-password/([^?/]+)#', (string) $resetUrl, $m)) {
        return null;
    }

    return $m[1];
}

// TEST 1: Request pertama → SMTP berhasil → email masuk → tercatat #1
it('TEST 1: mencatat request pertama setelah email berhasil dikirim', function () {
    $user = User::factory()->create(['email' => 'user1@example.com']);

    $this->post(route('password.email'), ['email' => $user->email])
        ->assertRedirect()
        ->assertSessionHasNoErrors()
        ->assertSessionHas('status', 'Jika email tersebut terdaftar, link reset password akan dikirim ke email tersebut.');

    Mail::assertSent(ResetPasswordMail::class, 1);
    expect(Cache::get('forgot-password:'.strtolower($user->email).':request_count'))->toBe(1);
});

// TEST 2: Request kedua dalam 1 menit → berhasil → tercatat #2
it('TEST 2: memperbolehkan request kedua dalam 1 menit', function () {
    $user = User::factory()->create(['email' => 'user2@example.com']);

    $this->post(route('password.email'), ['email' => $user->email])->assertRedirect();
    $this->post(route('password.email'), ['email' => $user->email])->assertRedirect()->assertSessionHasNoErrors();

    Mail::assertSent(ResetPasswordMail::class, 2);
    expect(Cache::get('forgot-password:'.strtolower($user->email).':request_count'))->toBe(2);
});

// TEST 3: Request ketiga dalam 1 menit → ditolak → SMTP tidak dipanggil → diblokir 30 menit
it('TEST 3: menolak request ketiga dan memblokir email 30 menit tanpa memanggil SMTP lagi', function () {
    $user = User::factory()->create(['email' => 'user3@example.com']);

    $this->post(route('password.email'), ['email' => $user->email])->assertRedirect();
    $this->post(route('password.email'), ['email' => $user->email])->assertRedirect();

    $this->post(route('password.email'), ['email' => $user->email])
        ->assertSessionHasErrors(['email']);

    // SMTP tetap 2x, tidak ada pengiriman ke-3.
    Mail::assertSent(ResetPasswordMail::class, 2);
    expect(Cache::has('forgot-password:'.strtolower($user->email).':blocked_until'))->toBeTrue();
});

// TEST 4: Request selama masa blokir → ditolak → SMTP tidak dipanggil
it('TEST 4: menolak request selama masa blokir 30 menit', function () {
    $user = User::factory()->create(['email' => 'user4@example.com']);

    $this->post(route('password.email'), ['email' => $user->email])->assertRedirect();
    $this->post(route('password.email'), ['email' => $user->email])->assertRedirect();
    $this->post(route('password.email'), ['email' => $user->email])->assertSessionHasErrors(['email']);

    Mail::assertSent(ResetPasswordMail::class, 2);

    // Masih diblokir: request ke-4 juga ditolak tanpa SMTP baru.
    $this->post(route('password.email'), ['email' => $user->email])->assertSessionHasErrors(['email']);

    Mail::assertSent(ResetPasswordMail::class, 2);
});

// TEST 5: Setelah 30 menit → blokir berakhir → kembali dapat 2 kesempatan
it('TEST 5: membuka blokir otomatis setelah 30 menit', function () {
    $user = User::factory()->create(['email' => 'user5@example.com']);

    $this->post(route('password.email'), ['email' => $user->email])->assertRedirect();
    $this->post(route('password.email'), ['email' => $user->email])->assertRedirect();
    $this->post(route('password.email'), ['email' => $user->email])->assertSessionHasErrors(['email']);

    $this->travel(31)->minutes();

    $this->post(route('password.email'), ['email' => $user->email])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    Mail::assertSent(ResetPasswordMail::class, 3);
});

// TEST 6: SMTP gagal → tidak dihitung → tidak ada token untuk user
it('TEST 6: tidak mencatat request saat SMTP Resend gagal', function () {
    $user = User::factory()->create(['email' => 'user6@example.com']);

    Mail::shouldReceive('to')->once()->with($user->email)->andThrow(new Exception('SMTP connection failed'));

    $this->post(route('password.email'), ['email' => $user->email])
        ->assertSessionHasErrors(['email' => 'Tidak dapat mengirim link reset password saat ini. Silakan coba lagi nanti.']);

    expect(Cache::get('forgot-password:'.strtolower($user->email).':request_count', 0))->toBe(0);
    expect(Cache::has('forgot-password:'.strtolower($user->email).':blocked_until'))->toBeFalse();
});

// TEST 7: Token expired → reset ditolak
it('TEST 7: menolak reset dengan token kedaluwarsa', function () {
    $user = User::factory()->create(['email' => 'user7@example.com']);
    $token = bin2hex(random_bytes(32));

    Cache::put('password-reset:'.hash('sha256', $token), [
        'user_id' => $user->id,
        'email' => $user->email,
        'used_at' => null,
        'expires_at' => now()->subMinutes(5)->toDateTimeString(),
        'created_at' => now()->subMinutes(35)->toDateTimeString(),
    ], now()->addMinutes(30));

    $this->post(route('password.update', ['token' => $token]), [
        'email' => $user->email,
        'password' => 'NewPassword123',
        'password_confirmation' => 'NewPassword123',
    ])->assertSessionHasErrors(['email' => 'Link reset password tidak valid atau sudah kedaluwarsa.']);

    expect(Hash::check('NewPassword123', $user->fresh()->password))->toBeFalse();
});

// TEST 8: Token sudah digunakan → reset kedua ditolak
it('TEST 8: menolak penggunaan token kedua kali', function () {
    $user = User::factory()->create(['email' => 'user8@example.com']);

    $this->post(route('password.email'), ['email' => $user->email])->assertRedirect();

    $token = extractResetToken();
    expect($token)->not->toBeNull();

    $this->post(route('password.update', ['token' => $token]), [
        'email' => $user->email,
        'password' => 'NewPassword123',
        'password_confirmation' => 'NewPassword123',
    ])->assertRedirect(route('login'));

    // Pakai token yang sama untuk kedua kali.
    $this->post(route('password.update', ['token' => $token]), [
        'email' => $user->email,
        'password' => 'AnotherPass123',
        'password_confirmation' => 'AnotherPass123',
    ])->assertSessionHasErrors(['email' => 'Link reset password tidak valid atau sudah kedaluwarsa.']);

    expect(Hash::check('NewPassword123', $user->fresh()->password))->toBeTrue();
});

// TEST 9: Password berhasil diubah → hash → token invalid
it('TEST 9: memperbarui users.password dalam bentuk hash dan menginvalidasi token', function () {
    $user = User::factory()->create([
        'email' => 'user9@example.com',
        'password' => bcrypt('old-password-123'),
    ]);

    $this->post(route('password.email'), ['email' => $user->email])->assertRedirect();

    $token = extractResetToken();
    expect($token)->not->toBeNull();
    $tokenKey = 'password-reset:'.hash('sha256', (string) $token);
    expect(Cache::has($tokenKey))->toBeTrue();

    $this->post(route('password.update', ['token' => $token]), [
        'email' => $user->email,
        'password' => 'BrandNewPass123',
        'password_confirmation' => 'BrandNewPass123',
    ])
        ->assertRedirect(route('login'))
        ->assertSessionHas('status', 'Password berhasil diubah. Silakan login menggunakan password baru.');

    $user->refresh();
    expect(Hash::check('BrandNewPass123', $user->password))->toBeTrue();
    expect($user->password)->not->toBe('BrandNewPass123');
    expect(Cache::get($tokenKey))->toBeNull();
});

// TEST 10: Email tidak terdaftar → tidak bocor
it('TEST 10: tidak membocorkan status email yang tidak terdaftar', function () {
    $response = $this->post(route('password.email'), ['email' => 'unknown-xyz@example.com']);

    $response->assertRedirect()->assertSessionHasNoErrors();
    $response->assertSessionHas('status', 'Jika email tersebut terdaftar, link reset password akan dikirim ke email tersebut.');

    Mail::assertNothingSent();
});

// TEST 11: Normalisasi email — varian huruf besar/kecil dihitung sebagai email yang sama
it('TEST 11: menganggap varian kapitalisasi email sebagai identifier yang sama', function () {
    $user = User::factory()->create(['email' => 'user11@example.com']);

    $this->post(route('password.email'), ['email' => 'User11@Example.com'])->assertRedirect()->assertSessionHasNoErrors();
    $this->post(route('password.email'), ['email' => 'USER11@EXAMPLE.COM'])->assertRedirect()->assertSessionHasNoErrors();

    Mail::assertSent(ResetPasswordMail::class, 2);

    // Varian ketiga tetap ditolak + diblokir karena bucket yang sama sudah penuh.
    $this->post(route('password.email'), ['email' => 'uSeR11@eXaMpLe.CoM'])
        ->assertSessionHasErrors(['email' => 'Terlalu banyak permintaan reset password. Silakan coba lagi setelah 30 menit.']);

    Mail::assertSent(ResetPasswordMail::class, 2);
    expect(Cache::has('forgot-password:user11@example.com:blocked_until'))->toBeTrue();
});

// TEST 12: Sliding window — request lama keluar dari perhitungan setelah 60 detik
it('TEST 12: mengeluarkan request berhasil yang sudah lebih dari 60 detik dari window', function () {
    $user = User::factory()->create(['email' => 'user12@example.com']);

    $this->post(route('password.email'), ['email' => $user->email])->assertRedirect();

    $this->travel(65)->seconds();

    // Dianggap request #1 pada window baru, bukan #2.
    $this->post(route('password.email'), ['email' => $user->email])
        ->assertRedirect()->assertSessionHasNoErrors();

    expect(Cache::get('forgot-password:'.strtolower($user->email).':request_count'))->toBe(1);

    $this->post(route('password.email'), ['email' => $user->email])->assertRedirect()->assertSessionHasNoErrors();
    $this->post(route('password.email'), ['email' => $user->email])
        ->assertSessionHasErrors(['email' => 'Terlalu banyak permintaan reset password. Silakan coba lagi setelah 30 menit.']);

    Mail::assertSent(ResetPasswordMail::class, 3);
});

// TEST 13: Ganti IP tidak melewati rate limit email
it('TEST 13: menolak request dari IP berbeda saat email sedang diblokir', function () {
    $user = User::factory()->create(['email' => 'user13@example.com']);

    $this->post(route('password.email'), ['email' => $user->email])->assertRedirect();
    $this->post(route('password.email'), ['email' => $user->email])->assertRedirect();
    $this->post(route('password.email'), ['email' => $user->email])->assertSessionHasErrors(['email']);

    // IP lain tetap ditolak untuk email yang sama, tanpa email baru terkirim.
    $this->post(route('password.email'), ['email' => $user->email], ['REMOTE_ADDR' => '10.9.9.9'])
        ->assertSessionHasErrors(['email' => 'Terlalu banyak permintaan reset password. Silakan coba lagi setelah 30 menit.']);

    Mail::assertSent(ResetPasswordMail::class, 2);
});

// TEST 14: Satu IP melakukan spam ke banyak email → dibatasi
it('TEST 14: membatasi satu IP yang meminta reset untuk banyak email berbeda', function () {
    for ($i = 1; $i <= 10; $i++) {
        $email = "ipspam{$i}@example.com";
        User::factory()->create(['email' => $email]);
        $this->post(route('password.email'), ['email' => $email])->assertRedirect()->assertSessionHasNoErrors();
    }

    Mail::assertSent(ResetPasswordMail::class, 10);

    User::factory()->create(['email' => 'ipspam11@example.com']);
    $this->post(route('password.email'), ['email' => 'ipspam11@example.com'])
        ->assertSessionHasErrors(['email' => 'Terlalu banyak permintaan reset password. Silakan coba lagi setelah 30 menit.']);

    Mail::assertSent(ResetPasswordMail::class, 10);
});

// TEST 15: SMTP gagal tidak dihitung — request berikutnya masih dianggap pertama
it('TEST 15: request setelah kegagalan SMTP dihitung sebagai request pertama', function () {
    $user = User::factory()->create(['email' => 'user15@example.com']);

    $pending = Mockery::mock(Illuminate\Mail\PendingMail::class);
    $pending->shouldReceive('send')->once()->with(Mockery::type(ResetPasswordMail::class));

    Mail::shouldReceive('to')->once()->ordered()->with($user->email)->andThrow(new Exception('SMTP connection failed'));
    Mail::shouldReceive('to')->once()->ordered()->with($user->email)->andReturn($pending);

    $this->post(route('password.email'), ['email' => $user->email])
        ->assertSessionHasErrors(['email' => 'Tidak dapat mengirim link reset password saat ini. Silakan coba lagi nanti.']);

    expect(Cache::get('forgot-password:'.strtolower($user->email).':request_count', 0))->toBe(0);

    $this->post(route('password.email'), ['email' => $user->email])
        ->assertRedirect()->assertSessionHasNoErrors();

    expect(Cache::get('forgot-password:'.strtolower($user->email).':request_count'))->toBe(1);
});

// TEST 16: Retry selama blokir tidak memperpanjang blokir dan tidak menambah counter
it('TEST 16: tidak memperpanjang blokir saat user mencoba kembali selama diblokir', function () {
    $user = User::factory()->create(['email' => 'user16@example.com']);
    $key = 'forgot-password:'.strtolower($user->email);

    $this->post(route('password.email'), ['email' => $user->email])->assertRedirect();
    $this->post(route('password.email'), ['email' => $user->email])->assertRedirect();
    $this->post(route('password.email'), ['email' => $user->email])->assertSessionHasErrors(['email']);

    // Retry di menit ke-20: tetap ditolak, counter tidak bertambah.
    $this->travel(20)->minutes();
    $this->post(route('password.email'), ['email' => $user->email])->assertSessionHasErrors(['email']);
    expect(Cache::has($key.':request_count'))->toBeFalse();
    Mail::assertSent(ResetPasswordMail::class, 2);

    // Total 31 menit sejak blokir: blokir berakhir (tidak diperpanjang ke menit 50).
    $this->travel(11)->minutes();
    $this->post(route('password.email'), ['email' => $user->email])
        ->assertRedirect()->assertSessionHasNoErrors();

    Mail::assertSent(ResetPasswordMail::class, 3);
});

// TEST 17: Request yang diblokir tidak membuat token dan tidak mengirim email
it('TEST 17: request terblokir tidak menghasilkan token maupun email', function () {
    $user = User::factory()->create(['email' => 'user17@example.com']);
    $key = 'forgot-password:'.strtolower($user->email);

    $this->post(route('password.email'), ['email' => $user->email])->assertRedirect();
    $this->post(route('password.email'), ['email' => $user->email])->assertRedirect();
    $this->post(route('password.email'), ['email' => $user->email])
        ->assertSessionHasErrors(['email' => 'Terlalu banyak permintaan reset password. Silakan coba lagi setelah 30 menit.']);

    // Counter dibersihkan saat blokir aktif; retry tidak menambah apa pun.
    expect(Cache::has($key.':request_count'))->toBeFalse();
    $this->post(route('password.email'), ['email' => $user->email])->assertSessionHasErrors(['email']);
    expect(Cache::has($key.':request_count'))->toBeFalse();
    Mail::assertSent(ResetPasswordMail::class, 2);
});
