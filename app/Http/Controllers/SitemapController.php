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
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers;

use App\Models\FormDokumen;
use App\Models\Profil;
use App\Models\Prosedur;
use App\Models\Regulasi;

class SitemapController extends Controller
{
    public function index()
    {
        $profil   = Profil::orderBy('updated_at', 'DESC')->first();
        $prosedur = Prosedur::orderBy('updated_at', 'DESC')->first();
        $regulasi = Regulasi::orderBy('updated_at', 'DESC')->first();
        $dokumen  = FormDokumen::orderBy('updated_at', 'DESC')->first();

        return response()->view('sitemap.index', [
            'profil'   => $profil,
            'prosedur' => $prosedur,
            'regulasi' => $regulasi,
            'dokumen'  => $dokumen,
        ])->header('Content-Type', 'text/xml');
    }

    public function prosedur()
    {
        $prosedurs = Prosedur::all();
        return response()->view('sitemap.prosedur', [
            'prosedurs' => $prosedurs,
        ])->header('Content-Type', 'text/xml');
    }
}
