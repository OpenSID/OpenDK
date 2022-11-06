<?php

use App\Models\Jabatan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefJabatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_jabatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->text('tupoksi')->nullable();
            $table->boolean('jenis')->default(2); // 1: Wajib; 2: Tidak Wajib
            $table->timestamps();
        });

        Jabatan::create(['nama' => 'camat', 'jenis' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref_jabatan');
    }
}
