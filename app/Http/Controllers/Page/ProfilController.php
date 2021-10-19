<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright	Hak Cipta 2017 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Page;

use App\Facades\Counter;
use App\Http\Controllers\Controller;
use App\Models\Profil;
use function array_merge;

use function compact;
use function config;
use function date;
use Illuminate\Support\Facades\DB;
use function strtolower;
use function ucwords;
use function view;
use function years_list;

class ProfilController extends Controller
{
    /**
     * Menampilkan Halaman Profil Kecamatan
     **/


    public function LetakGeografis()
    {
        Counter::count('profil.letak-geografis');

        $defaultProfil = config('app.default_profile');

        $profil = Profil::where('kecamatan_id', $defaultProfil)->first();

        $page_title = 'Letak Geografis';
        if (isset($profil)) {
            $page_description = ucwords(strtolower($profil->kecamatan->nama));
        }

        return view('pages.profil.letakgeografis', compact('page_title', 'page_description', 'profil', 'defaultProfil'));
    }

    public function StrukturPemerintahan()
    {
        Counter::count('profil.struktur-pemerintahan');

        $defaultProfil = config('app.default_profile');
        $profil        = Profil::where('kecamatan_id', $defaultProfil)->first();

        $dokumen = DB::table('das_form_dokumen')->take(5)->get();

        $page_title = 'Struktur Pemerintahan';
        if (isset($profil)) {
            $page_description = ucwords(strtolower($profil->kecamatan->nama));
        }
        return view('pages.profil.strukturpemerintahan', compact('page_title', 'page_description', 'profil'));
    }

    public function Kependudukan()
    {
        Counter::count('profil.kependudukan');

        $data['page_title']       = 'Kependudukan';
        $data['page_description'] = 'Statistik Kependudukan';
        $defaultProfil            = config('app.default_profile');
        $data['defaultProfil']    = $defaultProfil;
        $data['year_list']        = years_list();
        $data['list_kecamatan']   = Profil::with('kecamatan')->orderBy('kecamatan_id', 'desc')->get();
        $data['list_desa']        = DB::table('das_data_desa')->select('*')->where('kecamatan_id', '=', $defaultProfil)->get();

        $data = array_merge($data, $this->createDashboardKependudukan($defaultProfil, 'ALL', date('Y')));

        return view('pages.kependudukan.show_kependudukan')->with($data);
    }

    public function VisiMisi()
    {
        Counter::count('profil.visi-misi');

        $defaultProfil = config('app.default_profile');
        $profil        = Profil::where('kecamatan_id', $defaultProfil)->first();

        $dokumen = DB::table('das_form_dokumen')->take(5)->get();

        $page_title = 'Visi dan Misi';
        if (isset($profil)) {
            $page_description = ucwords(strtolower($profil->kecamatan->nama));
        }
        return view('pages.profil.visimisi', compact('page_title', 'page_description', 'profil'));
    }

    public function sejarah()
    {
        Counter::count('profil.sejarah');

        $defaultProfil = config('app.default_profile');

        $profil     = Profil::where('kecamatan_id', $defaultProfil)->first();
        $page_title = 'Sejarah';
        if (isset($profil)) {
            $page_description = ucwords(strtolower($profil->kecamatan->nama));
        }

        return view('pages.profil.sejarah', compact('page_title', 'page_description', 'profil'));
    }

    public function showProfile()
    {
        $defaultProfil = config('app.default_profile');

        $profil = Profil::where('kecamatan_id', $defaultProfil)->first();

        $dokumen = DB::table('das_form_dokumen')->take(5)->get();

        $page_title = 'Profil';
        if (isset($profil)) {
            $page_description = ucwords(strtolower($this->sebutan_wilayah . ' ' . $profil->kecamatan->nama));
        }

        return view('pages.profil.show_profil', compact('page_title', 'page_description', 'profil', 'defaultProfil', 'dokumen'));
    }
}
