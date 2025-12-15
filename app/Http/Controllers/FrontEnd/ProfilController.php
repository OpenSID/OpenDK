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
use App\Models\DataDesa;
use App\Models\DataUmum;
use App\Models\FormDokumen;
use App\Models\Pengurus;
use App\Models\Profil;
use App\Services\DesaService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use App\Traits\BaganTrait;

class ProfilController extends FrontEndController
{
    use BaganTrait;

    public function tipologi()
    {
        Counter::count('profil.tipologi');

        $page_title = 'Tipologi';

        $page_description = $this->browser_title;

        return view('pages.profil.tipologi', compact('page_title', 'page_description'));
    }

    public function sejarah()
    {
        Counter::count('profil.sejarah');

        $page_title = 'Sejarah';

        $page_description = $this->browser_title;

        return view('pages.profil.sejarah', compact('page_title', 'page_description'));
    }

    /**
     * Menampilkan Halaman Profil Kecamatan
     **/
    public function LetakGeografis()
    {
        Counter::count('profil.letak-geografis');

        $wilayah_desa = (new DesaService())->listPathDesa();
        $page_title = 'Letak Geografis';

        $page_description = $this->browser_title;

        $view = $this->isDatabaseGabungan() ? 'pages.profil.gabungan.letakgeografis' : 'pages.profil.letakgeografis';

        return view($view, compact('page_title', 'page_description', 'wilayah_desa'));
    }

    public function StrukturPemerintahan()
    {
        Counter::count('profil.struktur-pemerintahan');

        $page_title = 'Struktur Pemerintahan';
        $page_description = $this->browser_title;


        return view('pages.profil.strukturpemerintahan', compact('page_title', 'page_description'));
    }

    public function VisiMisi()
    {
        Counter::count('profil.visi-misi');

        $page_title = 'Visi dan Misi';
        $page_description = $this->browser_title;

        return view('pages.profil.visimisi', compact('page_title', 'page_description'));
    }

    public function Kependudukan()
    {
        Counter::count('profil.kependudukan');

        $data['page_title'] = 'Kependudukan';
        $data['page_description'] = 'Statistik Kependudukan';
        $data['year_list'] = years_list();
        $data['list_desa'] = DataDesa::all();

        $data = array_merge($data, $this->createDashboardKependudukan('Semua', date('Y')));

        return view('pages.kependudukan.show_kependudukan')->with($data);
    }

    public function showProfile()
    {
        $profil = $this->profil;
        $dokumen = FormDokumen::take(5)->get();

        $page_title = 'Profil';

        $page_description = $this->browser_title;

        return view('pages.profil.show_profil', compact('page_title', 'page_description', 'profil', 'dokumen'));
    }

    public function Sambutan()
    {
        Counter::count('profil.sambutan');

        $page_title = 'Sambutan';
        $page_description = $this->browser_title;

        return view('pages.profil.sambutan', compact('page_title', 'page_description'));
    }

    public function StrukturOrganisasi(Request $request)
    {
        Counter::count('profil.struktur-organisasi');

        $profil = $this->profil;
        $page_title = 'Struktur Organisasi';

        $page_description = $this->browser_title;

        return view('pages.profil.struktur-organisasi', compact('page_title', 'page_description'));
    }

    public function ajaxBaganPublic()
    {
        return response()->json($this->getDataStrukturOrganisasi());
    }
}
