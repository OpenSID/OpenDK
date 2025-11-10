<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'enabled' => env('API_CACHE_ENABLED', true),
        'duration' => env('API_CACHE_DURATION', 3600), // dalam detik (1 jam)
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limit Settings
    |--------------------------------------------------------------------------
    */
    'rate_limit' => [
        'per_minute' => env('API_RATE_LIMIT_PER_MINUTE', 120),
    ],

    /*
    |--------------------------------------------------------------------------
    | Display Data Settings
    |--------------------------------------------------------------------------
    */
    'display' => [
        'allowed_fields' => [
            'nama', 'email', 'status', // contoh
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme and Endpoint Settings
    |--------------------------------------------------------------------------
    */
    'theme' => [
        'active_custom' => env('API_ACTIVE_CUSTOM_THEME', 'modern'),
        'use_custom_endpoint' => env('API_USE_CUSTOM_ENDPOINT_THEME', true),
    ],

];
