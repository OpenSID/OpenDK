<?php

use Illuminate\Database\Seeder;

class DasDataUmumAikmelTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('das_data_umum')->delete();
        
        \DB::table('das_data_umum')->insert(array (
            0 => 
            array (
                'id' => 1,
                'kecamatan_id' => '5203090',
                'tipologi' => 'Kecamatan maju namun terpencil.',
                'luas_wilayah' => 0.0,
                'jumlah_penduduk' => 0,
                'jml_laki_laki' => 0,
                'jml_perempuan' => 0,
                'bts_wil_utara' => 'Kecamatan A',
                'bts_wil_timur' => 'Kecamatan B',
                'bts_wil_selatan' => 'Kecamatan C',
                'bts_wil_barat' => 'Kecamatan D',
                'jml_puskesmas' => 0,
                'jml_puskesmas_pembantu' => 0,
                'jml_posyandu' => 0,
                'jml_pondok_bersalin' => 0,
                'jml_paud' => 0,
                'jml_sd' => 0,
                'jml_smp' => 0,
                'jml_sma' => 0,
                'jml_masjid_besar' => 0,
                'jml_gereja' => 0,
                'jml_pasar' => 0,
                'jml_balai_pertemuan' => 0,
                'kepadatan_penduduk' => 0,
                'embed_peta' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126265.70213093884!2d116.44091936337398!3d-8.51848032847141!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dcc35d51fb6c959%3A0xc404fc2927a19d57!2sAikmel%2C+East+Lombok+Regency%2C+West+Nusa+Tenggara!5e0!3m2!1sen!2sid!4v1517936372575',
                'created_at' => '2018-02-03 10:33:43',
                'updated_at' => '2018-02-06 16:59:47',
            ),
        ));
        
        
    }
}