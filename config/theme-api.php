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
    | Artikel API Settings
    |--------------------------------------------------------------------------
    */
    'artikel' => [
        'default_per_page' => env('ARTIKEL_API_DEFAULT_PER_PAGE', 15),
        'max_per_page' => env('ARTIKEL_API_MAX_PER_PAGE', 100),
        'cache_prefix' => env('ARTIKEL_API_CACHE_PREFIX', 'artikel:api'),
        'allowed_sort_fields' => [
            'created_at', 'updated_at', 'judul', 'id'
        ],
        'allowed_filters' => [
            'kategori', 'search', 'status'
        ],
        'default_sort' => 'created_at',
        'default_order' => 'desc',
    ],

    /*
    |--------------------------------------------------------------------------
    | Profil API Settings
    |--------------------------------------------------------------------------
    */
    'profil' => [
        'default_per_page' => env('PROFIL_API_DEFAULT_PER_PAGE', 15),
        'max_per_page' => env('PROFIL_API_MAX_PER_PAGE', 100),
        'cache_prefix' => env('PROFIL_API_CACHE_PREFIX', 'profil:api'),
        'allowed_sort_fields' => [
            'nama_kecamatan', 'nama_kabupaten', 'nama_provinsi', 'created_at', 'updated_at', 'id'
        ],
        'allowed_filters' => [
            'nama_kecamatan', 'nama_kabupaten', 'nama_provinsi', 'kecamatan_id', 'kabupaten_id', 'provinsi_id', 'search'
        ],
        'default_sort' => 'nama_kecamatan',
        'default_order' => 'asc',
    ],

    'desa' => [
        'default_per_page' => env('DESA_API_DEFAULT_PER_PAGE', 15),
        'max_per_page' => env('DESA_API_MAX_PER_PAGE', 100),
        'cache_prefix' => env('DESA_API_CACHE_PREFIX', 'desa:api'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Galeri API Settings
    |--------------------------------------------------------------------------
    */
    'galeri' => [
        'default_per_page' => env('GALERI_API_DEFAULT_PER_PAGE', 15),
        'max_per_page' => env('GALERI_API_MAX_PER_PAGE', 100),
        'cache_prefix' => env('GALERI_API_CACHE_PREFIX', 'galeri:api'),
        'allowed_sort_fields' => [
            'judul', 'created_at', 'updated_at', 'id'
        ],
        'allowed_filters' => [
            'status', 'album_id', 'judul'
        ],
        'default_sort' => 'created_at',
        'default_order' => 'desc',
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
