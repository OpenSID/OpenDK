<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Providers;

use App\Helpers\IpAddress;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
            Route::middleware('theme.api')
                ->prefix('api/frontend')
                ->group(base_path('routes/api-frontend.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * Rate limiters yang tersedia:
     * - 'api': Untuk API routes (60 request/menit)
     * - 'login': Untuk login attempts (10 attempt/menit per IP + Email)
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        // API rate limiter - untuk authenticated dan guest users
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: IpAddress::getRealIp($request));
        });

        // Login rate limiter - mencegah brute force attack
        //
        // Key format: {ip}|{email}
        // Contoh: "192.168.1.1|user@example.com"
        //
        // Penggunaan IP + Email sebagai key memberikan keuntungan:
        // 1. Attacker tidak bisa mem-blokir semua user dari IP yang sama
        // 2. Attacker tidak bisa brute force satu email dari multiple IPs
        // 3. Legitimate user tidak terkena limit jika emailnya berbeda
        RateLimiter::for('login', function (Request $request) {
            // Ambil email dari request (bisa dari login form atau OTP request)
            $email = $request->input('email')
                ?? $request->input('username')
                ?? $request->input('identity');

            // Buat key berdasarkan IP asli + email
            $key = IpAddress::getRateLimitKey($request, $email);

            // Konfigurasi limit dari env atau default 10/menit
            $maxAttempts = (int) env('RATE_LIMIT_LOGIN_MAX', 10);
            $decayMinutes = (int) env('RATE_LIMIT_LOGIN_DECAY', 1);

            return Limit::perMinute($maxAttempts)
                ->by($key)
                ->response(function () use ($decayMinutes) {
                    return response()->json([
                        'message' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$decayMinutes} menit.",
                        'error' => 'too_many_attempts',
                    ], 429);
                });
        });

        // OTP rate limiter - mencegah abuse pada OTP request/resend
        RateLimiter::for('otp', function (Request $request) {
            $email = $request->input('email')
                ?? $request->input('username')
                ?? $request->input('identity');

            $key = IpAddress::getRateLimitKey($request, $email);

            // Lebih strict untuk OTP (3 per menit)
            $maxAttempts = (int) env('RATE_LIMIT_OTP_MAX', 3);
            $decayMinutes = (int) env('RATE_LIMIT_OTP_DECAY', 1);

            return Limit::perMinute($maxAttempts)
                ->by($key)
                ->response(function () use ($decayMinutes) {
                    return response()->json([
                        'message' => "Terlalu banyak permintaan OTP. Silakan coba lagi dalam {$decayMinutes} menit.",
                        'error' => 'too_many_otp_attempts',
                    ], 429);
                });
        });
    }
}
