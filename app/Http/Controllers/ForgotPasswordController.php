<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;
use Throwable;

class ForgotPasswordController extends Controller
{
    /**
     * Show the form to request a password reset link.
     */
    public function showLinkRequestForm(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     */
    public function sendResetLinkEmail(ForgotPasswordRequest $request): JsonResponse|RedirectResponse
    {
        $config = config('security.password_reset');

        $email = strtolower($request->string('email')->trim());
        $ip = $request->ip() ?? 'unknown';

        $accountKey = "forgot_password:{$ip}:{$email}";
        $ipKey = "forgot_password:ip:{$ip}";
        $blockKey = "forgot_password:blocked:{$ip}:{$email}";
        $ipBlockKey = "forgot_password:blocked:ip:{$ip}";

        $windowDecay = $config['decay_minutes'] * 60;
        $blockDecay = $config['block_minutes'] * 60;

        $blocked = RateLimiter::tooManyAttempts($blockKey, 1)
            || RateLimiter::tooManyAttempts($ipBlockKey, 1);

        if (! $blocked) {
            $exceeded = RateLimiter::tooManyAttempts($accountKey, $config['max_attempts'])
                || RateLimiter::tooManyAttempts($ipKey, $config['max_attempts_per_ip']);

            if ($exceeded) {
                RateLimiter::hit($blockKey, $blockDecay);
                RateLimiter::hit($ipBlockKey, $blockDecay);
                $blocked = true;
            } else {
                RateLimiter::hit($accountKey, $windowDecay);
                RateLimiter::hit($ipKey, $windowDecay);
            }
        }

        if ($blocked) {
            $retryAfter = max(
                RateLimiter::availableIn($blockKey),
                RateLimiter::availableIn($ipBlockKey),
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Terlalu banyak permintaan. Silakan coba lagi nanti.',
                    'retry_after' => $retryAfter,
                ], 429);
            }

            return back()->withErrors([
                'email' => 'Terlalu banyak permintaan. Silakan coba lagi beberapa saat lagi.',
            ]);
        }

        try {
            Password::sendResetLink($request->only('email'));
        } catch (Throwable $e) {
            report($e);
        }

        $successMessage = 'Jika email tersebut terdaftar, instruksi reset password telah dikirim. Silakan periksa inbox email kamu.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $successMessage]);
        }

        return back()
            ->with('success', $successMessage)
            ->onlyInput('email');
    }
}
