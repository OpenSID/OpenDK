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

namespace App\Providers;

use App\Rules\SafeFileContent;
use App\Services\ActivityLogService;
use App\Services\CacheService;
use App\Support\Collection;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CacheService::class, function () {
            return new CacheService();
        });

        /*
        |----------------------------------------------------------------------
        | Class Aliases (pengganti aliases di config/app.php L10-style)
        |----------------------------------------------------------------------
        | Didaftarkan via AliasLoader karena ApplicationBuilder::withAliases()
        | tidak tersedia di Laravel 13.x.
        */
        $loader = AliasLoader::getInstance();
        $loader->alias('JWTAuth',    \Tymon\JWTAuth\Facades\JWTAuth::class);
        $loader->alias('JWTFactory', \Tymon\JWTAuth\Facades\JWTFactory::class);
        $loader->alias('Captcha',    \Mews\Captcha\Facades\Captcha::class);
        $loader->alias('Counter',    \App\Facades\Counter::class);
        $loader->alias('Html',       \Spatie\Html\Facades\Html::class);
        $loader->alias('Excel',      \Maatwebsite\Excel\Facades\Excel::class);
        $loader->alias('Debugbar',   \Barryvdh\Debugbar\Facades\Debugbar::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Default string length untuk MySQL lama
        Schema::defaultStringLength(191);

        Paginator::useBootstrap();
        $this->paginate();

        $this->configureRateLimiting();

        if (sudahInstal()) {
            $this->penduduk();
            $this->config();
            $this->blade();
            $this->file();
        }

        // Bypass validasi captcha saat unit testing / local
        if (app()->environment('testing') || app()->environment('local')) {
            Validator::extend('captcha', function () {
                return true;
            });
        }

        // Auto-log login
        Event::listen(Login::class, function (Login $event) {
            $user = $event->user;
            $userId = $user ? $user->id : null;
            ActivityLogService::log('login', "User: {$user->name} (ID: {$user->id})", [
                'user_name' => $user ? $user->name : 'Sistem',
                'user_id' => $userId,
                'event' => 'login',
            ]);
        });

        // Auto-log logout
        Event::listen(Logout::class, function (Logout $event) {
            $user = $event->user;
            $userId = $user ? $user->id : null;
            ActivityLogService::log('logout', "User: {$user?->name} (ID: {$userId})", [
                'user_name' => $user ? $user->name : 'Sistem',
                'user_id' => $userId,
                'event' => 'logout',
            ]);
        });

        // Auto-log failed login
        Event::listen(Failed::class, function (Failed $event) {
            $user = $event->user;
            $userId = $user ? $user->id : null;
            ActivityLogService::logFailed('login gagal', "User: {$user?->name} (ID: {$user?->id}) login gagal", [
                'user_name' => $user ? $user->name : 'Sistem',
                'user_id' => $userId,
                'event' => 'login gagal',
                'attempted_username' => $event->username ?? null,
            ], $userId);
        });
    }

    /**
     * Rate limiter untuk login dan OTP (issue #1410).
     * (Sebelumnya di RouteServiceProvider::configureRateLimiting()).
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $maxAttempts = max(1, (int) config('security.rate_limit.login.max_attempts'));
            $decayMinutes = max(1, (int) config('security.rate_limit.login.decay_minutes'));

            $identifier = $this->extractAndValidateIdentifier($request);

            return Limit::perMinute($maxAttempts, $decayMinutes)
                ->by($this->rateLimitKey('login', $request, $identifier));
        });

        RateLimiter::for('otp', function (Request $request) {
            $maxAttempts = max(1, (int) config('security.rate_limit.otp.max_attempts'));
            $decayMinutes = max(1, (int) config('security.rate_limit.otp.decay_minutes'));

            $identifier = $this->extractAndValidateIdentifier($request);

            return Limit::perMinute($maxAttempts, $decayMinutes)
                ->by($this->rateLimitKey('otp', $request, $identifier));
        });
    }

    /**
     * Kunci rate limit: nama limiter + IP klien + identifier.
     *
     * $request->ip() hanya memakai X-Forwarded-For bila request datang dari
     * proxy yang dipercaya (TrustProxies). Jangan membaca header proxy secara
     * langsung: klien bisa mengisinya dengan nilai acak di setiap request
     * untuk mendapat bucket baru dan melewati batas.
     */
    protected function rateLimitKey(string $limiter, Request $request, ?string $identifier): string
    {
        return $limiter.'|'.$request->ip().'|'.($identifier ?? '');
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

    /**
     * Konfigurasi custom validator untuk data penduduk.
     */
    protected function penduduk(): void
    {
        // kecamatan_id harus dihapus pada migrasi database/migrations/2021_10_12_081718_alter_table_das_data_umum.php
        // jumlah_penduduk dll dihapus pada migrasi database/migrations/2021_01_02_055931_dropcolomn_data_umum_table.php

        Validator::extend('nik_exists', function ($attribute, $value, $parameters) {
            $query = DB::table('das_penduduk')
            ->where('nik', $value)
            ->where('tanggal_lahir', $parameters[0])
            ->exists();

            return $query;
        });

        Validator::extend('password_exists', function ($attribute, $value, $parameters) {
            $query = DB::table('das_penduduk')
            ->where('tanggal_lahir', $value)
            ->where('nik', $parameters[0])
            ->exists();

            return $query;
        });

        Validator::extend('unique_key', function ($attribute, $value, $parameters) {
            $query = DB::table($parameters[0])
                ->where('key', $value)
                ->first();

            if (! $query || $query->id == $parameters[1]) {
                return true;
            }

            return false;
        });

        Validator::extend('valid_json', function ($attributes, $value, $parameters) {
            if (! is_string($value)) {
                return false;
            }
            json_decode($value);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return false;
            }

            return true;
        });
    }

    /**
     * Load konfigurasi aplikasi dari database ke dalam config().
     */
    protected function config(): void
    {
        config([
            'setting' => Cache::remember('setting', 24 * 60 * 60, function () {
                return  Schema::hasTable('das_setting')
                    ? DB::table('das_setting')
                    ->get(['key', 'value'])
                    ->keyBy('key')
                    ->transform(function ($setting) {
                        return $setting->value;
                    })
                    : null;
            }),

            'profil' => Cache::remember('profil', 24 * 60 * 60, function () {
                if (Schema::hasTable('das_profil')) {
                    $profil = DB::table('das_profil')
                        ->get()
                        ->map(function ($item) {
                            return (array) $item;
                        })
                        ->first() ?? null;

                    if ($profil) {
                        if (in_array($profil['provinsi_id'], [91, 92])) {
                            $profil['sebutan_wilayah'] = 'Distrik';
                            $profil['sebutan_kepala_wilayah'] = 'Kepala Distrik';
                        } else {
                            $profil['sebutan_wilayah'] = 'Kecamatan';
                            $profil['sebutan_kepala_wilayah'] = 'Camat';
                        }
                    }

                    return $profil;
                }

                return null;
            }),
        ]);
    }

    /**
     * Registrasi Blade directive custom.
     */
    protected function blade(): void
    {
        Blade::directive('selected', function ($condition) {
            return "<?php if({$condition}): echo 'selected'; endif; ?>";
        });
    }

    /**
     * Validator untuk file upload (mencegah file berbahaya).
     *
     * Logika berada di App\Rules\SafeFileContent agar dapat diuji terpisah.
     */
    protected function file(): void
    {
        Validator::extend('valid_file', function ($attributes, $value, $parameters) {
            $failed = false;

            (new SafeFileContent())->validate($attributes, $value, function () use (&$failed) {
                $failed = true;
            });

            return ! $failed;
        });
    }

    /**
     * Tambahkan macro paginate() ke Collection.
     */
    protected function paginate(): void
    {
        /*
         * Paginate a standard Laravel Collection.
         *
         * @param  int  $perPage
         * @param  int  $total
         * @param  int  $page
         * @param  string  $pageName
         */
        Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page'): LengthAwarePaginator {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage)->values(),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }
}
