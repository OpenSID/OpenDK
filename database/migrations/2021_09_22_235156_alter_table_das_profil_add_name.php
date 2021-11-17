<?php

use App\Models\Profil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDasProfilAddName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('das_profil', function (Blueprint $table) {
            $table->string('nama_provinsi', 255)->after('provinsi_id')->nullable();
            $table->string('nama_kabupaten', 255)->after('kabupaten_id')->nullable();
            $table->string('nama_kecamatan', 255)->after('kecamatan_id')->nullable();
            $table->string('kecamatan_id', 8)->nullable()->change();
        });

        // Isi data dari pengguna versi sebelumnya
        if ($profil = Profil::first()) {
            $profil->nama_provinsi = DB::table('ref_wilayah')->where('kode', $profil->provinsi_id)->first()->nama;
            $profil->nama_kabupaten = DB::table('ref_wilayah')->where('kode', $profil->kabupaten_id)->first()->nama;
            $profil->nama_kecamatan = DB::table('ref_wilayah')->where('kode', $profil->kecamatan_id)->first()->nama;
            $profil->update();
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table) {
            $table->dropColumn('nama_provinsi');
            $table->dropColumn('nama_kabupaten');
            $table->dropColumn('nama_kecamatan');
        });
    }
}
