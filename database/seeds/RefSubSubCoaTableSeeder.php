<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @package	    OpenDK
 * @author	    Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2017 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

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
        DB::table('ref_sub_sub_coa')->truncate();

        DB::table('ref_sub_sub_coa')->insert([
            0 => [
                'id' => 1,
                'type_id' => 4,
                'sub_id' => 1,
                'sub_sub_name' => 'Hasil Usaha',
            ],
            1 => [
                'id' => 2,
                'type_id' => 4,
                'sub_id' => 1,
                'sub_sub_name' => 'Hasil Aset',
            ],
            2 => [
                'id' => 3,
                'type_id' => 4,
                'sub_id' => 1,
                'sub_sub_name' => 'Swadaya',
            ],
            3 => [
                'id' => 4,
                'type_id' => 4,
                'sub_id' => 1,
                'sub_sub_name' => 'Lain-lain Pendapatan Asli Desa',
            ],
            4 => [
                'id' => 1,
                'type_id' => 4,
                'sub_id' => 2,
                'sub_sub_name' => 'Dana Desa',
            ],
            5 => [
                'id' => 2,
                'type_id' => 4,
                'sub_id' => 2,
                'sub_sub_name' => 'Bagian dari Hasil Pajak dan Retribusi Daerah Kabupaten/kota',
            ],
            6 => [
                'id' => 3,
                'type_id' => 4,
                'sub_id' => 2,
                'sub_sub_name' => 'Alokasi Dana Desa',
            ],
            7 => [
                'id' => 4,
                'type_id' => 4,
                'sub_id' => 2,
                'sub_sub_name' => 'Bantuan Keuangan Provinsi',
            ],
            8 => [
                'id' => 5,
                'type_id' => 4,
                'sub_id' => 2,
                'sub_sub_name' => 'Bantuan Keuangan APBD Kabupaten/Kota',
            ],
            9 => [
                'id' => 1,
                'type_id' => 4,
                'sub_id' => 3,
                'sub_sub_name' => 'Penerimaan dari Hasil Kerjasama antar Desa ',
            ],
            10 => [
                'id' => 2,
                'type_id' => 4,
                'sub_id' => 3,
                'sub_sub_name' => 'Penerimaan dari Hasil Kerjasama Desa dengan Pihak Ketiga',
            ],
            11 => [
                'id' => 3,
                'type_id' => 4,
                'sub_id' => 3,
                'sub_sub_name' => 'Penerimaan dari Bantuan Perusahaan yang berlokasi di Desa',
            ],
            12 => [
                'id' => 4,
                'type_id' => 4,
                'sub_id' => 3,
                'sub_sub_name' => 'Hibah dan sumbangan dari Pihak Ketiga',
            ],
            13 => [
                'id' => 5,
                'type_id' => 4,
                'sub_id' => 3,
                'sub_sub_name' => 'Koreksi kesalahan belanja tahun-tahun anggaran sebelumnya yang mengakibatkan penerimaan di kas Desa pada tahun anggaran berjalan',
            ],
            14 => [
                'id' => 6,
                'type_id' => 4,
                'sub_id' => 3,
                'sub_sub_name' => 'Bunga Bank',
            ],
            15 => [
                'id' => 9,
                'type_id' => 4,
                'sub_id' => 3,
                'sub_sub_name' => 'Lain-lain pendapatan Desa yang sah',
            ],
            16 => [
                'id' => 1,
                'type_id' => 5,
                'sub_id' => 1,
                'sub_sub_name' => 'Penghasilan Tetap dan Tunjangan Kepala Desa',
            ],
            17 => [
                'id' => 2,
                'type_id' => 5,
                'sub_id' => 1,
                'sub_sub_name' => 'Penghasilan Tetap dan Tunjangan Perangkat Desa',
            ],
            18 => [
                'id' => 3,
                'type_id' => 5,
                'sub_id' => 1,
                'sub_sub_name' => 'Jaminan Sosial Kepala Desa dan Perangkat Desa',
            ],
            19 => [
                'id' => 4,
                'type_id' => 5,
                'sub_id' => 1,
                'sub_sub_name' => 'Tunjangan BPD',
            ],
            20 => [
                'id' => 2,
                'type_id' => 5,
                'sub_id' => 2,
                'sub_sub_name' => 'Belanja Jasa Honorarium',
            ],
            21 => [
                'id' => 3,
                'type_id' => 5,
                'sub_id' => 2,
                'sub_sub_name' => 'Belanja Perjalanan Dinas',
            ],
            22 => [
                'id' => 4,
                'type_id' => 5,
                'sub_id' => 2,
                'sub_sub_name' => 'Belanja Jasa Sewa',
            ],
            23 => [
                'id' => 5,
                'type_id' => 5,
                'sub_id' => 2,
                'sub_sub_name' => 'Belanja Operasional Perkantoran',
            ],
            24 => [
                'id' => 6,
                'type_id' => 5,
                'sub_id' => 2,
                'sub_sub_name' => 'Belanja Pemeliharaan',
            ],
            25 => [
                'id' => 7,
                'type_id' => 5,
                'sub_id' => 2,
                'sub_sub_name' => 'Belanja Barang dan Jasa yang Diserahkan kepada Masyarakat',
            ],
            26 => [
                'id' => 1,
                'type_id' => 5,
                'sub_id' => 3,
                'sub_sub_name' => 'Belanja Modal Pengadaan Tanah',
            ],
            27 => [
                'id' => 2,
                'type_id' => 5,
                'sub_id' => 3,
                'sub_sub_name' => 'Belanja Modal Peralatan, Mesin, dan Alat Berat',
            ],
            28 => [
                'id' => 3,
                'type_id' => 5,
                'sub_id' => 3,
                'sub_sub_name' => 'Belanja Modal Kendaraan ',
            ],
            29 => [
                'id' => 4,
                'type_id' => 5,
                'sub_id' => 3,
                'sub_sub_name' => 'Belanja Modal Gedung, Bangunan dan Taman',
            ],
            30 => [
                'id' => 5,
                'type_id' => 5,
                'sub_id' => 3,
                'sub_sub_name' => 'Belanja Modal Jalan/Prasarana Jalan',
            ],
            31 => [
                'id' => 6,
                'type_id' => 5,
                'sub_id' => 3,
                'sub_sub_name' => 'Belanja Modal Jembatan',
            ],
            32 => [
                'id' => 7,
                'type_id' => 5,
                'sub_id' => 3,
                'sub_sub_name' => 'Belanja Modal Irigasi/Embung/Air Sungai/Drainase/Air Limbah/Persampahan',
            ],
            33 => [
                'id' => 8,
                'type_id' => 5,
                'sub_id' => 3,
                'sub_sub_name' => 'Belanja Modal Jaringan/Instalasi',
            ],
            34 => [
                'id' => 9,
                'type_id' => 5,
                'sub_id' => 3,
                'sub_sub_name' => 'Belanja Modal lainnya',
            ],
            35 => [
                'id' => 1,
                'type_id' => 5,
                'sub_id' => 4,
                'sub_sub_name' => 'Belanja Tak Terduga',
            ],
            36 => [
                'id' => 1,
                'type_id' => 6,
                'sub_id' => 1,
                'sub_sub_name' => 'SILPA Tahun Sebelumya',
            ],
            37 => [
                'id' => 2,
                'type_id' => 6,
                'sub_id' => 1,
                'sub_sub_name' => 'Pencairan Dana Cadangan',
            ],
            38 => [
                'id' => 3,
                'type_id' => 6,
                'sub_id' => 1,
                'sub_sub_name' => 'Hasil Penjualan Kekayaan Desa yang Dipisahkan',
            ],
            39 => [
                'id' => 9,
                'type_id' => 6,
                'sub_id' => 1,
                'sub_sub_name' => 'Penerimaan Pembiayaan Lainnya',
            ],
            40 => [
                'id' => 1,
                'type_id' => 6,
                'sub_id' => 2,
                'sub_sub_name' => 'Pembentukan Dana Cadangan',
            ],
            41 => [
                'id' => 2,
                'type_id' => 6,
                'sub_id' => 2,
                'sub_sub_name' => 'Penyertaan Modal Desa',
            ],
            42 => [
                'id' => 9,
                'type_id' => 6,
                'sub_id' => 2,
                'sub_sub_name' => 'Pengeluaran Pembiayaan lainnya',
            ],
        ]);
    }
}
