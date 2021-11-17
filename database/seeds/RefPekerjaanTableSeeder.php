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

class RefPekerjaanTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('ref_pekerjaan')->truncate();

        DB::table('ref_pekerjaan')->insert([
            0 => [
                'id' => 1,
                'nama' => 'BELUM/TIDAK BEKERJA',
            ],
            1 => [
                'id' => 2,
                'nama' => 'MENGURUS RUMAH TANGGA',
            ],
            2 => [
                'id' => 3,
                'nama' => 'PELAJAR/MAHASISWA',
            ],
            3 => [
                'id' => 4,
                'nama' => 'PENSIUNAN',
            ],
            4 => [
                'id' => 5,
            'nama' => 'PEGAWAI NEGERI SIPIL (PNS)',
            ],
            5 => [
                'id' => 6,
            'nama' => 'TENTARA NASIONAL INDONESIA (TNI)',
            ],
            6 => [
                'id' => 7,
            'nama' => 'KEPOLISIAN RI (POLRI)',
            ],
            7 => [
                'id' => 8,
                'nama' => 'PERDAGANGAN',
            ],
            8 => [
                'id' => 9,
                'nama' => 'PETANI/PERKEBUNAN',
            ],
            9 => [
                'id' => 10,
                'nama' => 'PETERNAK',
            ],
            10 => [
                'id' => 11,
                'nama' => 'NELAYAN/PERIKANAN',
            ],
            11 => [
                'id' => 12,
                'nama' => 'INDUSTRI',
            ],
            12 => [
                'id' => 13,
                'nama' => 'KONSTRUKSI',
            ],
            13 => [
                'id' => 14,
                'nama' => 'TRANSPORTASI',
            ],
            14 => [
                'id' => 15,
                'nama' => 'KARYAWAN SWASTA',
            ],
            15 => [
                'id' => 16,
                'nama' => 'KARYAWAN BUMN',
            ],
            16 => [
                'id' => 17,
                'nama' => 'KARYAWAN BUMD',
            ],
            17 => [
                'id' => 18,
                'nama' => 'KARYAWAN HONORER',
            ],
            18 => [
                'id' => 19,
                'nama' => 'BURUH HARIAN LEPAS',
            ],
            19 => [
                'id' => 20,
                'nama' => 'BURUH TANI/PERKEBUNAN',
            ],
            20 => [
                'id' => 21,
                'nama' => 'BURUH NELAYAN/PERIKANAN',
            ],
            21 => [
                'id' => 22,
                'nama' => 'BURUH PETERNAKAN',
            ],
            22 => [
                'id' => 23,
                'nama' => 'PEMBANTU RUMAH TANGGA',
            ],
            23 => [
                'id' => 24,
                'nama' => 'TUKANG CUKUR',
            ],
            24 => [
                'id' => 25,
                'nama' => 'TUKANG LISTRIK',
            ],
            25 => [
                'id' => 26,
                'nama' => 'TUKANG BATU',
            ],
            26 => [
                'id' => 27,
                'nama' => 'TUKANG KAYU',
            ],
            27 => [
                'id' => 28,
                'nama' => 'TUKANG SOL SEPATU',
            ],
            28 => [
                'id' => 29,
                'nama' => 'TUKANG LAS/PANDAI BESI',
            ],
            29 => [
                'id' => 30,
                'nama' => 'TUKANG JAHIT',
            ],
            30 => [
                'id' => 31,
                'nama' => 'TUKANG GIGI',
            ],
            31 => [
                'id' => 32,
                'nama' => 'PENATA RIAS',
            ],
            32 => [
                'id' => 33,
                'nama' => 'PENATA BUSANA',
            ],
            33 => [
                'id' => 34,
                'nama' => 'PENATA RAMBUT',
            ],
            34 => [
                'id' => 35,
                'nama' => 'MEKANIK',
            ],
            35 => [
                'id' => 36,
                'nama' => 'SENIMAN',
            ],
            36 => [
                'id' => 37,
                'nama' => 'TABIB',
            ],
            37 => [
                'id' => 38,
                'nama' => 'PARAJI',
            ],
            38 => [
                'id' => 39,
                'nama' => 'PERANCANG BUSANA',
            ],
            39 => [
                'id' => 40,
                'nama' => 'PENTERJEMAH',
            ],
            40 => [
                'id' => 41,
                'nama' => 'IMAM MASJID',
            ],
            41 => [
                'id' => 42,
                'nama' => 'PENDETA',
            ],
            42 => [
                'id' => 43,
                'nama' => 'PASTOR',
            ],
            43 => [
                'id' => 44,
                'nama' => 'WARTAWAN',
            ],
            44 => [
                'id' => 45,
                'nama' => 'USTADZ/MUBALIGH',
            ],
            45 => [
                'id' => 46,
                'nama' => 'JURU MASAK',
            ],
            46 => [
                'id' => 47,
                'nama' => 'PROMOTOR ACARA',
            ],
            47 => [
                'id' => 48,
                'nama' => 'ANGGOTA DPR-RI',
            ],
            48 => [
                'id' => 49,
                'nama' => 'ANGGOTA DPD',
            ],
            49 => [
                'id' => 50,
                'nama' => 'ANGGOTA BPK',
            ],
            50 => [
                'id' => 51,
                'nama' => 'PRESIDEN',
            ],
            51 => [
                'id' => 52,
                'nama' => 'WAKIL PRESIDEN',
            ],
            52 => [
                'id' => 53,
                'nama' => 'ANGGOTA MAHKAMAH KONSTITUSI',
            ],
            53 => [
                'id' => 54,
                'nama' => 'ANGGOTA KABINET KEMENTERIAN',
            ],
            54 => [
                'id' => 55,
                'nama' => 'DUTA BESAR',
            ],
            55 => [
                'id' => 56,
                'nama' => 'GUBERNUR',
            ],
            56 => [
                'id' => 57,
                'nama' => 'WAKIL GUBERNUR',
            ],
            57 => [
                'id' => 58,
                'nama' => 'BUPATI',
            ],
            58 => [
                'id' => 59,
                'nama' => 'WAKIL BUPATI',
            ],
            59 => [
                'id' => 60,
                'nama' => 'WALIKOTA',
            ],
            60 => [
                'id' => 61,
                'nama' => 'WAKIL WALIKOTA',
            ],
            61 => [
                'id' => 62,
                'nama' => 'ANGGOTA DPRD PROVINSI',
            ],
            62 => [
                'id' => 63,
                'nama' => 'ANGGOTA DPRD KABUPATEN/KOTA',
            ],
            63 => [
                'id' => 64,
                'nama' => 'DOSEN',
            ],
            64 => [
                'id' => 65,
                'nama' => 'GURU',
            ],
            65 => [
                'id' => 66,
                'nama' => 'PILOT',
            ],
            66 => [
                'id' => 67,
                'nama' => 'PENGACARA',
            ],
            67 => [
                'id' => 68,
                'nama' => 'NOTARIS',
            ],
            68 => [
                'id' => 69,
                'nama' => 'ARSITEK',
            ],
            69 => [
                'id' => 70,
                'nama' => 'AKUNTAN',
            ],
            70 => [
                'id' => 71,
                'nama' => 'KONSULTAN',
            ],
            71 => [
                'id' => 72,
                'nama' => 'DOKTER',
            ],
            72 => [
                'id' => 73,
                'nama' => 'BIDAN',
            ],
            73 => [
                'id' => 74,
                'nama' => 'PERAWAT',
            ],
            74 => [
                'id' => 75,
                'nama' => 'APOTEKER',
            ],
            75 => [
                'id' => 76,
                'nama' => 'PSIKIATER/PSIKOLOG',
            ],
            76 => [
                'id' => 77,
                'nama' => 'PENYIAR TELEVISI',
            ],
            77 => [
                'id' => 78,
                'nama' => 'PENYIAR RADIO',
            ],
            78 => [
                'id' => 79,
                'nama' => 'PELAUT',
            ],
            79 => [
                'id' => 80,
                'nama' => 'PENELITI',
            ],
            80 => [
                'id' => 81,
                'nama' => 'SOPIR',
            ],
            81 => [
                'id' => 82,
                'nama' => 'PIALANG',
            ],
            82 => [
                'id' => 83,
                'nama' => 'PARANORMAL',
            ],
            83 => [
                'id' => 84,
                'nama' => 'PEDAGANG',
            ],
            84 => [
                'id' => 85,
                'nama' => 'PERANGKAT DESA',
            ],
            85 => [
                'id' => 86,
                'nama' => 'KEPALA DESA',
            ],
            86 => [
                'id' => 87,
                'nama' => 'BIARAWATI',
            ],
            87 => [
                'id' => 88,
                'nama' => 'WIRASWASTA',
            ],
            88 => [
                'id' => 89,
                'nama' => 'LAINNYA',
            ],
        ]);
    }
}
