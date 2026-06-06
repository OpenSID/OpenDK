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

use App\Events\ArtikelChanged;
use App\Listeners\ClearArtikelCacheListener;
use App\Models\Album;
use App\Models\Artikel;
use App\Models\DataDesa;
use App\Models\DataUmum;
use App\Models\Galeri;
use App\Models\MediaSosial;
use App\Models\MediaTerkait;
use App\Models\Penduduk;
use App\Models\Widget;
use App\Observers\AlbumObserver;
use App\Observers\ArtikelObserver;
use App\Observers\GaleriObserver;
use App\Observers\MediaSosialObserver;
use App\Observers\MediaTerkaitObserver;
use App\Observers\WidgetObserver;
use App\Services\CacheService;
use App\Support\Collection;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
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

        // Routing tambahan: API Frontend dengan middleware theme.api
        $this->registerApiFrontendRoutes();

        // Rate Limiter (sebelumnya di RouteServiceProvider)
        $this->configureRateLimiting();

        // Events & Listeners (sebelumnya di EventServiceProvider)
        $this->registerEvents();

        // Model Observers (sebelumnya di EventServiceProvider)
        $this->registerObservers();

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
    }

    /**
     * Registrasi route API Frontend dengan middleware khusus.
     * (Sebelumnya di RouteServiceProvider — route api-frontend tidak bisa diset di withRouting())
     */
    protected function registerApiFrontendRoutes(): void
    {
        Route::middleware('theme.api')
            ->prefix('api/frontend')
            ->group(base_path('routes/api-frontend.php'));
    }

    /**
     * Konfigurasi rate limiter untuk API.
     * (Sebelumnya di RouteServiceProvider::configureRateLimiting())
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    /**
     * Registrasi event dan listener.
     * (Sebelumnya di EventServiceProvider::$listen)
     */
    protected function registerEvents(): void
    {
        Event::listen(
            Registered::class,
            SendEmailVerificationNotification::class
        );

        Event::listen(
            ArtikelChanged::class,
            ClearArtikelCacheListener::class
        );
    }

    /**
     * Registrasi model observer.
     * (Sebelumnya di EventServiceProvider::boot())
     */
    protected function registerObservers(): void
    {
        Album::observe(AlbumObserver::class);
        Artikel::observe(ArtikelObserver::class);
        Galeri::observe(GaleriObserver::class);
        Widget::observe(WidgetObserver::class);
        MediaSosial::observe(MediaSosialObserver::class);
        MediaTerkait::observe(MediaTerkaitObserver::class);
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
     */
    protected function file(): void
    {
        Validator::extend('valid_file', function ($attributes, $value, $parameters) {
            $contains = preg_match('/<\?php|<script|function|__halt_compiler|<html/i', File::get($value));
            if ($contains) {
                return false;
            }

            return true;
        });
    }

    /**
     * Tambahkan macro paginate() ke Collection.
     */
    protected function paginate(): void
    {
        /**
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
