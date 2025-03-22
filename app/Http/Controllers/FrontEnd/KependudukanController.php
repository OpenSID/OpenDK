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
use App\Services\DesaService;
use App\Services\StatistikChartPendudukAgamaService;
use App\Services\StatistikChartPendudukGolDarahService;
use App\Services\StatistikChartPendudukPendidikanService;
use App\Services\StatistikChartPendudukPerkawinanService;
use App\Services\StatistikChartPendudukService;
use App\Services\StatistikChartPendudukUsiaService;
use App\Services\StatistikPendudukService;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class KependudukanController extends FrontEndController
{
    protected $profil;

    protected $penduduk;
    private $statistikPendudukService;
    private $desaService;

    public function __construct(Profil $profil, Penduduk $penduduk)
    {
        $this->profil = $profil;
        $this->penduduk = $penduduk;
        $this->statistikPendudukService = new StatistikPendudukService();
        $this->desaService = new DesaService();
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
        $data['year_list'] = $this->yearsList();
        $data['list_desa'] = $this->desaService->listDesa();

        $data = array_merge($data, $this->createDashboardKependudukan('Semua', date('Y')));

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

    public function getChartPenduduk()
    {
        $did = request('did');
        $year = request('y');
        $listYears = array_sort($this->yearsList($year));
        $data = (new StatistikChartPendudukService())->chart($did, $listYears);

        return $data;
    }

    public function getChartPendudukUsia()
    {
        $did = request('did');
        $year = request('y');
        $data = (new StatistikChartPendudukUsiaService())->chart($did, $year);

        return $data;
    }

    public function getChartPendudukPendidikan()
    {
        $did = request('did');
        $year = request('y');
        $data = (new StatistikChartPendudukPendidikanService())->chart($did, $year);

        return $data;
    }

    public function getChartPendudukGolDarah()
    {
        $did = request('did');
        $year = request('y');
        $data = (new StatistikChartPendudukGolDarahService())->chart($did, $year);
        
        return $data;
    }

    public function getChartPendudukKawin()
    {
        $did = request('did');
        $year = request('y');
        $data = (new StatistikChartPendudukPerkawinanService())->chart($did, $year);

        return $data;
    }

    public function getChartPendudukAgama()
    {
        $did = request('did');
        $year = request('y');
        $data = (new StatistikChartPendudukAgamaService())->chart($did, $year);
        
        return $data;
    }

    public function getChartPendudukKelamin()
    {
        $did = request('did');
        $year = request('y');

        // Data Chart Penduduk By Jenis Kelamin
        $data = [];
        $kelamin = [
            [
                'id' => 1,
                'nama' => 'Laki-Laki',
            ],
            [
                'id' => 2,
                'nama' => 'Perempuan',
            ],
        ];
        foreach ($kelamin as $val) {
            $query_total = DB::table('das_penduduk')
                //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year)
                ->where('das_penduduk.sex', '=', $val['id']);

            if ($did != 'Semua') {
                $query_total->where('das_penduduk.desa_id', '=', $did);
            }
            $total = $query_total->count();
            $data[] = ['sex' => $val['nama'], 'total' => $total, 'color' => '#'.random_color()];
        }

        return $data;
    }

    public function getDataPenduduk()
    {
        $type = request('t');
        $did = request('did');
        $year = request('year');

        $query = DB::table('das_penduduk')
            ->join('ref_pendidikan_kk', 'das_penduduk.pendidikan_kk_id', '=', 'ref_pendidikan_kk.id')
            ->join('ref_kawin', 'das_penduduk.status_kawin', '=', 'ref_kawin.id')
            ->join('ref_pekerjaan', 'das_penduduk.pekerjaan_id', '=', 'ref_pekerjaan.id')
            ->selectRaw('das_penduduk.id, das_penduduk.nik, das_penduduk.nama, das_penduduk.no_kk, das_penduduk.alamat_sekarang as alamat,
            ref_pendidikan_kk.nama as pendidikan,
            das_penduduk.tanggal_lahir, ref_kawin.nama as status_kawin, ref_pekerjaan.nama as pekerjaan, das_penduduk.foto');
        if ($type == 'C') {
            $query->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year);
            if ($did != 'Semua') {
                $query->where('das_penduduk.desa_id', '=', $did);
            }
        }
        if ($type == 'L') {
            $query->where('das_penduduk.sex', '=', 1)
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year);
            if ($did != 'Semua') {
                $query->where('das_penduduk.desa_id', '=', $did);
            }
        }
        if ($type == 'P') {
            $query->where('das_penduduk.sex', '=', 2)
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year);
            if ($did != 'Semua') {
                $query->where('das_penduduk.desa_id', '=', $did);
            }
        }
        if ($type == 'D') {
            $query->where('das_penduduk.cacat_id', '<>', 7)
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year);
            if ($did != 'Semua') {
                $query->where('das_penduduk.desa_id', '=', $did);
            }
        }

        return DataTables::of($query)
            ->addColumn('tanggal_lahir', function ($row) {
                return convert_born_date_to_age($row->tanggal_lahir);
            })->make(true);
    }
}
