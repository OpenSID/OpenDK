<?php

use App\Models\Jabatan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->boolean('jenis')->default(3); // 1: Camat; 2: Sekcam, 3: lainnya
            $table->timestamps();
        });

        $data = [
            ['nama'=>'Camat', 'jenis'=> 1],
            ['nama'=>'Sekretaris', 'jenis'=> 2],
        ];

        Jabatan::insert($data);
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
