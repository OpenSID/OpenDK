<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\FrontEnd;

use App\Facades\Counter;
use App\Http\Controllers\FrontEndController;
use App\Models\Penduduk;
use App\Models\Profil;
use App\Services\StatistikPendudukService;

class KependudukanController extends FrontEndController
{
    protected $profil;

    protected $penduduk;
    private $statistikPendudukService;


    public function __construct(Profil $profil, Penduduk $penduduk)
    {
        $this->profil = $profil;
        $this->penduduk = $penduduk;
        $this->statistikPendudukService = new StatistikPendudukService();
        parent::__construct();
    }

    /**
     * Menampilkan Data Kependudukan
     **/
    public function showKependudukan()
    {
        Counter::count('statistik.kependudukan');

        $data['page_title'] = 'Kependudukan';
        $data['page_description'] = 'Statistik Kependudukan';                

        return view('pages.kependudukan.show_kependudukan')->with($data);
    }

    /* Menghasilkan array berisi semua tahun di mana penduduk tercatat sampai tahun sekarang */
    protected function yearsList($max_tahun = null)
    {
        $min_tahun = $this->statistikPendudukService->minYear() ?? date('Y');

        $daftar_tahun = [];
        for ($y = $min_tahun; $y <= ($max_tahun ?? date('Y')); $y++) {
            $daftar_tahun[] = $y;
        }

        return $daftar_tahun;
    }

    public function showKependudukanPartial()
    {
        $data['page_title'] = 'Kependudukan';
        $data['page_description'] = 'Statistik Kependudukan';
        $data['year_list'] = $this->yearsList();

        if (request('did') && request('y')) {
            $data = array_merge($data, $this->createDashboardKependudukan(request('did'), request('y')));
        }

        return $data;
    }

    protected function createDashboardKependudukan($did, $year)
    {
        $data = $this->statistikPendudukService->dashboard($did, $year);
        return $data;
    }        
}
