<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('das_jenis_dokumen')) {
            Schema::create('das_jenis_dokumen', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nama', 255);
                $table->string('slug', 255)->nullable();
                $table->timestamps();
            });

            // Membuat data untuk langsung ada secara default
            DB::table('das_jenis_dokumen')->insert([
                [
                    'nama' => 'Tersedia setiap saat',
                    'slug' => Str::slug('Tersedia setiap saat'),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'nama' => 'Serta merta',
                    'slug' => Str::slug('Serta merta'),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'nama' => 'Secara Berkala',
                    'slug' => Str::slug('Secara Berkala'),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('das_jenis_dokumen');
    }
};
