<?php

use App\Models\DataDesa;
use App\Models\Wilayah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DasDataDesaTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $kecamatan_id = config('app.default_profile');
        $desa         = Wilayah::getDesaByKecamatan($kecamatan_id)->get();
        $collection   = collect($desa);
        $collection->shift();

        foreach ($collection as $value) {
            $insert[] = [
                'desa_id'      => $value->kode,
                'kecamatan_id' => $kecamatan_id,
                'nama'         => $value->nama,
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }

        DataDesa::insert($insert);
    }
}
