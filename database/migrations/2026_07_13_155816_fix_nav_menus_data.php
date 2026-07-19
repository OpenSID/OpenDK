<?php

use App\Models\NavMenu;
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
        // Hapus menu 'Kategori Berita' dan 'Desa' yang tidak ada halamannya
        NavMenu::whereIn('name', ['Kategori Berita', 'Desa'])
            ->where('url', '#')
            ->delete();

        // Perbaiki link FAQ
        NavMenu::where('name', 'FAQ')
            ->where('url', 'https://demodk.opendesa.id/faq')
            ->update([
                'url' => '/faq',
                'target' => '_self',
                'type' => 'modul'
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Tidak perlu direverse karena ini perbaikan data
    }
};
