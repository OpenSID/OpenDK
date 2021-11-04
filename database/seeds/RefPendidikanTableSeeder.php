<?php

use Illuminate\Database\Seeder;

class RefPendidikanTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('ref_pendidikan')->truncate();
        
        DB::table('ref_pendidikan')->insert(array (
            0 => array (
                'id' => 1,
                'nama' => 'BELUM MASUK TK/KELOMPOK BERMAIN',
            ),
            1 => array (
                'id' => 2,
                'nama' => 'SEDANG TK/KELOMPOK BERMAIN',
            ),
            2 => array (
                'id' => 3,
                'nama' => 'TIDAK PERNAH SEKOLAH',
            ),
            3 => array (
                'id' => 4,
                'nama' => 'SEDANG SD/SEDERAJAT',
            ),
            4 => array (
                'id' => 5,
                'nama' => 'TIDAK TAMAT SD/SEDERAJAT',
            ),
            5 => array (
                'id' => 6,
                'nama' => 'SEDANG SLTP/SEDERAJAT',
            ),
            6 => array (
                'id' => 7,
                'nama' => 'SEDANG SLTA/SEDERAJAT',
            ),
            7 => array (
                'id' => 8,
                'nama' => 'SEDANG  D-1/SEDERAJAT',
            ),
            8 => array (
                'id' => 9,
                'nama' => 'SEDANG D-2/SEDERAJAT',
            ),
            9 => array (
                'id' => 10,
                'nama' => 'SEDANG D-3/SEDERAJAT',
            ),
            10 => array (
                'id' => 11,
                'nama' => 'SEDANG  S-1/SEDERAJAT',
            ),
            11 => array (
                'id' => 12,
                'nama' => 'SEDANG S-2/SEDERAJAT',
            ),
            12 => array (
                'id' => 13,
                'nama' => 'SEDANG S-3/SEDERAJAT',
            ),
            13 => array (
                'id' => 14,
                'nama' => 'SEDANG SLB A/SEDERAJAT',
            ),
            14 => array (
                'id' => 15,
                'nama' => 'SEDANG SLB B/SEDERAJAT',
            ),
            15 => array (
                'id' => 16,
                'nama' => 'SEDANG SLB C/SEDERAJAT',
            ),
            16 => array (
                'id' => 17,
                'nama' => 'TIDAK DAPAT MEMBACA DAN MENULIS HURUF LATIN/ARAB',
            ),
            17 => array (
                'id' => 18,
                'nama' => 'TIDAK SEDANG SEKOLAH',
            ),
        ));
    }
}