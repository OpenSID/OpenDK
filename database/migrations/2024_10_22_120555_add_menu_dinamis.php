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
        NavMenu::insert([
            [
                'id' => 1,
                'name' => 'Beranda',
                'url' => '/',
                'target' => '_self',
                'type' => 'modul',
                'parent_id' => NULL,
                'order' => 1,
                'is_show' => 1
            ],
            [
                'id' => 2,
                'name' => 'Publikasi',
                'url' => '#',
                'target' => '_self',
                'type' => 'modul',
                'parent_id' => NULL,
                'order' => 2,
                'is_show' => 1
            ],
            [
                'id' => 3,
                'name' => 'Galeri',
                'url' => '/publikasi/galeri',
                'target' => '_self',
                'type' => 'modul',
                'parent_id' => 2,
                'order' => 1,
                'is_show' => 1
            ],
            [
                'id' => 4,
                'name' => 'Berita Desa',
                'url' => '/berita-desa',
                'target' => '_self',
                'type' => 'modul',
                'parent_id' => NULL,
                'order' => 3,
                'is_show' => 1
            ],
            [
                'id' => 5,
                'name' => 'Profil',
                'url' => '#',
                'target' => '_self',
                'type' => 'modul',
                'parent_id' => NULL,
                'order' => 4,
                'is_show' => 1
            ],
            [
                'id' => 6,
                'name' => 'Sejarah',
                'url' => '/profil/sejarah',
                'target' => '_self',
                'type' => 'modul',
                'parent_id' => 5,
                'order' => 1,
                'is_show' => 1
            ],
            [
                'id' => 7,
                'name' => 'Letak Geografis',
                'url' => '/profil/letak-geografis',
                'target' => '_self',
                'type' => 'modul',
                'parent_id' => 5,
                'order' => 2,
                'is_show' => 1
            ],
            [
                'id' => 8,
                'name' => 'Struktur Pemerintahan',
                'url' => '/profil/struktur-pemerintahan',
                'target' => '_self',
                'type' => 'modul',
                'parent_id' => 5,
                'order' => 3,
                'is_show' => 1
            ],
            [
                'id' => 9,
                'name' => 'Visi & Misi',
                'url' => '/profil/visi-misi',
                'target' => '_self',
                'type' => 'modul',
                'parent_id' => 5,
                'order' => 4,
                'is_show' => 1
            ],
            [
                'id' => 10,
                'name' => 'Kategori Berita',
                'url' => '#',
                'target' => '_self',
                'type' => 'modul',
                'parent_id' => NULL,
                'order' => 5,
                'is_show' => 1
            ],
            [
                'id' => 11,
                'name' => 'Desa',
                'url' => '#',
                'target' => '_self',
                'type' => 'modul',
                'parent_id' => NULL,
                'order' => 6,
                'is_show' => 1
            ],
            [
                'id' => 12,
                'name' => 'Potensi',
                'url' => '#',
                'target' => '_self',
                'type' => 'modul',
                'parent_id' => NULL,
                'order' => 7,
                'is_show' => 1
            ],
            [
                'id' => 13,
                'name' => 'Statistik',
                'url' => '#',
                'target' => '_self',
                'type' => 'modul',
                'parent_id' => NULL,
                'order' => 8,
                'is_show' => 1
            ],
            [
                'id' => 14,
                'name' => 'Penduduk',
                'url' => '/statistik/kependudukan',
                'target' => '_self',
                'type' => 'modul',
                'parent_id' => 13,
                'order' => 1,
                'is_show' => 1
            ],
            [
                'id' => 15,
                'name' => 'Pendidikan',
                'url' => '/statistik/pendidikan',
                'target' => '_self',
                'type' => 'modul',
                'parent_id' => 13,
                'order' => 2,
                'is_show' => 1
            ],
            [
                'id' => 16,
                'name' => 'Kesehatan',
                'url' => '/statistik/kesehatan',
                'target' => '_self',
                'type' => 'modul',
                'parent_id' => 13,
                'order' => 3,
                'is_show' => 1
            ],
            [
                'id' => 17,
                'name' => 'Program dan Bantuan',
                'url' => '/statistik/program-dan-bantuan',
                'target' => '_self',
                'type' => 'modul',
                'parent_id' => 13,
                'order' => 4,
                'is_show' => 1
            ],
            [
                'id' => 18,
                'name' => 'Anggaran dan Realisasi',
                'url' => '/statistik/anggaran-dan-realisasi',
                'target' => '_self',
                'type' => 'modul',
                'parent_id' => 13,
                'order' => 5,
                'is_show' => 1
            ],
            [
                'id' => 19,
                'name' => 'Anggaran Desa',
                'url' => '/statistik/anggaran-desa',
                'target' => '_self',
                'type' => 'modul',
                'parent_id' => 13,
                'order' => 6,
                'is_show' => 1
            ],
            [
                'id' => 20,
                'name' => 'Unduhan',
                'url' => '/statistik/anggaran-desa',
                'target' => '_self',
                'type' => 'modul',
                'parent_id' => NULL,
                'order' => 9,
                'is_show' => 1
            ],
            [
                'id' => 21,
                'name' => 'Prosedur',
                'url' => '/unduhan/prosedur',
                'target' => '_self',
                'type' => 'modul',
                'parent_id' => 20,
                'order' => 1,
                'is_show' => 1
            ],
            [
                'id' => 22,
                'name' => 'Regulasi',
                'url' => '/unduhan/regulasi',
                'target' => '_self',
                'type' => 'modul',
                'parent_id' => 20,
                'order' => 2,
                'is_show' => 1
            ],
            [
                'id' => 23,
                'name' => 'Dokumen',
                'url' => '/unduhan/form-dokumen',
                'target' => '_self',
                'type' => 'modul',
                'parent_id' => 20,
                'order' => 3,
                'is_show' => 1
            ],
            [
                'id' => 24,
                'name' => 'FAQ',
                'url' => 'https://demodk.opendesa.id/faq',
                'target' => '_blank',
                'type' => 'link',
                'parent_id' => NULL,
                'order' => 10,
                'is_show' => 1
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
