<?php

use Illuminate\Database\Seeder;

class RefGolonganDarahTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('ref_golongan_darah')->delete();

        \DB::table('ref_golongan_darah')->insert(array (
            0 =>
            array (
                'id' => 1,
                'nama' => 'A',
            ),
            1 =>
            array (
                'id' => 2,
                'nama' => 'B',
            ),
            2 =>
            array (
                'id' => 3,
                'nama' => 'AB',
            ),
            3 =>
            array (
                'id' => 4,
                'nama' => 'O',
            ),

            4 =>
            array (
                'id' => 13,
                'nama' => 'TIDAK TAHU',
            ),
        ));


    }
}
