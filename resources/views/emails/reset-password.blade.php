<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password ALGO NATION</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 24px;">
    <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 32px; border: 1px solid #e2e8f0;">
        <h1 style="margin: 0 0 12px; font-size: 22px;">Reset Password ALGO NATION</h1>
        <p>Halo {{ $user->name }},</p>
        <p>Anda menerima email ini karena terdapat permintaan reset password akun ALGO NATION Anda.</p>
        <p style="margin: 24px 0;">
            <a href="{{ $resetUrl }}" style="display: inline-block; background: #111827; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: bold;">Reset Password</a>
        </p>
        <p>Atau salin link berikut ke browser Anda:</p>
        <p style="word-break: break-all;"><a href="{{ $resetUrl }}">{{ $resetUrl }}</a></p>
        <p>Link reset password ini hanya berlaku maksimal 30 menit dan hanya dapat digunakan satu kali.</p>
        <p>Jika Anda tidak merasa melakukan permintaan ini, Anda bisa mengabaikan email ini. Akun Anda tetap aman.</p>
        <p>Terima kasih,<br>{{ config('app.name') }}</p>
    </div>
</body>
</html>
