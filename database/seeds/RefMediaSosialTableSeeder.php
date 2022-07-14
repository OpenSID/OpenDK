<?php

use Illuminate\Database\Seeder;

class RefMediaSosialTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('media_sosial')->truncate();

        DB::table('media_sosial')->insert([
            0 => [
                'id'     => 1,
                'gambar' => 'fb.png',
                'link'   => null,
                'nama'   => 'Facebook',
                'tipe'   => 1,
                'status' => 0,
            ],
            1 => [
                'id'     => 2,
                'gambar' => 'twt.png',
                'link'   => null,
                'nama'   => 'Twitter',
                'tipe'   => 1,
                'status' => 0,
            ],
            2 => [
                'id'     => 3,
                'gambar' => 'yt.png',
                'link'   => null,
                'nama'   => 'YouTube',
                'tipe'   => 1,
                'status' => 0,
            ],
            3 => [
                'id'     => 4,
                'gambar' => 'ins.png',
                'link'   => null,
                'nama'   => 'Instagram',
                'tipe'   => 1,
                'status' => 0,
            ],
            4 => [
                'id'     => 5,
                'gambar' => 'wa.png',
                'link'   => null,
                'nama'   => 'WhatsApp',
                'tipe'   => 1,
                'status' => 0,
            ],
            5 => [
                'id'     => 6,
                'gambar' => 'tg.png',
                'link'   => null,
                'nama'   => 'Telegram',
                'tipe'   => 1,
                'status' => 0,
            ],
        ]);
    }
}
