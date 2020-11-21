<?php

namespace App\Http;

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\KDInstalled;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\SentinelAuth;
use App\Http\Middleware\SentinelHasAccess;
use App\Http\Middleware\SentinelRedirectIfAuthenticated;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        CheckForMaintenanceMode::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
        TrustProxies::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
        ],
        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'            => Authenticate::class,
        'auth.basic'      => AuthenticateWithBasicAuth::class,
        'bindings'        => SubstituteBindings::class,
        'can'             => Authorize::class,
        'guest'           => RedirectIfAuthenticated::class,
        'throttle'        => ThrottleRequests::class,
        'sentinel_auth'   => SentinelAuth::class,
        'sentinel_access' => SentinelHasAccess::class,
        'sentinel_guest'  => SentinelRedirectIfAuthenticated::class,
        'check_role'      => CheckRole::class,
        'installed'       => KDInstalled::class,
    ];
}
