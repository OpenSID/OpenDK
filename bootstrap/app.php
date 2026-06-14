<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
    )
    ->withProviders([
        \App\Providers\ApiFrontendRouteServiceProvider::class,
    ])
    ->withCommands([
        __DIR__.'/../app/Console/Commands',
    ])
    ->withMiddleware(function (Middleware $middleware) {
        /*
        |----------------------------------------------------------------------
        | Global HTTP Middleware
        |----------------------------------------------------------------------
        | Middleware berikut dijalankan pada setiap request ke aplikasi.
        | (Sebelumnya di App\Http\Kernel::$middleware)
        */
        $middleware->append([
            \App\Http\Middleware\TrustProxies::class,
            \Illuminate\Http\Middleware\HandleCors::class,
            \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \App\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ]);

        /*
        |----------------------------------------------------------------------
        | Web Middleware Group — tambahan
        |----------------------------------------------------------------------
        | Middleware ini di-append ke group 'web' yang sudah ada secara default.
        | (Sebelumnya di App\Http\Kernel::$middlewareGroups['web'])
        */
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\GlobalShareMiddleware::class,
        ]);

        /*
        |----------------------------------------------------------------------
        | Middleware Alias
        |----------------------------------------------------------------------
        | Daftar alias middleware yang bisa digunakan di route.
        | (Sebelumnya di App\Http\Kernel::$routeMiddleware)
        */
        $middleware->alias([
            'installed'         => \App\Http\Middleware\KDInstalled::class,
            'maintenance'       => \App\Http\Middleware\MaintenanceMode::class,
            'action_permission' => \App\Http\Middleware\CheckActionPermission::class,
            'xss_sanitization'  => \App\Http\Middleware\XssSanitization::class,
            'complete_profile'  => \App\Http\Middleware\CompleteProfile::class,
            'token.registered'  => \App\Http\Middleware\TokenRegistered::class,
            'track.visitors'    => \App\Http\Middleware\TrackVisitors::class,
            'otp.enabled'       => \App\Http\Middleware\CheckOtpEnabled::class,
            'theme.api'         => \App\Http\Middleware\ThemeApiMiddleware::class,
            'role'              => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'        => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission'=> \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        /*
        |----------------------------------------------------------------------
        | Middleware Priority
        |----------------------------------------------------------------------
        | Urutan eksekusi middleware non-global yang dipaksakan.
        | (Sebelumnya di App\Http\Kernel::$middlewarePriority)
        */
        $middleware->priority([
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\Authenticate::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class,
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Illuminate\Auth\Middleware\Authorize::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);

        /*
        |----------------------------------------------------------------------
        | Rate Limiting — API
        |----------------------------------------------------------------------
        | Rate limiter untuk grup 'api': 60 request per menit per user/IP.
        | (Sebelumnya di AppServiceProvider::configureRateLimiting())
        */
        $middleware->api(prepend: [
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':60',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        /*
        |----------------------------------------------------------------------
        | Exception Handling
        |----------------------------------------------------------------------
        | Konfigurasi penanganan exception aplikasi.
        | (Sebelumnya di App\Exceptions\Handler)
        */
        $exceptions->dontFlash([
            'password',
            'password_confirmation',
        ]);

        // Uncomment untuk mengaktifkan Sentry error reporting:
        // $exceptions->report(function (Throwable $e) {
        //     \Sentry\Laravel\Integration::captureUnhandledException($e);
        // });
    })
    ->create();
