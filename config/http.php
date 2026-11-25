<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Outbound HTTP Timeouts
    |--------------------------------------------------------------------------
    |
    | Batas waktu terpusat untuk setiap pemanggilan HTTP keluar (API pihak
    | ketiga, Telegram, server pantau, dll). Digunakan oleh App\Support\OutboundHttp.
    |
    */

    'timeout' => (int) env('HTTP_TIMEOUT', 10),

    'connect_timeout' => (int) env('HTTP_CONNECT_TIMEOUT', 5),

    // Server TTE dapat lambat, sehingga memakai batas waktu yang lebih panjang.
    'tte_timeout' => (int) env('HTTP_TTE_TIMEOUT', 60),

];
