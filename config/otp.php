<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OTP Expiry Time (in minutes)
    |--------------------------------------------------------------------------
    |
    | Menentukan berapa lama kode OTP akan berlaku sebelum kadaluarsa.
    |
    */
    'expiry_minutes' => env('OTP_EXPIRY_MINUTES', 5),

    /*
    |--------------------------------------------------------------------------
    | Telegram Bot Token
    |--------------------------------------------------------------------------
    |
    | Token bot Telegram yang digunakan untuk mengirim OTP via Telegram.
    | Dapatkan token dari @BotFather di Telegram.
    |
    */
    'telegram_bot_token' => env('TELEGRAM_BOT_TOKEN', ''),

    /*
    |--------------------------------------------------------------------------
    | Maximum Attempts
    |--------------------------------------------------------------------------
    |
    | Jumlah maksimal percobaan verifikasi OTP yang diperbolehkan.
    |
    */
    'max_attempts' => 3,

    /*
    |--------------------------------------------------------------------------
    | Resend Cooldown (in seconds)
    |--------------------------------------------------------------------------
    |
    | Waktu tunggu sebelum pengguna dapat meminta kode OTP baru.
    |
    */
    'resend_cooldown' => 30,
];
