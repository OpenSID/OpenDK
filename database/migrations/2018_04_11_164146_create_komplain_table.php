
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKomplainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_komplain', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('komplain_id');
            $table->string('kategori', 200);
            $table->char('nik', 16);
            $table->string('nama', 100);
            $table->string('judul', 255);
            $table->string('slug', 255);
            $table->text('laporan');
            $table->boolean('anonim')->nullable();
            $table->string('status', 15);
            $table->integer('dilihat');
            $table->string('lampiran1', 255)->nullable();
            $table->string('lampiran2', 255)->nullable();
            $table->string('lampiran3', 255)->nullable();
            $table->string('lampiran4', 255)->nullable();
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
        Schema::dropIfExists('das_komplain');
    }
}
