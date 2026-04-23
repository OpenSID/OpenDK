<?php

namespace Database\Seeders;

use App\Models\PpidJenisDokumen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PpidJenisDokumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'nama'      => 'Secara Berkala',
                'slug'      => 'secara-berkala',
                'deskripsi' => 'Informasi yang rutin diterbitkan dan diperbaharui untuk publik.',
                'kode'      => '#0e15d8',
                'icon'      => 'fa fa-calendar',
                'urut'      => 1,
                'status'    => '1',
            ],
            [
                'nama'      => 'Serta Merta',
                'slug'      => 'serta-merta',
                'deskripsi' => 'Informasi yang wajib diumumkan segera karena penting bagi masyarakat.',
                'kode'      => '#059e17',
                'icon'      => 'fa fa-bolt',
                'urut'      => 2,
                'status'    => '1',
            ],
            [
                'nama'      => 'Tersedia Setiap Saat',
                'slug'      => 'tersedia-setiap-saat',
                'deskripsi' => 'Informasi yang tersedia dan dapat diakses setiap saat.',
                'kode'      => '#ff9900',
                'icon'      => 'fa fa-globe',
                'urut'      => 3,
                'status'    => '1',
            ],
        ];

        foreach ($data as $item) {
            PpidJenisDokumen::updateOrCreate(
                ['slug' => $item['slug']],
                array_merge($item, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
