<?php

use App\Models\Penduduk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $duplicates = DB::table('das_penduduk')
            ->select('nik', 'desa_id', DB::raw('COUNT(*) as `count`'))
            ->groupBy('nik', 'desa_id')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $duplicate) {
            $penduduk = Penduduk::where('nik', $duplicate->nik)->where('desa_id', $duplicate->desa_id)->orderBy('imported_at', 'desc')->first('id');
            Penduduk::where('nik', $duplicate->nik)->where('desa_id', $duplicate->desa_id)->where('id', '!=', $penduduk->id)->delete();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
