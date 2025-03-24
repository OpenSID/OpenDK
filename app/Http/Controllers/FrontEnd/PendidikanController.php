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
use App\Services\DesaService;
use App\Services\StatistikChartTingkatPendidikanService;
use Illuminate\Support\Facades\DB;

class PendidikanController extends FrontEndController
{
    public $nama_kuartal = ['q1' => 'Kuartal 1', 'q2' => 'Kuartal 2', 'q3' => 'Kuartal 3', 'q4' => 'Kuartal 4'];

    protected $penduduk;
    
    private $desaService;

    public function __construct()
    {        
        $this->desaService = new DesaService();
        parent::__construct();
    }
    /**
     * Menampilkan Data Pendidikan
     **/
    public function showPendidikan()
    {
        Counter::count('statistik.pendidikan');

        $data['page_title'] = 'Pendidikan';
        $data['page_description'] = 'Data Pendidikan '.$this->sebutan_wilayah;
        $data['year_list'] = years_list();        
        $data['list_desa'] = $this->desaService->listDesa();
        $data['gabungan'] = $this->isDatabaseGabungan() ? true : false;

        return view('pages.pendidikan.show_pendidikan')->with($data);
    }

    public function getChartTingkatPendidikan()
    {
        $did = request('did');
        $year = request('y');

        return (new StatistikChartTingkatPendidikanService)->chart($did, $year);
        
    }

    public function getChartPutusSekolah()
    {
        $did = request('did');
        $year = request('y');

        // Grafik Data Siswa PAUD
        $dataPendidikan = [];
        if ($year == 'Semua' && $did == 'Semua') {
            foreach (years_list() as $yearl) {
                // SD
                $queryPendidikan = DB::table('das_putus_sekolah')
                    ->where('tahun', '=', $yearl);

                $dataPendidikan[] = [
                    'year' => $yearl,
                    'siswa_paud' => $queryPendidikan->sum('siswa_paud'),
                    'anak_usia_paud' => $queryPendidikan->sum('anak_usia_paud'),
                    'siswa_sd' => $queryPendidikan->sum('siswa_sd'),
                    'anak_usia_sd' => $queryPendidikan->sum('anak_usia_sd'),
                    'siswa_smp' => $queryPendidikan->sum('siswa_smp'),
                    'anak_usia_smp' => $queryPendidikan->sum('anak_usia_smp'),
                    'siswa_sma' => $queryPendidikan->sum('siswa_sma'),
                    'anak_usia_sma' => $queryPendidikan->sum('anak_usia_sma'),
                ];
            }
        } elseif ($year == 'Semua' && $did != 'Semua') {
            foreach (years_list() as $yearl) {
                // SD
                $queryPendidikan = DB::table('das_putus_sekolah')
                    ->where('tahun', '=', $yearl)
                    ->where('desa_id', $did);

                $dataPendidikan[] = [
                    'year' => $yearl,
                    'siswa_paud' => $queryPendidikan->sum('siswa_paud'),
                    'anak_usia_paud' => $queryPendidikan->sum('anak_usia_paud'),
                    'siswa_sd' => $queryPendidikan->sum('siswa_sd'),
                    'anak_usia_sd' => $queryPendidikan->sum('anak_usia_sd'),
                    'siswa_smp' => $queryPendidikan->sum('siswa_smp'),
                    'anak_usia_smp' => $queryPendidikan->sum('anak_usia_smp'),
                    'siswa_sma' => $queryPendidikan->sum('siswa_sma'),
                    'anak_usia_sma' => $queryPendidikan->sum('anak_usia_sma'),
                ];
            }
        } elseif ($year != 'Semua' && $did == 'Semua') {
            $desa = DataDesa::all();
            foreach ($desa as $value) {
                // SD
                $queryPendidikan = DB::table('das_putus_sekolah')
                    ->where('tahun', '=', $year)
                    ->where('desa_id', $value->desa_id);

                $dataPendidikan[] = [
                    'year' => $value->nama,
                    'siswa_paud' => $queryPendidikan->sum('siswa_paud'),
                    'anak_usia_paud' => $queryPendidikan->sum('anak_usia_paud'),
                    'siswa_sd' => $queryPendidikan->sum('siswa_sd'),
                    'anak_usia_sd' => $queryPendidikan->sum('anak_usia_sd'),
                    'siswa_smp' => $queryPendidikan->sum('siswa_smp'),
                    'anak_usia_smp' => $queryPendidikan->sum('anak_usia_smp'),
                    'siswa_sma' => $queryPendidikan->sum('siswa_sma'),
                    'anak_usia_sma' => $queryPendidikan->sum('anak_usia_sma'),
                ];
            }
        } elseif ($year != 'Semua' && $did != 'Semua') {
            $dataTabel = [];
            // Quartal
            foreach (semester() as $key => $kuartal) {
                $queryPendidikan = DB::table('das_putus_sekolah')
                    // ->whereRaw('bulan in ('.$this->getIdsSemester($key).')')
                    ->where('tahun', $year)
                    ->where('desa_id', '=', $did);

                $dataTabel[] = [
                    'year' => 'Semester '.$key,
                    'siswa_paud' => $queryPendidikan->sum('siswa_paud'),
                    'anak_usia_paud' => $queryPendidikan->sum('anak_usia_paud'),
                    'siswa_sd' => $queryPendidikan->sum('siswa_sd'),
                    'anak_usia_sd' => $queryPendidikan->sum('anak_usia_sd'),
                    'siswa_smp' => $queryPendidikan->sum('siswa_smp'),
                    'anak_usia_smp' => $queryPendidikan->sum('anak_usia_smp'),
                    'siswa_sma' => $queryPendidikan->sum('siswa_sma'),
                    'anak_usia_sma' => $queryPendidikan->sum('anak_usia_sma'),
                ];
            }

            $dataPendidikan = $dataTabel;
        }

        // Data Tabel AKI & AKB
        $tabelKesehatan = [];

        return [
            'grafik' => $dataPendidikan,
            'tabel' => $tabelKesehatan,
        ];
    }

    public function getChartFasilitasPAUD()
    {
        $did = request('did');
        $year = request('y');

        // Grafik Data Fasilitas PAUD
        $dataPendidikan = [];
        if ($year == 'Semua') {
            foreach (years_list() as $yearl) {
                // SD
                $queryPendidikan = DB::table('das_fasilitas_paud')
                    ->where('tahun', '=', $yearl);
                if ($did != 'Semua') {
                    $queryPendidikan->where('desa_id', '=', $did);
                }

                $dataPendidikan[] = [
                    'year' => $yearl,
                    'jumlah_paud' => $queryPendidikan->sum('jumlah_paud'),
                    'jumlah_guru_paud' => $queryPendidikan->sum('jumlah_guru_paud'),
                    'jumlah_siswa_paud' => $queryPendidikan->sum('jumlah_siswa_paud'),
                ];
            }
        } else {
            $dataTabel = [];
            // Quartal
            foreach (semester() as $key => $kuartal) {
                $queryPendidikan = DB::table('das_fasilitas_paud')
                    ->whereRaw('semester in ('.$this->getIdsSemester($key).')')
                    ->where('tahun', $year);
                if ($did != 'Semua') {
                    $queryPendidikan->where('desa_id', '=', $did);
                }
                $dataTabel[] = [
                    'year' => 'Semester '.$key,
                    'jumlah_paud' => $queryPendidikan->sum('jumlah_paud'),
                    'jumlah_guru_paud' => $queryPendidikan->sum('jumlah_guru_paud'),
                    'jumlah_siswa_paud' => $queryPendidikan->sum('jumlah_siswa_paud'),
                ];
            }

            $dataPendidikan = $dataTabel;
        }

        // Data Tabel AKI & AKB
        $tabelKesehatan = [];

        return [
            'grafik' => $dataPendidikan,
            'tabel' => $tabelKesehatan,
        ];
    }

    private function getIdsQuartal($q)
    {
        $quartal = kuartal_bulan()[$q];
        $ids = '';
        foreach ($quartal as $key => $val) {
            $ids .= $key.',';
        }

        return rtrim($ids, ',');
    }

    private function getIdsSemester($smt)
    {
        $semester = semester()[$smt];
        $ids = '';
        foreach ($semester as $key => $val) {
            $ids .= $key.',';
        }

        return rtrim($ids, ',');
    }
}
