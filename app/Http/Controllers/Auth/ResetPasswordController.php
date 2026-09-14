<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    public function showResetForm(string $token, Request $request): View|RedirectResponse
    {
        $email = strtolower((string) $request->query('email', ''));

        $payload = $this->tokenPayload($token);

        if (! $this->tokenIsValid($payload, $email ?: null)) {
            return redirect()->route('password.request')->withErrors([
                'email' => 'Link reset password tidak valid atau sudah kedaluwarsa.',
            ]);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => strtolower((string) $payload['email']),
        ]);
    }

    public function reset(Request $request, string $token): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $email = strtolower(trim($validated['email']));
        $payload = $this->tokenPayload($token);

        if (! $this->tokenIsValid($payload, $email)) {
            throw ValidationException::withMessages([
                'email' => 'Link reset password tidak valid atau sudah kedaluwarsa.',
            ]);
        }

        $user = User::find($payload['user_id']);
        if (! $user) {
            $user = User::whereRaw('LOWER(email) = ?', [$email])->first();
        }

        if (! $user || strtolower((string) $user->email) !== strtolower((string) $payload['email'])) {
            throw ValidationException::withMessages([
                'email' => 'Link reset password tidak valid atau sudah kedaluwarsa.',
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        // Token sekali pakai: langsung tidak valid setelah berhasil.
        Cache::forget($this->tokenKey($token));

        event(new PasswordReset($user));

        return redirect()->route('login')->with('status', 'Password berhasil diubah. Silakan login menggunakan password baru.');
    }

    protected function tokenKey(string $token): string
    {
        return 'password-reset:'.hash('sha256', $token);
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function tokenPayload(string $token): ?array
    {
        // Tolak format token yang jelas tidak valid tanpa query cache.
        if ($token === '' || strlen($token) > 256 || ! preg_match('/^[a-f0-9]+$/i', $token)) {
            return null;
        }

        $payload = Cache::get($this->tokenKey($token));

        return is_array($payload) ? $payload : null;
    }

    /**
     * @param array<string, mixed>|null $payload
     */
    protected function tokenIsValid(?array $payload, ?string $email): bool
    {
        if (! is_array($payload) || ! isset($payload['email'], $payload['expires_at'], $payload['user_id'])) {
            return false;
        }

        // Sudah digunakan -> tidak valid.
        if (isset($payload['used_at']) && $payload['used_at'] !== null) {
            return false;
        }

        // Kedaluwarsa (maks 30 menit) -> hapus + tidak valid.
        try {
            if (now()->isAfter($payload['expires_at'])) {
                return false;
            }
        } catch (\Throwable) {
            return false;
        }

        // Token terikat ke email pemiliknya.
        if ($email !== null && $email !== '' && strtolower((string) $payload['email']) !== strtolower($email)) {
            return false;
        }

        return true;
    }
}
