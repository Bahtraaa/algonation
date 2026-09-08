<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    /**
     * Show the form to reset the user's password.
     *
     * Validates the reset token against the database and renders the
     * appropriate state (form, expired, or invalid) without leaking
     * information about whether an email address is registered.
     */
    public function showResetForm(string $token, Request $request): View|RedirectResponse
    {
        $email = $request->query('email');

        if (! is_string($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return view('auth.reset-password', ['state' => 'invalid']);
        }

        /** @var PasswordBroker $broker */
        $broker = Password::broker();

        $user = $broker->getUser(['email' => $email]);

        if ($user === null) {
            return view('auth.reset-password', ['state' => 'invalid']);
        }

        if ($broker->tokenExists($user, $token)) {
            return view('auth.reset-password', [
                'state' => 'form',
                'token' => $token,
                'email' => $email,
            ]);
        }

        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if ($record !== null) {
            $expiresAt = Carbon::parse($record->created_at)
                ->addMinutes((int) config('auth.passwords.users.expire', 60));

            if (Carbon::now()->greaterThan($expiresAt)) {
                return view('auth.reset-password', ['state' => 'expired']);
            }
        }

        return view('auth.reset-password', ['state' => 'invalid']);
    }

    /**
     * Handle an incoming new password request.
     */
    public function reset(ResetPasswordRequest $request): RedirectResponse
    {
        $status = Password::reset(
            $request->only('token', 'email', 'password'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();

                if (Schema::hasTable('sessions')) {
                    DB::table('sessions')->where('user_id', $user->getAuthIdentifier())->delete();
                }

                event(new PasswordReset($user));

                Log::info('Password reset completed.', ['user_id' => $user->getAuthIdentifier()]);
            },
        );

        $request->session()->regenerate();

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('password_reset_success', true);
        }

        return back()
            ->withErrors(['email' => 'Link reset password tidak valid atau sudah tidak dapat digunakan. Silakan meminta link reset password baru.'])
            ->onlyInput('email');
    }
}
