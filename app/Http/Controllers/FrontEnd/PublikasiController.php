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

namespace App\Http\Controllers\FrontEnd;

use App\Facades\Counter;
use App\Http\Controllers\FrontEndController;
use App\Models\Album;
use App\Models\Galeri;
use Illuminate\Support\Facades\DB;

class PublikasiController extends FrontEndController
{
    public function album()
    {
        Counter::count('publik.publikasi.album');

        $albums     = Album::status()->with(['galeris'])->paginate(9);
        $page_title = 'Galeri';

        return view('pages.publikasi.album', compact('page_title', 'albums'));
    }

    public function galeri($slug)
    {
        Counter::count('publik.publikasi.album');

        $galeris = Galeri::status()->whereRelation('album', 'slug', $slug)->paginate(9);
        // $album = Album::with(['galeris'])->where('slug', $slug)->first();

        $page_title = 'Galeri';


        return view('pages.publikasi.galeri', compact('page_title', 'galeris'));
    }

    public function galeri_detail($slug)
    {
        Counter::count('publik.publikasi.album');

        $galeri = Galeri::where('slug', $slug)->first();

        $page_title = 'Galeri';


        return view('pages.publikasi.galeri_detail', compact('page_title', 'galeri'));
    }
}
