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

use App\Models\Navigation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

        // insert parents
        DB::table('das_navigation')->insert([
            [                
                'name' => 'Beranda',
                'slug' => Str::slug('Beranda'),
                'nav_type' => 'system',
                'url' => '/',
                'order' => 1                
            ],
            [                
                'name' => 'Berita Desa',
                'slug' => Str::slug('Berita Desa'),
                'nav_type' => 'system',
                'url' => '/berita-desa',
                'order' => 2                
            ],
            [                
                'name' => 'Profil',
                'slug' => Str::slug('Profil'),
                'nav_type' => 'system',
                'url' => '/#',
                'order' => 3                
            ],
            [                
                'name' => 'Desa',
                'slug' => Str::slug('Desa'),
                'nav_type' => 'system',
                'url' => '/#',
                'order' => 4                
            ],
            [                
                'name' => 'Potensi',
                'slug' => Str::slug('Potensi'),
                'nav_type' => 'system',
                'url' => '/#',
                'order' => 5                
            ],
            [                
                'name' => 'Statistik',
                'slug' => Str::slug('Statistik'),
                'nav_type' => 'system',
                'url' => '/#',
                'order' => 6                
            ],
            [                
                'name' => 'Unduhan',
                'slug' => Str::slug('Unduhan'),
                'nav_type' => 'system',
                'url' => '/#',
                'order' => 7                
            ],
            [                
                'name' => 'FAQ',
                'slug' => Str::slug('FAQ'),
                'nav_type' => 'system',
                'url' => '/faq',
                'order' => 8                
            ],                        
        ]);

        // insert childs
        DB::table('das_navigation')->insert([
            [
                'parent_id' => Navigation::where('slug', 'profil')->first()->id,
                'name' => 'Sejarah',
                'slug' => 'profil-sejarah',
                'nav_type' => 'system',
                'url' => '/profil/sejarah',
                'order' => 1                
            ],
            [    
                'parent_id' => Navigation::where('slug', 'profil')->first()->id,            
                'name' => 'Letak Geografis',
                'slug' => 'profil-letak-geografis',
                'nav_type' => 'system',
                'url' => '/profil/letak-geografis',
                'order' => 2                
            ],
            [   
                'parent_id' => Navigation::where('slug', 'profil')->first()->id,             
                'name' => 'Struktur Pemerintahan',
                'slug' => 'profil-struktur-pemerintahan',
                'nav_type' => 'system',
                'url' => '/profil/struktur-pemerintahan',
                'order' => 3                
            ],
            [     
                'parent_id' => Navigation::where('slug', 'profil')->first()->id,           
                'name' => 'Visi & Misi',
                'slug' => 'profil-visi-misi',
                'nav_type' => 'system',
                'url' => '/profil/visi-misi',
                'order' => 4                
            ],
            [     
                'parent_id' => Navigation::where('slug', 'statistik')->first()->id,           
                'name' => 'Penduduk',
                'slug' => 'statistik-penduduk',
                'nav_type' => 'system',
                'url' => '/statistik/kependudukan',
                'order' => 1                
            ],
            [ 
                'parent_id' => Navigation::where('slug', 'statistik')->first()->id,                 
                'name' => 'Pendidikan',
                'slug' => 'statistik-pendidikan',
                'nav_type' => 'system',
                'url' => '/statistik/pendidikan',
                'order' => 2                
            ],
            [    
                'parent_id' => Navigation::where('slug', 'statistik')->first()->id,                
                'name' => 'Kesehatan',
                'slug' => 'statistik-kesehatan',
                'nav_type' => 'system',
                'url' => '/statistik/kesehatan',
                'order' => 3                
            ],
            [    
                'parent_id' => Navigation::where('slug', 'statistik')->first()->id,                
                'name' => 'Program dan Bantuan',
                'slug' => 'statistik-program-dan-bantuan',
                'nav_type' => 'system',
                'url' => '/statistik/program-dan-bantuan',
                'order' => 4                
            ],
            [ 
                'parent_id' => Navigation::where('slug', 'statistik')->first()->id,                   
                'name' => 'Anggaran dan Realisasi',
                'slug' => 'statistik-anggaran-dan-realisasi',
                'nav_type' => 'system',
                'url' => '/statistik/anggaran-dan-realisasi',
                'order' => 5                
            ],
            [    
                'parent_id' => Navigation::where('slug', 'statistik')->first()->id,                
                'name' => 'Anggaran Desa',
                'slug' => 'statistik-anggaran-desa',
                'nav_type' => 'system',
                'url' => '/statistik/anggaran-desa',
                'order' => 6                
            ],
            [    
                'parent_id' => Navigation::where('slug', 'unduhan')->first()->id,                
                'name' => 'Prosedur',
                'slug' => 'unduhan-prosedur',
                'nav_type' => 'system',
                'url' => '/unduhan/prosedur',
                'order' => 1                
            ],
            [   
                'parent_id' => Navigation::where('slug', 'unduhan')->first()->id,             
                'name' => 'Regulasi',
                'slug' => 'unduhan-regulasi',
                'nav_type' => 'system',
                'url' => '/unduhan/regulasi',
                'order' => 2                
            ],
            [      
                'parent_id' => Navigation::where('slug', 'unduhan')->first()->id,          
                'name' => 'Dokumen',
                'slug' => 'unduhan-dokumen',
                'nav_type' => 'system',
                'url' => '/unduhan/form-dokumen',
                'order' => 3                
            ]
        ]);
    }
}
