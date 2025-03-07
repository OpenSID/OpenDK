<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Two-Factor Authentication Provider
    |--------------------------------------------------------------------------
    |
    | This option controls the default two-factor authentication provider that
    | will be used by the framework when a user needs to be two-factor
    | authenticated. You may set this to any of the connections defined in the
    | "providers" array below.
    |
    | Supported: "messagebird", "null"
    |
    */

    'default' => env('TWO_FACTOR_AUTH_DRIVER', 'null'),

    /*
    |--------------------------------------------------------------------------
    | Two-Factor Authentication Providers
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the two-factor authentication providers that
    | will be used to two-factor authenticate the user.
    |
    */

    'providers' => [

        'messagebird' => [
            'driver' => 'messagebird',
            'key' => env('MESSAGEBIRD_ACCESS_KEY'),
            'options' => [
                'originator' => 'Me',
                'timeout' => 60,
                'language' => 'nl-nl',
            ],
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Enabled Mode
    |--------------------------------------------------------------------------
    |
    | Options:
    |
    | 'always': Always require two-factor authentication.
    | 'never': Never require two-factor authentication.
    | 'user': Specify manually for which users to enable 2fa.
    |
    */

    'enabled' => 'always',

    /*
    |--------------------------------------------------------------------------
    | Routes Configuration + Naming
    |--------------------------------------------------------------------------
    |
    | Here you may customize the route URL's and in case of the GET route, also
    | the route name.
    |
    */

    'routes' => [

        'namespace' => 'App\Http\Controllers\Auth',

        'get' => [
            'url' => '/auth/token',
            'name' => 'auth.token',
        ],

        'post' => '/auth/token',

    ],

    /*
    |--------------------------------------------------------------------------
    | Model
    |--------------------------------------------------------------------------
    |
    | Here you can optionally specify the user model that should be used. The
    | standard "Eloquent" user model is configured by default.
    |
    */

    'model' => \App\Models\User::class,
    // expired in minutes
    'expiry' => 3,
];
