<?php

use App\Models\Kategori;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menghapus foreign key dan kolom kategori_id dari das_artikel
     * (kolom ini sudah digantikan oleh id_kategori → das_artikel_kategori)
     *
     * @return void
     */
    public function up()
    {
        Schema::table('das_artikel', function (Blueprint $table) {
            // Drop FK terlebih dahulu sebelum drop kolom (MySQL strict mode requirement)
            if (Schema::hasColumn('das_artikel', 'kategori_id')) {
                $table->dropForeign(['kategori_id']);
                $table->dropColumn('kategori_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_artikel', function (Blueprint $table) {
            if (! Schema::hasColumn('das_artikel', 'kategori_id')) {
                $table->foreignIdFor(Kategori::class)
                    ->nullable()
                    ->after('judul')
                    ->constrained()
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();
            }
        });
    }
};

