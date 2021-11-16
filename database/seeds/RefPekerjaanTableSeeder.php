<?php

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
        
        DB::table('ref_pekerjaan')->insert(array (
            0 => array (
                'id' => 1,
                'nama' => 'BELUM/TIDAK BEKERJA',
            ),
            1 => array (
                'id' => 2,
                'nama' => 'MENGURUS RUMAH TANGGA',
            ),
            2 => array (
                'id' => 3,
                'nama' => 'PELAJAR/MAHASISWA',
            ),
            3 => array (
                'id' => 4,
                'nama' => 'PENSIUNAN',
            ),
            4 => array (
                'id' => 5,
            'nama' => 'PEGAWAI NEGERI SIPIL (PNS)',
            ),
            5 => array (
                'id' => 6,
            'nama' => 'TENTARA NASIONAL INDONESIA (TNI)',
            ),
            6 => array (
                'id' => 7,
            'nama' => 'KEPOLISIAN RI (POLRI)',
            ),
            7 => array (
                'id' => 8,
                'nama' => 'PERDAGANGAN',
            ),
            8 => array (
                'id' => 9,
                'nama' => 'PETANI/PERKEBUNAN',
            ),
            9 => array (
                'id' => 10,
                'nama' => 'PETERNAK',
            ),
            10 => array (
                'id' => 11,
                'nama' => 'NELAYAN/PERIKANAN',
            ),
            11 => array (
                'id' => 12,
                'nama' => 'INDUSTRI',
            ),
            12 => array (
                'id' => 13,
                'nama' => 'KONSTRUKSI',
            ),
            13 => array (
                'id' => 14,
                'nama' => 'TRANSPORTASI',
            ),
            14 => array (
                'id' => 15,
                'nama' => 'KARYAWAN SWASTA',
            ),
            15 => array (
                'id' => 16,
                'nama' => 'KARYAWAN BUMN',
            ),
            16 => array (
                'id' => 17,
                'nama' => 'KARYAWAN BUMD',
            ),
            17 => array (
                'id' => 18,
                'nama' => 'KARYAWAN HONORER',
            ),
            18 => array (
                'id' => 19,
                'nama' => 'BURUH HARIAN LEPAS',
            ),
            19 => array (
                'id' => 20,
                'nama' => 'BURUH TANI/PERKEBUNAN',
            ),
            20 => array (
                'id' => 21,
                'nama' => 'BURUH NELAYAN/PERIKANAN',
            ),
            21 => array (
                'id' => 22,
                'nama' => 'BURUH PETERNAKAN',
            ),
            22 => array (
                'id' => 23,
                'nama' => 'PEMBANTU RUMAH TANGGA',
            ),
            23 => array (
                'id' => 24,
                'nama' => 'TUKANG CUKUR',
            ),
            24 => array (
                'id' => 25,
                'nama' => 'TUKANG LISTRIK',
            ),
            25 => array (
                'id' => 26,
                'nama' => 'TUKANG BATU',
            ),
            26 => array (
                'id' => 27,
                'nama' => 'TUKANG KAYU',
            ),
            27 => array (
                'id' => 28,
                'nama' => 'TUKANG SOL SEPATU',
            ),
            28 => array (
                'id' => 29,
                'nama' => 'TUKANG LAS/PANDAI BESI',
            ),
            29 => array (
                'id' => 30,
                'nama' => 'TUKANG JAHIT',
            ),
            30 => array (
                'id' => 31,
                'nama' => 'TUKANG GIGI',
            ),
            31 => array (
                'id' => 32,
                'nama' => 'PENATA RIAS',
            ),
            32 => array (
                'id' => 33,
                'nama' => 'PENATA BUSANA',
            ),
            33 => array (
                'id' => 34,
                'nama' => 'PENATA RAMBUT',
            ),
            34 => array (
                'id' => 35,
                'nama' => 'MEKANIK',
            ),
            35 => array (
                'id' => 36,
                'nama' => 'SENIMAN',
            ),
            36 => array (
                'id' => 37,
                'nama' => 'TABIB',
            ),
            37 => array (
                'id' => 38,
                'nama' => 'PARAJI',
            ),
            38 => array (
                'id' => 39,
                'nama' => 'PERANCANG BUSANA',
            ),
            39 => array (
                'id' => 40,
                'nama' => 'PENTERJEMAH',
            ),
            40 => array (
                'id' => 41,
                'nama' => 'IMAM MASJID',
            ),
            41 => array (
                'id' => 42,
                'nama' => 'PENDETA',
            ),
            42 => array (
                'id' => 43,
                'nama' => 'PASTOR',
            ),
            43 => array (
                'id' => 44,
                'nama' => 'WARTAWAN',
            ),
            44 => array (
                'id' => 45,
                'nama' => 'USTADZ/MUBALIGH',
            ),
            45 => array (
                'id' => 46,
                'nama' => 'JURU MASAK',
            ),
            46 => array (
                'id' => 47,
                'nama' => 'PROMOTOR ACARA',
            ),
            47 => array (
                'id' => 48,
                'nama' => 'ANGGOTA DPR-RI',
            ),
            48 => array (
                'id' => 49,
                'nama' => 'ANGGOTA DPD',
            ),
            49 => array (
                'id' => 50,
                'nama' => 'ANGGOTA BPK',
            ),
            50 => array (
                'id' => 51,
                'nama' => 'PRESIDEN',
            ),
            51 => array (
                'id' => 52,
                'nama' => 'WAKIL PRESIDEN',
            ),
            52 => array (
                'id' => 53,
                'nama' => 'ANGGOTA MAHKAMAH KONSTITUSI',
            ),
            53 => array (
                'id' => 54,
                'nama' => 'ANGGOTA KABINET KEMENTERIAN',
            ),
            54 => array (
                'id' => 55,
                'nama' => 'DUTA BESAR',
            ),
            55 => array (
                'id' => 56,
                'nama' => 'GUBERNUR',
            ),
            56 => array (
                'id' => 57,
                'nama' => 'WAKIL GUBERNUR',
            ),
            57 => array (
                'id' => 58,
                'nama' => 'BUPATI',
            ),
            58 => array (
                'id' => 59,
                'nama' => 'WAKIL BUPATI',
            ),
            59 => array (
                'id' => 60,
                'nama' => 'WALIKOTA',
            ),
            60 => array (
                'id' => 61,
                'nama' => 'WAKIL WALIKOTA',
            ),
            61 => array (
                'id' => 62,
                'nama' => 'ANGGOTA DPRD PROVINSI',
            ),
            62 => array (
                'id' => 63,
                'nama' => 'ANGGOTA DPRD KABUPATEN/KOTA',
            ),
            63 => array (
                'id' => 64,
                'nama' => 'DOSEN',
            ),
            64 => array (
                'id' => 65,
                'nama' => 'GURU',
            ),
            65 => array (
                'id' => 66,
                'nama' => 'PILOT',
            ),
            66 => array (
                'id' => 67,
                'nama' => 'PENGACARA',
            ),
            67 => array (
                'id' => 68,
                'nama' => 'NOTARIS',
            ),
            68 => array (
                'id' => 69,
                'nama' => 'ARSITEK',
            ),
            69 => array (
                'id' => 70,
                'nama' => 'AKUNTAN',
            ),
            70 => array (
                'id' => 71,
                'nama' => 'KONSULTAN',
            ),
            71 => array (
                'id' => 72,
                'nama' => 'DOKTER',
            ),
            72 => array (
                'id' => 73,
                'nama' => 'BIDAN',
            ),
            73 => array (
                'id' => 74,
                'nama' => 'PERAWAT',
            ),
            74 => array (
                'id' => 75,
                'nama' => 'APOTEKER',
            ),
            75 => array (
                'id' => 76,
                'nama' => 'PSIKIATER/PSIKOLOG',
            ),
            76 => array (
                'id' => 77,
                'nama' => 'PENYIAR TELEVISI',
            ),
            77 => array (
                'id' => 78,
                'nama' => 'PENYIAR RADIO',
            ),
            78 => array (
                'id' => 79,
                'nama' => 'PELAUT',
            ),
            79 => array (
                'id' => 80,
                'nama' => 'PENELITI',
            ),
            80 => array (
                'id' => 81,
                'nama' => 'SOPIR',
            ),
            81 => array (
                'id' => 82,
                'nama' => 'PIALANG',
            ),
            82 => array (
                'id' => 83,
                'nama' => 'PARANORMAL',
            ),
            83 => array (
                'id' => 84,
                'nama' => 'PEDAGANG',
            ),
            84 => array (
                'id' => 85,
                'nama' => 'PERANGKAT DESA',
            ),
            85 => array (
                'id' => 86,
                'nama' => 'KEPALA DESA',
            ),
            86 => array (
                'id' => 87,
                'nama' => 'BIARAWATI',
            ),
            87 => array (
                'id' => 88,
                'nama' => 'WIRASWASTA',
            ),
            88 => array (
                'id' => 89,
                'nama' => 'LAINNYA',
            ),
        ));
    }
}