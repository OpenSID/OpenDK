<?php
return [
    'admin_auth' => env('LARAPOLL_ADMIN_AUTH_MIDDLEWARE', 'sentinel_access'),
    'admin_guard' => env('LARAPOLL_ADMIN_AUTH_GUARD', 'web'),
    'pagination' => env('LARAPOLL_PAGINATION', 15),
    'prefix' => env('LARAPOLL_PREFIX', 'admin_polls'),
    'results' => 'results',
    'radio' => 'radioview',
    'checkbox' => 'checkview',
    'user_model' => App\Models\User::class,
];
