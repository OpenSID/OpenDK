<?php

use Illuminate\Database\Seeder;

class DasDataUmumTableSeeder extends Seeder
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
                'profil_id' => 1,
                'kecamatan_id' => '53.06.13',
                'tipologi' => 'Kecamatan maju namun terpencil.',
                'ketinggian' => 1,
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
                'jml_mushola' => 1,
                'jml_gereja' => 0,
                'jml_pasar' => 0,
                'jml_balai_pertemuan' => 0,
                'kepadatan_penduduk' => 0,
                'embed_peta' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127672.75772082225!2d100.61093321349074!3d-0.27103862950004254!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e2ab5bbf8396485%3A0x56587edf579fe503!2sLuak%2C+Kabupaten+Lima+Puluh+Kota%2C+Sumatera+Barat!5e0!3m2!1sid!2sid!4v1557908807791!5m2!1sid!2sid',
                'created_at' => '2018-02-03 10:33:43',
                'updated_at' => '2018-07-19 01:35:15',
            ),
        ));


    }
}