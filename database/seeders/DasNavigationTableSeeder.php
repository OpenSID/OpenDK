<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace Database\Seeders;

use App\Enums\MenuTipe;
use App\Models\Navigation;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DasNavigationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('das_navigation')->delete();

        // Menu Utama
        $menuUtama = [
            [
                'name' => 'Beranda',
                'slug' => Str::slug('Beranda'),
                'type' => MenuTipe::EKSTERNAL,
                'url' => url('/'),
                'order' => 1,
                'status' => 1,
            ],
            [
                'name' => 'Berita Desa',
                'slug' => Str::slug('Berita Desa'),
                'type' => MenuTipe::EKSTERNAL,
                'url' => url('berita-desa'),
                'order' => 2,
                'status' => 1,
            ],
            [
                'name' => 'Profil',
                'slug' => Str::slug('Profil'),
                'type' => MenuTipe::EKSTERNAL,
                'url' => '#',
                'order' => 3,
                'status' => 1,
            ],
            [
                'name' => 'Desa',
                'slug' => Str::slug('Desa'),
                'type' => MenuTipe::EKSTERNAL,
                'url' => '#',
                'order' => 4,
                'status' => 1,
            ],
            [
                'name' => 'Potensi',
                'slug' => Str::slug('Potensi'),
                'type' => MenuTipe::EKSTERNAL,
                'url' => '#',
                'order' => 5,
                'status' => 1,
            ],
            [
                'name' => 'Statistik',
                'slug' => Str::slug('Statistik'),
                'type' => MenuTipe::EKSTERNAL,
                'url' => '#',
                'order' => 6,
                'status' => 1,
            ],
            [
                'name' => 'Unduhan',
                'slug' => Str::slug('Unduhan'),
                'type' => MenuTipe::EKSTERNAL,
                'url' => '#',
                'order' => 7,
                'status' => 1,
            ],
            [
                'name' => 'FAQ',
                'slug' => Str::slug('FAQ'),
                'type' => MenuTipe::EKSTERNAL,
                'url' => url('faq'),
                'order' => 8,
                'status' => 1,
            ],
        ];

        DB::table('das_navigation')->insert($menuUtama);

        // Submenu
        $subMenu = [
            [
                'parent_id' => Navigation::where('slug', 'profil')->first()->id,
                'name' => 'Sejarah',
                'slug' => 'profil-sejarah',
                'type' => MenuTipe::PROFIL,
                'url' => 'profil/sejarah',
                'order' => 1,
                'status' => 1,
            ],
            [
                'parent_id' => Navigation::where('slug', 'profil')->first()->id,
                'name' => 'Letak Geografis',
                'slug' => 'profil-letak-geografis',
                'type' => MenuTipe::PROFIL,
                'url' => 'profil/letak-geografis',
                'order' => 2,
                'status' => 1,
            ],
            [
                'parent_id' => Navigation::where('slug', 'profil')->first()->id,
                'name' => 'Struktur Pemerintahan',
                'slug' => 'profil-struktur-pemerintahan',
                'type' => MenuTipe::PROFIL,
                'url' => 'profil/struktur-pemerintahan',
                'order' => 3,
                'status' => 1,
            ],
            [
                'parent_id' => Navigation::where('slug', 'profil')->first()->id,
                'name' => 'Visi & Misi',
                'slug' => 'profil-visi-misi',
                'type' => MenuTipe::PROFIL,
                'url' => 'profil/visi-misi',
                'order' => 4,
                'status' => 1,
            ],
            [
                'parent_id' => Navigation::where('slug', 'statistik')->first()->id,
                'name' => 'Penduduk',
                'slug' => 'statistik-penduduk',
                'type' => MenuTipe::STATISTIK,
                'url' => 'statistik/kependudukan',
                'order' => 1,
                'status' => 1,
            ],
            [
                'parent_id' => Navigation::where('slug', 'statistik')->first()->id,
                'name' => 'Pendidikan',
                'slug' => 'statistik-pendidikan',
                'type' => MenuTipe::STATISTIK,
                'url' => 'statistik/pendidikan',
                'order' => 2,
                'status' => 1,
            ],
            [
                'parent_id' => Navigation::where('slug', 'statistik')->first()->id,
                'name' => 'Kesehatan',
                'slug' => 'statistik-kesehatan',
                'type' => MenuTipe::STATISTIK,
                'url' => 'statistik/kesehatan',
                'order' => 3,
                'status' => 1,
            ],
            [
                'parent_id' => Navigation::where('slug', 'statistik')->first()->id,
                'name' => 'Program dan Bantuan',
                'slug' => 'statistik-program-dan-bantuan',
                'type' => MenuTipe::STATISTIK,
                'url' => 'statistik/program-dan-bantuan',
                'order' => 4,
                'status' => 1,
            ],
            [
                'parent_id' => Navigation::where('slug', 'statistik')->first()->id,
                'name' => 'Anggaran dan Realisasi',
                'slug' => 'statistik-anggaran-dan-realisasi',
                'type' => MenuTipe::STATISTIK,
                'url' => 'statistik/anggaran-dan-realisasi',
                'order' => 5,
                'status' => 1,
            ],
            [
                'parent_id' => Navigation::where('slug', 'statistik')->first()->id,
                'name' => 'Anggaran Desa',
                'slug' => 'statistik-anggaran-desa',
                'type' => MenuTipe::STATISTIK,
                'url' => 'statistik/anggaran-desa',
                'order' => 6,
                'status' => 1,
            ],
            [
                'parent_id' => Navigation::where('slug', 'unduhan')->first()->id,
                'name' => 'Prosedur',
                'slug' => 'unduhan-prosedur',
                'type' => MenuTipe::UNDUHAN,
                'url' => 'unduhan/prosedur',
                'order' => 1,
                'status' => 1,
            ],
            [
                'parent_id' => Navigation::where('slug', 'unduhan')->first()->id,
                'name' => 'Regulasi',
                'slug' => 'unduhan-regulasi',
                'type' => MenuTipe::UNDUHAN,
                'url' => 'unduhan/regulasi',
                'order' => 2,
                'status' => 1,
            ],
            [
                'parent_id' => Navigation::where('slug', 'unduhan')->first()->id,
                'name' => 'Dokumen',
                'slug' => 'unduhan-dokumen',
                'type' => MenuTipe::UNDUHAN,
                'url' => 'unduhan/form-dokumen',
                'order' => 3,
                'status' => 1,
            ]
        ];

        DB::table('das_data_desa')->get()->each(function ($data) use (&$subMenu) {
            $slug = $data->sebutan_desa . '-' . Str::slug($data->nama);
            $subMenu[] = [
                'parent_id' => Navigation::where('slug', 'desa')->first()->id,
                'name' => ucwords($data->sebutan_desa . ' ' . $data->nama),
                'slug' => $slug,
                'type' => MenuTipe::DESA,
                'url' => 'desa/' . $slug,
                'order' => $data->id,
                'status' => 1,
            ];
        });

        DB::table('das_tipe_potensi')->get()->each(function ($data) use (&$subMenu) {
            $subMenu[] = [
                'parent_id' => Navigation::where('slug', 'potensi')->first()->id,
                'name' => $data->nama_kategori,
                'slug' => 'potensi-' . $data->slug,
                'type' => MenuTipe::POTENSI,
                'url' => 'potensi/' . $data->slug,
                'order' => $data->id,
                'status' => 1,
            ];
        });

        DB::table('das_navigation')->insert($subMenu);
    }
}
