<?php

namespace Database\Seeds\Demo;

use ZipArchive;
use Illuminate\Support\Str;
use App\Imports\ImporPenduduk;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class DemoPendudukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {

            DB::table('das_penduduk')->truncate();
            
            $name = 'penduduk_22_12_2020_opendk.zip';

            // Temporary path file
            $path = storage_path("app/public/template_upload/{$name}");
            $extract = storage_path('app/temp/penduduk/foto/');

            // Ekstrak file
            $zip = new ZipArchive();
            $zip->open($path);
            $zip->extractTo($extract);
            $zip->close();

            // Proses impor excell
            (new ImporPenduduk())
                ->queue($extract . Str::replaceLast('zip', 'xlsx', $name));
        } catch (Exception $e) {
            return back()->with('error', 'Import data gagal. ' . $e->getMessage());
        }
    }
}
