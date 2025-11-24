<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('kode_kecamatan')->unique(); // Using kecamatan code for identification
            $table->unsignedBigInteger('id_start_range')->nullable(); // Starting offset for ID range
            $table->unsignedBigInteger('id_end_range')->nullable(); // Ending offset for ID range
            $table->text('description')->nullable();            
            $table->timestamps();
        });

        $kecamatan = DB::table('das_profil')            
            ->first();        
        // Provide default values if pengaturan_aplikasi is empty
        if (!$kecamatan) {
            $kecamatan = (object)['value' => 'Desa Default'];
        }        
        if ($kecamatan) {
            DB::table('tenants')->insert([
                'kode_kecamatan' => $kecamatan->kecamatan_id,
                'name' => $kecamatan->nama_kecamatan,
                'id_start_range' => 1,
                'id_end_range' => 999999999,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // jika pada file .env belum ada variabel KODE_KECAMATAN maka tambahkan dengan nilai $kecamatan->keacamatan_id
            if (empty(env('KODE_KECAMATAN'))) {
                $envPath = base_path('.env');
                if (file_exists($envPath)) {
                    $envContents = file_get_contents($envPath);
                    if (strpos($envContents, 'KODE_KECAMATAN=') === false) {
                        $line = PHP_EOL . 'KODE_KECAMATAN=' . $kecamatan->kecamatan_id . PHP_EOL;
                        file_put_contents($envPath, $line, FILE_APPEND | LOCK_EX);
                    }
                }
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('tenants');
    }
};
