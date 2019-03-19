<?php

namespace App\Providers;

use App\Models\DataDesa;
use App\Models\DataUmum;
use App\Models\Penduduk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

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

        Penduduk::saved(function($model){
            $dataUmum = DataUmum::where('kecamatan_id',$model->kecamatan_id)->first();


            $dataUmum->jumlah_penduduk = $model->where('kecamatan_id', $model->kecamatan_id)->count();
            $dataUmum->jml_laki_laki = $model->where('sex', 1)->count();
            $dataUmum->jml_perempuan = $model->where('sex', 2)->count();
            $dataUmum->luas_wilayah = DataDesa::where('kecamatan_id', $model->kecamatan_id)->sum('luas_wilayah');
            $dataUmum->kepadatan_penduduk = ($dataUmum->luas_wilayah == 0)? 0:$dataUmum->jumlah_penduduk /$dataUmum->luas_wilayah;

            $dataUmum->save();
        });

        Penduduk::deleted(function($model){
            $dataUmum = DataUmum::where('kecamatan_id',$model->kecamatan_id)->first();

            $dataUmum->jumlah_penduduk = $model->where('kecamatan_id', $model->kecamatan_id)->count();
            $dataUmum->jml_laki_laki = $model->where('sex', 1)->count();
            $dataUmum->jml_perempuan = $model->where('sex', 2)->count();
            $dataUmum->luas_wilayah = DataDesa::where('kecamatan_id', $model->kecamatan_id)->sum('luas_wilayah');
            $dataUmum->kepadatan_penduduk = ($dataUmum->luas_wilayah == 0)? 0:$dataUmum->jumlah_penduduk /$dataUmum->luas_wilayah;

            $dataUmum->save();
        });

        Validator::extend('nik_exists', function($attribute, $value, $parameters) {

            $query = DB::table('das_penduduk')->
                where('nik', $value)->whereRaw("tanggal_lahir = '".$parameters[0]."'")->exists();

            if($query) return true;
            return false;

        });

        Validator::extend('password_exists', function($attribute, $value, $parameters) {

            $query = DB::table('das_penduduk')->
            where('tanggal_lahir', $value)->whereRaw("nik = '".$parameters[0]."'")->exists();

            if($query) return true;
            return false;

        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
