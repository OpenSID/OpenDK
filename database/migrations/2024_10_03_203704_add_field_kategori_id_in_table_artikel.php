<?php

use App\Models\Kategori;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('das_artikel', function (Blueprint $table) {
            // Cek apakah kolom kategori_id sudah ada (dari migration sebelumnya)
            // untuk menghindari duplicate column error
            if (! Schema::hasColumn('das_artikel', 'kategori_id')) {
                $table->foreignIdFor(Kategori::class)->nullable()->after('judul')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
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
            // Drop foreign key constraint sebelum menghapus kolom
            // Nama constraint: das_artikel_kategori_id_foreign (konvensi Laravel)
            if (Schema::hasColumn('das_artikel', 'kategori_id')) {
                $table->dropForeign(['kategori_id']);
                $table->dropColumn('kategori_id');
            }
        });
    }
};

