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

use App\Models\DataDesa;
use App\Models\DataUmum;
use App\Models\Penduduk;
use App\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use MichaelDzjap\TwoFactorAuth\Providers\EmailTwoFactorProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {}

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // default lengt string
        Schema::defaultStringLength(191);

        Paginator::useBootstrap();
        $this->paginate();

        if (sudahInstal()) {
            $this->penduduk();
            $this->config();
            $this->blade();
            $this->file();
        }

        if (!Type::hasType('tinyinteger')) {
            Type::addType('tinyinteger', 'Doctrine\DBAL\Types\SmallIntType');
            $platform = Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform();
            $platform->markDoctrineTypeCommented(Type::getType('tinyinteger'));
        }

        resolve(\MichaelDzjap\TwoFactorAuth\TwoFactorAuthManager::class)->extend('email', function ($app) {
            return new \App\Providers\EmailTwoFactorProvider();
        });       
        
        // bypass validasi captcha saat unit testing
        if (app()->environment('testing') || app()->environment('local')) {
            Validator::extend('captcha', function () {
                return true; // selalu lolos di environment testing
            });
        }
    }

    protected function penduduk()
    {
        // kecamatan_id harus dihapus pada migrasi database/migrations/2021_10_12_081718_alter_table_das_data_umum.php
        // jumlah_penduduk dll dihapus pada migrasi database/migrations/2021_01_02_055931_dropcolomn_data_umum_table.php           
        // Penduduk::saved(function ($model) {
        //     
        //     $dataUmum = DataUmum::where('kecamatan_id', $model->kecamatan_id)->first();            
        //      
        //     $dataUmum->jumlah_penduduk = $model->where('kecamatan_id', $model->kecamatan_id)->count();
        //     $dataUmum->jml_laki_laki = $model->where('sex', 1)->count();
        //     $dataUmum->jml_perempuan = $model->where('sex', 2)->count();
        //     $dataUmum->luas_wilayah = DataDesa::where('kecamatan_id', $model->kecamatan_id)->sum('luas_wilayah');
        //     $dataUmum->kepadatan_penduduk = $dataUmum->luas_wilayah == 0 ? 0 : $dataUmum->jumlah_penduduk / $dataUmum->luas_wilayah;

        //     $dataUmum->save();
        // });

        // Penduduk::deleted(function ($model) {
        //     $dataUmum = DataUmum::where('kecamatan_id', $model->kecamatan_id)->first();

        //     $dataUmum->jumlah_penduduk = $model->where('kecamatan_id', $model->kecamatan_id)->count();
        //     $dataUmum->jml_laki_laki = $model->where('sex', 1)->count();
        //     $dataUmum->jml_perempuan = $model->where('sex', 2)->count();
        //     $dataUmum->luas_wilayah = DataDesa::where('kecamatan_id', $model->kecamatan_id)->sum('luas_wilayah');
        //     $dataUmum->kepadatan_penduduk = $dataUmum->luas_wilayah == 0 ? 0 : $dataUmum->jumlah_penduduk / $dataUmum->luas_wilayah;

        //     $dataUmum->save();
        // });

        Validator::extend('nik_exists', function ($attribute, $value, $parameters) {
            $query = DB::table('das_penduduk')->where('nik', $value)->whereRaw("tanggal_lahir = '" . $parameters[0] . "'")->exists();

            if ($query) {
                return true;
            }

            return false;
        });

        Validator::extend('password_exists', function ($attribute, $value, $parameters) {
            $query = DB::table('das_penduduk')->where('tanggal_lahir', $value)->whereRaw("nik = '" . $parameters[0] . "'")->exists();

            if ($query) {
                return true;
            }

            return false;
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

    protected function config()
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

    protected function blade()
    {
        Blade::directive('selected', function ($condition) {
            return "<?php if({$condition}): echo 'selected'; endif; ?>";
        });
    }

    protected function file()
    {
        Validator::extend('valid_file', function ($attributes, $value, $parameters) {
            $contains = preg_match('/<\?php|<script|function|__halt_compiler|<html/i', File::get($value));
            if ($contains) {
                return false;
            }

            return true;
        });
    }

    protected function paginate()
    {
        /**
         * Paginate a standard Laravel Collection.
         *
         * @param  int  $perPage
         * @param  int  $total
         * @param  int  $page
         * @param  string  $pageName
         * @return array
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
