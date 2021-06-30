<?php

namespace App\Providers;

use App\Models\DataDesa;
use App\Models\DataUmum;
use App\Models\Penduduk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // default lengt string
        Schema::defaultStringLength(191);

        Penduduk::saved(function ($model) {
            $dataUmum = DataUmum::where('kecamatan_id', $model->kecamatan_id)->first();

            $dataUmum->jumlah_penduduk    = $model->where('kecamatan_id', $model->kecamatan_id)->count();
            $dataUmum->jml_laki_laki      = $model->where('sex', 1)->count();
            $dataUmum->jml_perempuan      = $model->where('sex', 2)->count();
            $dataUmum->luas_wilayah       = DataDesa::where('kecamatan_id', $model->kecamatan_id)->sum('luas_wilayah');
            $dataUmum->kepadatan_penduduk = $dataUmum->luas_wilayah == 0 ? 0 : $dataUmum->jumlah_penduduk / $dataUmum->luas_wilayah;

            $dataUmum->save();
        });

        Penduduk::deleted(function ($model) {
            $dataUmum = DataUmum::where('kecamatan_id', $model->kecamatan_id)->first();

            $dataUmum->jumlah_penduduk    = $model->where('kecamatan_id', $model->kecamatan_id)->count();
            $dataUmum->jml_laki_laki      = $model->where('sex', 1)->count();
            $dataUmum->jml_perempuan      = $model->where('sex', 2)->count();
            $dataUmum->luas_wilayah       = DataDesa::where('kecamatan_id', $model->kecamatan_id)->sum('luas_wilayah');
            $dataUmum->kepadatan_penduduk = $dataUmum->luas_wilayah == 0 ? 0 : $dataUmum->jumlah_penduduk / $dataUmum->luas_wilayah;

            $dataUmum->save();
        });

        Validator::extend('nik_exists', function ($attribute, $value, $parameters) {
            $query = DB::table('das_penduduk')->
                where('nik', $value)->whereRaw("tanggal_lahir = '" . $parameters[0] . "'")->exists();

            if ($query) {
                return true;
            }
            return false;
        });

        Validator::extend('password_exists', function ($attribute, $value, $parameters) {
            $query = DB::table('das_penduduk')->
            where('tanggal_lahir', $value)->whereRaw("nik = '" . $parameters[0] . "'")->exists();

            if ($query) {
                return true;
            }
            return false;
        });

        Validator::extend('unique_key', function ($attribute, $value, $parameters) {
            $query = DB::table($parameters[0])
                ->where('key', $value)
                ->exists();

            if (!$query) {
                return true;
            }
            return false;
        });

        Validator::extend('valid_json', function ($attributes, $value, $parameters) {
            $json_string = $value;
        
            if(!is_string($json_string)) {
                return false;
            }
            json_encode($json_string);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return false;
            }
            return true;
        });
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
