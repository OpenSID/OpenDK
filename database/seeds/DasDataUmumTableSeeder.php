<?php

use App\Models\DataUmum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DasDataUmumTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('das_data_umum')->insert([
            'profil_id'              => env('DATA_UMUM_PROFIL_ID'),
            'kecamatan_id'           => config('app.default_profile'),
            'tipologi'               => env('DATA_UMUM_TIPOLOGI'),
            'ketinggian'             => env('DATA_UMUM_KETINGGIAN'),
            'luas_wilayah'           => env('DATA_UMUM_LUAS_WILAYAH'),
            'jumlah_penduduk'        => 0,
            'jml_laki_laki'          => 0,
            'jml_perempuan'          => 0,
            'bts_wil_utara'          => env('DATA_UMUM_BTS_WIL_UTARA'),
            'bts_wil_timur'          => env('DATA_UMUM_BTS_WIL_TIMUR'),
            'bts_wil_selatan'        => env('DATA_UMUM_BTS_WIL_SELATAN'),
            'bts_wil_barat'          => env('DATA_UMUM_BTS_WIL_BARAT'),
            'jml_puskesmas'          => env('DATA_UMUM_JML_PUSKESMAS'),
            'jml_puskesmas_pembantu' => env('DATA_UMUM_JML_PUSKESMAS_PEMBANTU'),
            'jml_posyandu'           => env('DATA_UMUM_JML_POSYANDU'),
            'jml_pondok_bersalin'    => env('DATA_UMUM_JML_PONDOK_BERSALIN'),
            'jml_paud'               => env('DATA_UMUM_JML_PAUD'),
            'jml_sd'                 => env('DATA_UMUM_JML_SD'),
            'jml_smp'                => env('DATA_UMUM_JML_SMP'),
            'jml_sma'                => env('DATA_UMUM_JML_SMA'),
            'jml_masjid_besar'       => env('DATA_UMUM_JML_MASJID_BESAR'),
            'jml_mushola'            => env('DATA_UMUM_JML_MUSHOLA'),
            'jml_gereja'             => env('DATA_UMUM_JML_GEREJA'),
            'jml_pasar'              => env('DATA_UMUM_JML_PASAR'),
            'jml_balai_pertemuan'    => env('DATA_UMUM_JML_BALAI_PERTEMUAN'),
            'kepadatan_penduduk'     => env('DATA_UMUM_JML_PENDUDUK'),
            'embed_peta'             => env('DATA_UMUM_EMBED_PETA'),
            'created_at'             => now(),
            'updated_at'             => now(),
        ]);
    }
}
