<?php

use Illuminate\Database\Seeder;

class RefSubSubCoaTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('ref_sub_sub_coa')->delete();
        
        \DB::table('ref_sub_sub_coa')->insert(array (
            0 => 
            array (
                'id' => 1,
                'type_id' => 4,
                'sub_id' => 1,
                'sub_sub_name' => 'Hasil Usaha',
            ),
            1 => 
            array (
                'id' => 2,
                'type_id' => 4,
                'sub_id' => 1,
                'sub_sub_name' => 'Hasil Aset',
            ),
            2 => 
            array (
                'id' => 3,
                'type_id' => 4,
                'sub_id' => 1,
                'sub_sub_name' => 'Swadaya',
            ),
            3 => 
            array (
                'id' => 4,
                'type_id' => 4,
                'sub_id' => 1,
                'sub_sub_name' => 'Lain-lain Pendapatan Asli Desa',
            ),
            4 => 
            array (
                'id' => 1,
                'type_id' => 4,
                'sub_id' => 2,
                'sub_sub_name' => 'Dana Desa',
            ),
            5 => 
            array (
                'id' => 2,
                'type_id' => 4,
                'sub_id' => 2,
                'sub_sub_name' => 'Bagian dari Hasil Pajak dan Retribusi Daerah Kabupaten/kota',
            ),
            6 => 
            array (
                'id' => 3,
                'type_id' => 4,
                'sub_id' => 2,
                'sub_sub_name' => 'Alokasi Dana Desa',
            ),
            7 => 
            array (
                'id' => 4,
                'type_id' => 4,
                'sub_id' => 2,
                'sub_sub_name' => 'Bantuan Keuangan Provinsi',
            ),
            8 => 
            array (
                'id' => 5,
                'type_id' => 4,
                'sub_id' => 2,
                'sub_sub_name' => 'Bantuan Keuangan APBD Kabupaten/Kota',
            ),
            9 => 
            array (
                'id' => 1,
                'type_id' => 4,
                'sub_id' => 3,
                'sub_sub_name' => 'Penerimaan dari Hasil Kerjasama antar Desa ',
            ),
            10 => 
            array (
                'id' => 2,
                'type_id' => 4,
                'sub_id' => 3,
                'sub_sub_name' => 'Penerimaan dari Hasil Kerjasama Desa dengan Pihak Ketiga',
            ),
            11 => 
            array (
                'id' => 3,
                'type_id' => 4,
                'sub_id' => 3,
                'sub_sub_name' => 'Penerimaan dari Bantuan Perusahaan yang berlokasi di Desa',
            ),
            12 => 
            array (
                'id' => 4,
                'type_id' => 4,
                'sub_id' => 3,
                'sub_sub_name' => 'Hibah dan sumbangan dari Pihak Ketiga',
            ),
            13 => 
            array (
                'id' => 5,
                'type_id' => 4,
                'sub_id' => 3,
                'sub_sub_name' => 'Koreksi kesalahan belanja tahun-tahun anggaran sebelumnya yang mengakibatkan penerimaan di kas Desa pada tahun anggaran berjalan',
            ),
            14 => 
            array (
                'id' => 6,
                'type_id' => 4,
                'sub_id' => 3,
                'sub_sub_name' => 'Bunga Bank',
            ),
            15 => 
            array (
                'id' => 9,
                'type_id' => 4,
                'sub_id' => 3,
                'sub_sub_name' => 'Lain-lain pendapatan Desa yang sah',
            ),
            16 => 
            array (
                'id' => 1,
                'type_id' => 5,
                'sub_id' => 1,
                'sub_sub_name' => 'Penghasilan Tetap dan Tunjangan Kepala Desa',
            ),
            17 => 
            array (
                'id' => 2,
                'type_id' => 5,
                'sub_id' => 1,
                'sub_sub_name' => 'Penghasilan Tetap dan Tunjangan Perangkat Desa',
            ),
            18 => 
            array (
                'id' => 3,
                'type_id' => 5,
                'sub_id' => 1,
                'sub_sub_name' => 'Jaminan Sosial Kepala Desa dan Perangkat Desa',
            ),
            19 => 
            array (
                'id' => 4,
                'type_id' => 5,
                'sub_id' => 1,
                'sub_sub_name' => 'Tunjangan BPD',
            ),
            20 => 
            array (
                'id' => 2,
                'type_id' => 5,
                'sub_id' => 2,
                'sub_sub_name' => 'Belanja Jasa Honorarium',
            ),
            21 => 
            array (
                'id' => 3,
                'type_id' => 5,
                'sub_id' => 2,
                'sub_sub_name' => 'Belanja Perjalanan Dinas',
            ),
            22 => 
            array (
                'id' => 4,
                'type_id' => 5,
                'sub_id' => 2,
                'sub_sub_name' => 'Belanja Jasa Sewa',
            ),
            23 => 
            array (
                'id' => 5,
                'type_id' => 5,
                'sub_id' => 2,
                'sub_sub_name' => 'Belanja Operasional Perkantoran',
            ),
            24 => 
            array (
                'id' => 6,
                'type_id' => 5,
                'sub_id' => 2,
                'sub_sub_name' => 'Belanja Pemeliharaan',
            ),
            25 => 
            array (
                'id' => 7,
                'type_id' => 5,
                'sub_id' => 2,
                'sub_sub_name' => 'Belanja Barang dan Jasa yang Diserahkan kepada Masyarakat',
            ),
            26 => 
            array (
                'id' => 1,
                'type_id' => 5,
                'sub_id' => 3,
                'sub_sub_name' => 'Belanja Modal Pengadaan Tanah',
            ),
            27 => 
            array (
                'id' => 2,
                'type_id' => 5,
                'sub_id' => 3,
                'sub_sub_name' => 'Belanja Modal Peralatan, Mesin, dan Alat Berat',
            ),
            28 => 
            array (
                'id' => 3,
                'type_id' => 5,
                'sub_id' => 3,
                'sub_sub_name' => 'Belanja Modal Kendaraan ',
            ),
            29 => 
            array (
                'id' => 4,
                'type_id' => 5,
                'sub_id' => 3,
                'sub_sub_name' => 'Belanja Modal Gedung, Bangunan dan Taman',
            ),
            30 => 
            array (
                'id' => 5,
                'type_id' => 5,
                'sub_id' => 3,
                'sub_sub_name' => 'Belanja Modal Jalan/Prasarana Jalan',
            ),
            31 => 
            array (
                'id' => 6,
                'type_id' => 5,
                'sub_id' => 3,
                'sub_sub_name' => 'Belanja Modal Jembatan',
            ),
            32 => 
            array (
                'id' => 7,
                'type_id' => 5,
                'sub_id' => 3,
                'sub_sub_name' => 'Belanja Modal Irigasi/Embung/Air Sungai/Drainase/Air Limbah/Persampahan',
            ),
            33 => 
            array (
                'id' => 8,
                'type_id' => 5,
                'sub_id' => 3,
                'sub_sub_name' => 'Belanja Modal Jaringan/Instalasi',
            ),
            34 => 
            array (
                'id' => 9,
                'type_id' => 5,
                'sub_id' => 3,
                'sub_sub_name' => 'Belanja Modal lainnya',
            ),
            35 => 
            array (
                'id' => 1,
                'type_id' => 5,
                'sub_id' => 4,
                'sub_sub_name' => 'Belanja Tak Terduga',
            ),
            36 => 
            array (
                'id' => 1,
                'type_id' => 6,
                'sub_id' => 1,
                'sub_sub_name' => 'SILPA Tahun Sebelumya',
            ),
            37 => 
            array (
                'id' => 2,
                'type_id' => 6,
                'sub_id' => 1,
                'sub_sub_name' => 'Pencairan Dana Cadangan',
            ),
            38 => 
            array (
                'id' => 3,
                'type_id' => 6,
                'sub_id' => 1,
                'sub_sub_name' => 'Hasil Penjualan Kekayaan Desa yang Dipisahkan',
            ),
            39 => 
            array (
                'id' => 9,
                'type_id' => 6,
                'sub_id' => 1,
                'sub_sub_name' => 'Penerimaan Pembiayaan Lainnya',
            ),
            40 => 
            array (
                'id' => 1,
                'type_id' => 6,
                'sub_id' => 2,
                'sub_sub_name' => 'Pembentukan Dana Cadangan',
            ),
            41 => 
            array (
                'id' => 2,
                'type_id' => 6,
                'sub_id' => 2,
                'sub_sub_name' => 'Penyertaan Modal Desa',
            ),
            42 => 
            array (
                'id' => 9,
                'type_id' => 6,
                'sub_id' => 2,
                'sub_sub_name' => 'Pengeluaran Pembiayaan lainnya',
            ),
        ));
        
        
    }
}