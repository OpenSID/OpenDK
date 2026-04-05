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
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('login', function (Request $request) {
            $maxAttempts = max(1, (int) env('RATE_LIMIT_LOGIN_MAX', 10));
            $decayMinutes = max(1, (int) env('RATE_LIMIT_LOGIN_DECAY', 1));

            $identifier = $this->extractAndValidateIdentifier($request);

            return Limit::perMinute($maxAttempts, $decayMinutes)
                ->by(IpAddress::getRateLimitKey($request, $identifier));
        });

        RateLimiter::for('otp', function (Request $request) {
            $maxAttempts = max(1, (int) env('RATE_LIMIT_OTP_MAX', 3));
            $decayMinutes = max(1, (int) env('RATE_LIMIT_OTP_DECAY', 1));

            $identifier = $this->extractAndValidateIdentifier($request);

            return Limit::perMinute($maxAttempts, $decayMinutes)
                ->by(IpAddress::getRateLimitKey($request, $identifier));
        });
    }

    /**
     * Extract dan validasi identifier (email/username) dari request.
     *
     * Validasi:
     * - Harus string, bukan array atau tipe lain
     * - Batasi panjang maksimal 320 karakter (RFC 5321)
     * - Hapus null bytes dan control characters
     * - Validasi format email jika terdeteksi sebagai email
     */
    protected function extractAndValidateIdentifier(Request $request): ?string
    {
        $identifier = $request->input('email')
            ?? $request->input('username')
            ?? $request->input('identity')
            ?? $request->input('identifier')
            ?? data_get($request->session()->get('two-factor:auth'), 'email')
            ?? data_get($request->session()->get('two-factor:auth'), 'id')
            ?? data_get($request->session()->get('otp_login'), 'user_id');

        // Return null jika tidak ada
        if ($identifier === null) {
            return null;
        }

        // Validasi tipe data - harus string
        if (! is_string($identifier)) {
            return null;
        }

        // Batasi panjang maksimal (RFC 5321: 320 characters)
        if (strlen($identifier) > 320) {
            $identifier = substr($identifier, 0, 320);
        }

        // Hapus null bytes dan control characters
        $identifier = preg_replace('/[\x00-\x1F\x7F]/', '', $identifier);

        if ($identifier === null) {
            return null;
        }

        $identifier = mb_strtolower(trim($identifier));

        // Hanya izinkan karakter yang aman untuk rate limit key
        $result = preg_replace('/[^a-z0-9@._-]/', '', $identifier);

        return ($result !== null && $result !== '') ? $result : null;
    }
}
