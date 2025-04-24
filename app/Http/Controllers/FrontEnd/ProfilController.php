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

        $profil = Profil::with(['dataUmum'])->first();
        $page_title = 'Tipologi';
        if (isset($profil)) {
            $page_description = $this->browser_title;
        }

        return view('pages.profil.tipologi', compact('page_title', 'page_description', 'profil'));
    }

    public function sejarah()
    {
        Counter::count('profil.sejarah');

        $profil = Profil::with(['dataUmum'])->first();
        $page_title = 'Sejarah';
        if (isset($profil)) {
            $page_description = $this->browser_title;
        }

        return view('pages.profil.sejarah', compact('page_title', 'page_description', 'profil'));
    }

    /**
     * Menampilkan Halaman Profil Kecamatan
     **/
    public function LetakGeografis()
    {
        Counter::count('profil.letak-geografis');

        $profil = Profil::with(['dataDesa'])->first();
        $wilayah_desa = (new DesaService())->listPathDesa();
        $data_umum = DataUmum::first();
        $page_title = 'Letak Geografis';
        if (isset($profil)) {
            $page_description = $this->browser_title;
        }

        $view = $this->isDatabaseGabungan() ? 'pages.profil.gabungan.letakgeografis' : 'pages.profil.letakgeografis';

        return view($view, compact('page_title', 'page_description', 'profil', 'wilayah_desa', 'data_umum'));
    }

    public function StrukturPemerintahan()
    {
        Counter::count('profil.struktur-pemerintahan');

        $profil = $this->profil;
        $pengurus = Pengurus::status()->get()->sortBy('jabatan.jenis');
        $page_title = 'Struktur Pemerintahan';
        if (isset($profil)) {
            $page_description = $this->browser_title;
        }

        return view('pages.profil.strukturpemerintahan', compact('page_title', 'page_description', 'profil', 'pengurus'));
    }

    public function VisiMisi()
    {
        Counter::count('profil.visi-misi');

        $profil = $this->profil;
        $page_title = 'Visi dan Misi';
        if (isset($profil)) {
            $page_description = $this->browser_title;
        }

        return view('pages.profil.visimisi', compact('page_title', 'page_description', 'profil'));
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
        $dokumen = DB::table('das_form_dokumen')->take(5)->get();

        $page_title = 'Profil';
        if (isset($profil)) {
            $page_description = $this->browser_title;
        }

        return view('pages.profil.show_profil', compact('page_title', 'page_description', 'profil', 'dokumen'));
    }

    public function Sambutan()
    {
        Counter::count('profil.sambutan');

        $profil = $this->profil;
        $page_title = 'Sambutan';
        if (isset($profil)) {
            $page_description = $this->browser_title;
        }

        return view('pages.profil.sambutan', compact('page_title', 'page_description', 'profil'));
    }

    public function StrukturOrganisasi(Request $request)
    {
        Counter::count('profil.struktur-organisasi');

        $profil = $this->profil;
        $pengurus = Pengurus::status()->get()->sortBy('jabatan.jenis');
        $page_title = 'Struktur Organisasi';
        if (isset($profil)) {
            $page_description = $this->browser_title;
        }

        return view('pages.profil.struktur-organisasi', compact('page_title', 'page_description', 'profil', 'pengurus',));
    }

    public function ajaxBaganPublic()
    {
        return response()->json($this->getDataStrukturOrganisasi());
    }
}
