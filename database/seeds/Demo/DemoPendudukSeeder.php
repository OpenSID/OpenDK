<?php

namespace Database\Seeds\Demo;

use App\Imports\ImporPenduduk;
use App\Models\DataDesa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

class DemoPendudukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = storage_path('app/public/template_upload/penduduk_22_12_2020_opendk.zip');
        $extract = storage_path('app/public/penduduk/foto/');

        $zip = new ZipArchive;
        $zip->open($path);
        $zip->extractTo($extract);
        $zip->close();

        Excel::import(
            new ImporPenduduk([
                'tahun'   => now()->year,
                'desa_id' => DataDesa::first()->desa_id,
            ]),
            'penduduk/foto/penduduk_22_12_2020_opendk.xlsx',
            'public'
        );

        Storage::disk('public')->delete('penduduk/foto/penduduk_22_12_2020_opendk.xlsx');
    }
}
