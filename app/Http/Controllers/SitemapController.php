<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @package	    OpenDK
 * @author	    Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers;

use App\Models\Artikel;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = Sitemap::create()
        ->add(Url::create('/profil/sejarah'))
        ->add(Url::create('/profil/letak-geografis'))
        ->add(Url::create('/profil/visi-dan-misi'))
        ->add(Url::create('/profil/struktur-pemerintahan'))
        ->add(Url::create('/potensi'))
        ->add(Url::create('/desa'))
        ->add(Url::create('/statistik/kependudukan'))
        ->add(Url::create('/statistik/pendidikan'))
        ->add(Url::create('/statistik/program-dan-bantuan'))
        ->add(Url::create('/statistik/anggaran-dan-realisasi'))
        ->add(Url::create('/statistik/anggaran-desa'))
        ->add(Url::create('/unduhan/prosedur'))
        ->add(Url::create('/unduhan/regulasi'))
        ->add(Url::create('/unduhan/form-dokumen'));

        $artikel = Artikel::all();
        foreach ($artikel as $artikel) {
            $sitemap->add(Url::create("/berita/{$artikel->slug}"));
        }
        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}
