<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Trusted Proxies
    |--------------------------------------------------------------------------
    |
    | Daftar IP/CIDR reverse proxy yang dipercaya (dipisah koma), misalnya
    | Nginx atau Cloudflare. Kosong berarti tidak ada proxy yang dipercaya,
    | sehingga header X-Forwarded-For dari klien diabaikan. Nilai '*' ditolak
    | di App\Http\Middleware\TrustProxies karena memungkinkan IP spoofing.
    |
    */

    'trusted_proxies' => env('TRUST_PROXIES'),

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Login & OTP
    |--------------------------------------------------------------------------
    |
    | Batas percobaan per menit untuk tiap kombinasi IP klien + identitas
    | (email/username). Dibaca di AppServiceProvider::configureRateLimiting().
    |
    */

    'rate_limit' => [
        'login' => [
            'max_attempts' => (int) env('RATE_LIMIT_LOGIN_MAX', 10),
            'decay_minutes' => (int) env('RATE_LIMIT_LOGIN_DECAY', 1),
        ],
        'otp' => [
            'max_attempts' => (int) env('RATE_LIMIT_OTP_MAX', 3),
            'decay_minutes' => (int) env('RATE_LIMIT_OTP_DECAY', 1),
        ],
    ],

];
