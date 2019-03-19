<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataUmumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_data_umum', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('profil_id')->nullable();
            $table->char('kecamatan_id', 7);
            $table->string('tipologi', 255)->nullable(true);
            $table->integer('ketinggian')->nullable(true);
            $table->double('luas_wilayah')->nullable(true);
            $table->integer('jumlah_penduduk')->nullable(true);
            $table->integer('jml_laki_laki')->nullable(true);
            $table->integer('jml_perempuan')->nullable(true);
            $table->string('bts_wil_utara', 255)->nullable(true);
            $table->string('bts_wil_timur', 255)->nullable(true);
            $table->string('bts_wil_selatan', 255)->nullable(true);
            $table->string('bts_wil_barat', 255)->nullable(true);
            $table->integer('jml_puskesmas')->nullable(true);
            $table->integer('jml_puskesmas_pembantu')->nullable(true);
            $table->integer('jml_posyandu')->nullable(true);
            $table->integer('jml_pondok_bersalin')->nullable(true);
            $table->integer('jml_paud')->nullable(true);
            $table->integer('jml_sd')->nullable(true);
            $table->integer('jml_smp')->nullable(true);
            $table->integer('jml_sma')->nullable(true);
            $table->integer('jml_masjid_besar')->nullable(true);
            $table->integer('jml_mushola')->nullable(true);
            $table->integer('jml_gereja')->nullable(true);
            $table->integer('jml_pasar')->nullable(true);
            $table->integer('jml_balai_pertemuan')->nullable(true);
            $table->integer('kepadatan_penduduk')->nullable(true);
            $table->longText('embed_peta')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('das_data_umum');
    }
}
