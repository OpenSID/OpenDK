<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePegawaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_pegawai', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_pegawai');
            $table->string('nip')->nullable();
            $table->enum('jenis_kelamin',['1','2']);
            $table->char('agama_id', 2);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->char('status_kawin_id',2);
            $table->string('nomor_karpeg')->nullable();
            $table->char('nik', 16)->unique();
            $table->enum('status_pegawai',['CPNS','PNS','P3K','PPNPN','Honorer']);
            $table->string('pangkat_cpns')->nullable();
            $table->date('tmt_cpns')->nullable();
            $table->enum('pangkat', ['Juru Muda','Juru Muda Tingkat I','Juru','Juru Tingkat I','Pengatur Muda','Pengatur Muda Tingkat I','Pengatur','Pengatur Tingkat I','Penata Muda','Penata Muda Tingkat I','Penata','Penata Tingkat I','Pembina','Pembina Tingkat I','Pembina Utama Muda','Pembina Utama Madya','Pembina Utama'])->nullable();
            $table->enum('golongan',['I','II','III','IV'])->nullable();
            $table->enum('ruang',['a','b','c','d','e'])->nullable();
            $table->date('tmt_pangkat')->nullable();
            $table->date('tmt_jabatan')->nullable();
            $table->string('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('pendidikan');
            $table->date('tamat_pendidikan')->nullable();
            $table->enum('status', ['1', '2'])->default('1');
            $table->longText('foto')->nullable();
            $table->unsignedBigInteger('jabatan_id')->nullable();
            $table->foreign('jabatan_id')->references('id')->on('das_jabatan');
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
        Schema::dropIfExists('das_pegawai');
    }
}
