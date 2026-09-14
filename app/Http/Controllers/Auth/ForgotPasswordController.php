<?php

namespace App\Http\Controllers\Auth;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    protected const RATE_LIMIT_KEY_PREFIX = 'forgot-password';
    protected const RATE_LIMIT_WINDOW_SECONDS = 60;
    protected const MAX_REQUESTS_PER_WINDOW = 2;
    protected const BLOCK_DURATION_SECONDS = 1800;
    protected const TOKEN_TTL_MINUTES = 30;

    // Perlindungan tambahan per IP (tidak menggantikan rate limit email).
    protected const IP_RATE_LIMIT_WINDOW_SECONDS = 60;
    protected const IP_MAX_REQUESTS_PER_WINDOW = 10;
    protected const IP_BLOCK_DURATION_SECONDS = 600;

    protected const BLOCKED_MESSAGE = 'Terlalu banyak permintaan reset password. Silakan coba lagi setelah 30 menit.';

    public function showLinkRequestForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        // 1. Validasi input.
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        // 2. Normalisasi email (lowercase + trim) sebagai identifier rate limit.
        $email = strtolower(trim($validated['email']));
        $ip = (string) $request->ip();

        // 3. Cek blokir 30 menit (email adalah aturan utama).
        //    Selama diblokir: tolak tanpa menyentuh counter/token/email,
        //    dan jangan memperpanjang waktu blokir.
        if (Cache::has($this->rateLimitKey($email, 'blocked_until'))) {
            throw ValidationException::withMessages([
                'email' => self::BLOCKED_MESSAGE,
            ]);
        }

        // 3b. Cek blokir IP tambahan. Sama: tolak tanpa efek samping.
        if (Cache::has($this->ipRateLimitKey($ip, 'blocked_until'))) {
            throw ValidationException::withMessages([
                'email' => self::BLOCKED_MESSAGE,
            ]);
        }

        // 4. Hitung hanya request BERHASIL dalam 60 detik terakhir (sliding window).
        $this->pruneExpiredRequests($email);
        $this->pruneExpiredIpRequests($ip);
        $requestCount = (int) Cache::get($this->rateLimitKey($email, 'request_count'), 0);
        $ipRequestCount = (int) Cache::get($this->ipRateLimitKey($ip, 'request_count'), 0);

        // 5. Request ketiga dalam 1 menit: DITOLAK, tanpa token baru,
        //    tanpa email, lalu aktifkan blokir email 30 menit.
        if ($requestCount >= self::MAX_REQUESTS_PER_WINDOW) {
            $this->activateBlock($email);

            throw ValidationException::withMessages([
                'email' => self::BLOCKED_MESSAGE,
            ]);
        }

        // 5b. Spam lintas-email dari satu IP: tolak + blokir IP (tanpa token/email).
        if ($ipRequestCount >= self::IP_MAX_REQUESTS_PER_WINDOW) {
            $this->activateIpBlock($ip);

            throw ValidationException::withMessages([
                'email' => self::BLOCKED_MESSAGE,
            ]);
        }

        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

        // Anti enumeration: response umum, tanpa token, tanpa email.
        // Email tak terdaftar tidak mengirim apa pun sehingga tidak dicatat.
        if (! $user) {
            return back()->with('status', 'Jika email tersebut terdaftar, link reset password akan dikirim ke email tersebut.');
        }

        // 6. Buat token cryptographically secure (bukan ID/email/password).
        //    Disimpan sebagai hash SHA-256 di Cache (bukan plaintext),
        //    TTL 30 menit, tanpa tabel database baru.
        $token = bin2hex(random_bytes(32));
        $tokenKey = 'password-reset:'.hash('sha256', $token);

        Cache::put($tokenKey, [
            'user_id' => $user->id,
            'email' => $user->email,
            'used_at' => null,
            'expires_at' => now()->addMinutes(self::TOKEN_TTL_MINUTES)->toDateTimeString(),
            'created_at' => now()->toDateTimeString(),
        ], now()->addMinutes(self::TOKEN_TTL_MINUTES));

        // 7. Kirim email dengan Laravel Mail + SMTP Resend (kredensial di .env).
        try {
            $resetUrl = url('/reset-password/'.$token.'?email='.urlencode($user->email));

            Mail::to($user->email)->send(new ResetPasswordMail($user, $resetUrl));
        } catch (\Throwable $e) {
            // SMTP gagal: hapus token, JANGAN catat request,
            // JANGAN tambah counter, JANGAN aktifkan blokir.
            Cache::forget($tokenKey);

            Log::error('Password reset email failed.');

            return back()->withErrors([
                'email' => 'Tidak dapat mengirim link reset password saat ini. Silakan coba lagi nanti.',
            ])->onlyInput('email');
        }

        // 8. Hanya SETELAH Mail::send() berhasil: catat request berhasil
        //    pada bucket email DAN bucket IP.
        $this->recordRequest($email);
        $this->recordIpRequest($ip);

        return back()->with('status', 'Jika email tersebut terdaftar, link reset password akan dikirim ke email tersebut.');
    }

    protected function rateLimitKey(string $email, string $suffix): string
    {
        return self::RATE_LIMIT_KEY_PREFIX.':'.strtolower(trim($email)).':'.$suffix;
    }

    protected function ipRateLimitKey(string $ip, string $suffix): string
    {
        return self::RATE_LIMIT_KEY_PREFIX.':ip:'.trim($ip).':'.$suffix;
    }

    protected function pruneExpiredRequests(string $email): void
    {
        $key = $this->rateLimitKey($email, 'request_timestamps');
        $timestamps = Cache::get($key, []);

        if (! is_array($timestamps)) {
            Cache::forget($key);
            Cache::forget($this->rateLimitKey($email, 'request_count'));

            return;
        }

        $cutoff = now()->subSeconds(self::RATE_LIMIT_WINDOW_SECONDS);
        $filtered = array_values(array_filter($timestamps, static fn ($timestamp) => Carbon::parse($timestamp)->greaterThan($cutoff)));

        if ($filtered !== $timestamps) {
            if (empty($filtered)) {
                Cache::forget($key);
                Cache::forget($this->rateLimitKey($email, 'request_count'));

                return;
            }

            Cache::put($key, $filtered, self::RATE_LIMIT_WINDOW_SECONDS);
            Cache::put($this->rateLimitKey($email, 'request_count'), count($filtered), self::RATE_LIMIT_WINDOW_SECONDS);
        }
    }

    protected function pruneExpiredIpRequests(string $ip): void
    {
        $key = $this->ipRateLimitKey($ip, 'request_timestamps');
        $timestamps = Cache::get($key, []);

        if (! is_array($timestamps)) {
            Cache::forget($key);
            Cache::forget($this->ipRateLimitKey($ip, 'request_count'));

            return;
        }

        $cutoff = now()->subSeconds(self::IP_RATE_LIMIT_WINDOW_SECONDS);
        $filtered = array_values(array_filter($timestamps, static fn ($timestamp) => Carbon::parse($timestamp)->greaterThan($cutoff)));

        if ($filtered !== $timestamps) {
            if (empty($filtered)) {
                Cache::forget($key);
                Cache::forget($this->ipRateLimitKey($ip, 'request_count'));

                return;
            }

            Cache::put($key, $filtered, self::IP_RATE_LIMIT_WINDOW_SECONDS);
            Cache::put($this->ipRateLimitKey($ip, 'request_count'), count($filtered), self::IP_RATE_LIMIT_WINDOW_SECONDS);
        }
    }

    protected function recordRequest(string $email): void
    {
        $key = $this->rateLimitKey($email, 'request_timestamps');
        $timestamps = Cache::get($key, []);
        if (! is_array($timestamps)) {
            $timestamps = [];
        }

        $timestamps[] = now()->toDateTimeString();
        Cache::put($key, $timestamps, self::RATE_LIMIT_WINDOW_SECONDS);
        Cache::put($this->rateLimitKey($email, 'request_count'), count($timestamps), self::RATE_LIMIT_WINDOW_SECONDS);
    }

    protected function recordIpRequest(string $ip): void
    {
        $key = $this->ipRateLimitKey($ip, 'request_timestamps');
        $timestamps = Cache::get($key, []);
        if (! is_array($timestamps)) {
            $timestamps = [];
        }

        $timestamps[] = now()->toDateTimeString();
        Cache::put($key, $timestamps, self::IP_RATE_LIMIT_WINDOW_SECONDS);
        Cache::put($this->ipRateLimitKey($ip, 'request_count'), count($timestamps), self::IP_RATE_LIMIT_WINDOW_SECONDS);
    }

    protected function activateBlock(string $email): void
    {
        Cache::put(
            $this->rateLimitKey($email, 'blocked_until'),
            now()->addSeconds(self::BLOCK_DURATION_SECONDS)->toDateTimeString(),
            self::BLOCK_DURATION_SECONDS
        );
        Cache::forget($this->rateLimitKey($email, 'request_timestamps'));
        Cache::forget($this->rateLimitKey($email, 'request_count'));
    }

    protected function activateIpBlock(string $ip): void
    {
        Cache::put(
            $this->ipRateLimitKey($ip, 'blocked_until'),
            now()->addSeconds(self::IP_BLOCK_DURATION_SECONDS)->toDateTimeString(),
            self::IP_BLOCK_DURATION_SECONDS
        );
        Cache::forget($this->ipRateLimitKey($ip, 'request_timestamps'));
        Cache::forget($this->ipRateLimitKey($ip, 'request_count'));
    }
}
