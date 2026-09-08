<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Password Reset Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Batas permintaan link reset password per kombinasi alamat IP + email dan
    | per alamat IP. Nilai disesuaikan lewat file .env agar mudah diubah tanpa
    | menyentuh kode.
    |
    */

    'password_reset' => [
        'max_attempts' => (int) env('PASSWORD_RESET_MAX_ATTEMPTS', 4),
        'decay_minutes' => (int) env('PASSWORD_RESET_DECAY_MINUTES', 1),
        'block_minutes' => (int) env('PASSWORD_RESET_BLOCK_MINUTES', 30),
        'max_attempts_per_ip' => (int) env('PASSWORD_RESET_MAX_ATTEMPTS_PER_IP', 20),
    ],
];
