<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JenisSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            'Surat Pengantar RT/RW',
            'Fotokopi KK',
            'Fotokopi Surat Nikah/Akta Nikah/Kutipan Akta Perkawinan',
            'Fotokopi Akta Kelahiran/Surat Kelahiran bagi keluarga yang mempunyai anak',
            'Surat Pindah Datang dari tempat asal',
            'Surat Keterangan Kematian dari Rumah Sakit, Rumah Bersalin Puskesmas, atau visum Dokter',
            'Surat Keterangan Cerai',
            'Fotokopi Ijasah Terakhir',
            'SK. PNS/KARIP/SK. TNI – POLRI',
            'Surat Keterangan Kematian dari Kepala Desa/Kelurahan',
            'Surat imigrasi / STMD (Surat Tanda Melapor Diri)',
        ];
        $now = Carbon::now();
        foreach ($items as $item) {
            DB::table('das_jenis_surat') ->insert([
                'nama' => $item,
                'slug' => Str::slug($item),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
