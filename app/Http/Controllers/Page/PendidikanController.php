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
use App\Models\DataDesa;
use Illuminate\Support\Facades\DB;

class PendidikanController extends Controller
{
    public $nama_kuartal = ['q1' => 'Kuartal 1', 'q2' => 'Kuartal 2', 'q3' => 'Kuartal 3', 'q4' => 'Kuartal 4'];
    /**
     * Menampilkan Data Pendidikan
     **/
    public function showPendidikan()
    {
        Counter::count('statistik.pendidikan');

        $data['page_title']       = 'Pendidikan';
        $data['page_description'] = 'Data Pendidikan ' . $this->sebutan_wilayah;
        $data['year_list']        = years_list();
        $data['list_desa']        = DataDesa::all();

        return view('pages.pendidikan.show_pendidikan')->with($data);
    }

    public function getChartTingkatPendidikan()
    {
        $pid  = request('pid');
        $did  = request('did');
        $year = request('y');

        // Grafik Data TIngkat Pendidikan
        $data_pendidikan = [];
        if ($year == 'Semua' && $did == 'Semua') {
            foreach (years_list() as $yearl) {
                // SD
                $query_pendidikan = DB::table('das_tingkat_pendidikan')
                    ->where('tahun', '=', $yearl)
                    ->where('profil', '=', $pid);

                $data_pendidikan[] = [
                    'year'                    => $yearl,
                    'tidak_tamat_sekolah'     => $query_pendidikan->sum('tidak_tamat_sekolah'),
                    'tamat_sd'                => $query_pendidikan->sum('tamat_sd'),
                    'tamat_smp'               => $query_pendidikan->sum('tamat_smp'),
                    'tamat_sma'               => $query_pendidikan->sum('tamat_sma'),
                    'tamat_diploma_sederajat' => $query_pendidikan->sum('tamat_diploma_sederajat'),
                ];
            }
        } elseif ($year != "Semua" && $did == "Semua") {
            $data_tabel = [];
            // Quartal
            $desa = DataDesa::all();
            foreach ($desa as $value) {
                $query_pendidikan = DB::table('das_tingkat_pendidikan')
                    ->selectRaw('sum(tidak_tamat_sekolah) as tidak_tamat_sekolah, sum(tamat_sd) as tamat_sd, sum(tamat_smp) as tamat_smp, sum(tamat_sma) as tamat_sma, sum(tamat_diploma_sederajat) as tamat_diploma_sederajat')
                   // ->whereRaw('bulan in ('.$this->getIdsQuartal($key).')')
                    ->where('tahun', $year)
                    ->where('desa_id', '=', $value->desa_id)
                    ->get()->first();

                $data_tabel[] = [
                    'year'                    => $value->nama,
                    'tidak_tamat_sekolah'     => intval($query_pendidikan->tidak_tamat_sekolah),
                    'tamat_sd'                => intval($query_pendidikan->tamat_sd),
                    'tamat_smp'               => intval($query_pendidikan->tamat_smp),
                    'tamat_sma'               => intval($query_pendidikan->tamat_sma),
                    'tamat_diploma_sederajat' => intval($query_pendidikan->tamat_diploma_sederajat),
                ];
            }

            $data_pendidikan = $data_tabel;
        } elseif ($year != 'Semua' && $did != 'Semua') {
            $data_tabel = [];
            // Quartal
            foreach (semester() as $key => $value) {
                $query_pendidikan = DB::table('das_tingkat_pendidikan')
                    ->selectRaw('sum(tidak_tamat_sekolah) as tidak_tamat_sekolah, sum(tamat_sd) as tamat_sd, sum(tamat_smp) as tamat_smp, sum(tamat_sma) as tamat_sma, sum(tamat_diploma_sederajat) as tamat_diploma_sederajat')
                    ->whereRaw('bulan in (' . $this->getIdsSemester($key) . ')')
                    ->where('tahun', $year)
                    ->where('desa_id', '=', $did)
                    ->get()->first();

                //return $query_pendidikan;
                $data_tabel[] = [
                    'year'                    => 'Semester ' . $key,
                    'tidak_tamat_sekolah'     => intval($query_pendidikan->tidak_tamat_sekolah),
                    'tamat_sd'                => intval($query_pendidikan->tamat_sd),
                    'tamat_smp'               => intval($query_pendidikan->tamat_smp),
                    'tamat_sma'               => intval($query_pendidikan->tamat_sma),
                    'tamat_diploma_sederajat' => intval($query_pendidikan->tamat_diploma_sederajat),
                ];
            }

            $data_pendidikan = $data_tabel;
        } elseif ($year == 'Semua' && $did != 'Semua') {
            foreach (years_list() as $yearl) {
                // SD
                $query_pendidikan = DB::table('das_tingkat_pendidikan')
                    ->where('tahun', '=', $yearl)
                    ->where('profil_id', '=', $pid)
                    ->where('desa_id', $did);

                $data_pendidikan[] = [
                    'year'                    => $yearl,
                    'tidak_tamat_sekolah'     => $query_pendidikan->sum('tidak_tamat_sekolah'),
                    'tamat_sd'                => $query_pendidikan->sum('tamat_sd'),
                    'tamat_smp'               => $query_pendidikan->sum('tamat_smp'),
                    'tamat_sma'               => $query_pendidikan->sum('tamat_sma'),
                    'tamat_diploma_sederajat' => $query_pendidikan->sum('tamat_diploma_sederajat'),
                ];
            }
        }

        // Data Tabel AKI & AKB
        $tabel_kesehatan = [];

        return [
            'grafik' => $data_pendidikan,
            'tabel'  => $tabel_kesehatan,
        ];
    }

    public function getChartPutusSekolah()
    {
        $pid  = request('pid');
        $did  = request('did');
        $year = request('y');

        // Grafik Data Siswa PAUD
        $data_pendidikan = [];
        if ($year == 'Semua' && $did == 'Semua') {
            foreach (years_list() as $yearl) {
                // SD
                $query_pendidikan = DB::table('das_putus_sekolah')
                    ->where('tahun', '=', $yearl)
                    ->where('profil_id', '=', $pid);

                $data_pendidikan[] = [
                    'year'           => $yearl,
                    'siswa_paud'     => $query_pendidikan->sum('siswa_paud'),
                    'anak_usia_paud' => $query_pendidikan->sum('anak_usia_paud'),
                    'siswa_sd'       => $query_pendidikan->sum('siswa_sd'),
                    'anak_usia_sd'   => $query_pendidikan->sum('anak_usia_sd'),
                    'siswa_smp'      => $query_pendidikan->sum('siswa_smp'),
                    'anak_usia_smp'  => $query_pendidikan->sum('anak_usia_smp'),
                    'siswa_sma'      => $query_pendidikan->sum('siswa_sma'),
                    'anak_usia_sma'  => $query_pendidikan->sum('anak_usia_sma'),
                ];
            }
        } elseif ($year == 'Semua' && $did != 'Semua') {
            foreach (years_list() as $yearl) {
                // SD
                $query_pendidikan = DB::table('das_putus_sekolah')
                    ->where('tahun', '=', $yearl)
                    ->where('profil_id', '=', $pid)
                    ->where('desa_id', $did);

                $data_pendidikan[] = [
                    'year'           => $yearl,
                    'siswa_paud'     => $query_pendidikan->sum('siswa_paud'),
                    'anak_usia_paud' => $query_pendidikan->sum('anak_usia_paud'),
                    'siswa_sd'       => $query_pendidikan->sum('siswa_sd'),
                    'anak_usia_sd'   => $query_pendidikan->sum('anak_usia_sd'),
                    'siswa_smp'      => $query_pendidikan->sum('siswa_smp'),
                    'anak_usia_smp'  => $query_pendidikan->sum('anak_usia_smp'),
                    'siswa_sma'      => $query_pendidikan->sum('siswa_sma'),
                    'anak_usia_sma'  => $query_pendidikan->sum('anak_usia_sma'),
                ];
            }
        } elseif ($year != 'Semua' && $did == 'Semua') {
            $desa = DataDesa::all();
            foreach ($desa as $value) {
                // SD
                $query_pendidikan = DB::table('das_putus_sekolah')
                    ->where('tahun', '=', $year)
                    ->where('profil_id', '=', $pid)
                    ->where('desa_id', $value->desa_id);

                $data_pendidikan[] = [
                    'year'           => $value->nama,
                    'siswa_paud'     => $query_pendidikan->sum('siswa_paud'),
                    'anak_usia_paud' => $query_pendidikan->sum('anak_usia_paud'),
                    'siswa_sd'       => $query_pendidikan->sum('siswa_sd'),
                    'anak_usia_sd'   => $query_pendidikan->sum('anak_usia_sd'),
                    'siswa_smp'      => $query_pendidikan->sum('siswa_smp'),
                    'anak_usia_smp'  => $query_pendidikan->sum('anak_usia_smp'),
                    'siswa_sma'      => $query_pendidikan->sum('siswa_sma'),
                    'anak_usia_sma'  => $query_pendidikan->sum('anak_usia_sma'),
                ];
            }
        } elseif ($year != 'Semua' && $did != 'Semua') {
            $data_tabel = [];
            // Quartal
            foreach (semester() as $key => $kuartal) {
                $query_pendidikan = DB::table('das_putus_sekolah')
                    ->whereRaw('bulan in (' . $this->getIdsSemester($key) . ')')
                    ->where('tahun', $year)
                    ->where('desa_id', '=', $did);

                $data_tabel[] = [
                    'year'           => 'Semester ' . $key,
                    'siswa_paud'     => $query_pendidikan->sum('siswa_paud'),
                    'anak_usia_paud' => $query_pendidikan->sum('anak_usia_paud'),
                    'siswa_sd'       => $query_pendidikan->sum('siswa_sd'),
                    'anak_usia_sd'   => $query_pendidikan->sum('anak_usia_sd'),
                    'siswa_smp'      => $query_pendidikan->sum('siswa_smp'),
                    'anak_usia_smp'  => $query_pendidikan->sum('anak_usia_smp'),
                    'siswa_sma'      => $query_pendidikan->sum('siswa_sma'),
                    'anak_usia_sma'  => $query_pendidikan->sum('anak_usia_sma'),
                ];
            }

            $data_pendidikan = $data_tabel;
        }

        // Data Tabel AKI & AKB
        $tabel_kesehatan = [];

        return [
            'grafik' => $data_pendidikan,
            'tabel'  => $tabel_kesehatan,
        ];
    }

    public function getChartFasilitasPAUD()
    {
        $pid  = request('pid');
        $did  = request('did');
        $year = request('y');

        // Grafik Data Fasilitas PAUD
        $data_pendidikan = [];
        if ($year == 'Semua') {
            foreach (years_list() as $yearl) {
                // SD
                $query_pendidikan = DB::table('das_fasilitas_paud')
                    ->where('tahun', '=', $yearl)
                    ->where('profil_id', '=', $pid);
                if ($did != 'Semua') {
                    $query_pendidikan->where('desa_id', '=', $did);
                }

                $data_pendidikan[] = [
                    'year'              => $yearl,
                    'jumlah_paud'       => $query_pendidikan->sum('jumlah_paud'),
                    'jumlah_guru_paud'  => $query_pendidikan->sum('jumlah_guru_paud'),
                    'jumlah_siswa_paud' => $query_pendidikan->sum('jumlah_siswa_paud'),
                ];
            }
        } else {
            $data_tabel = [];
            // Quartal
            foreach (semester() as $key => $kuartal) {
                $query_pendidikan = DB::table('das_fasilitas_paud')
                    ->whereRaw('semester in (' . $this->getIdsSemester($key) . ')')
                    ->where('tahun', $year);
                if ($did != 'Semua') {
                    $query_pendidikan->where('desa_id', '=', $did);
                }
                $data_tabel[] = [
                    'year'              => 'Semester ' . $key,
                    'jumlah_paud'       => $query_pendidikan->sum('jumlah_paud'),
                    'jumlah_guru_paud'  => $query_pendidikan->sum('jumlah_guru_paud'),
                    'jumlah_siswa_paud' => $query_pendidikan->sum('jumlah_siswa_paud'),
                ];
            }

            $data_pendidikan = $data_tabel;
        }

        // Data Tabel AKI & AKB
        $tabel_kesehatan = [];

        return [
            'grafik' => $data_pendidikan,
            'tabel'  => $tabel_kesehatan,
        ];
    }

    private function getIdsQuartal($q)
    {
        $quartal = kuartal_bulan()[$q];
        $ids     = '';
        foreach ($quartal as $key => $val) {
            $ids .= $key . ',';
        }
        return rtrim($ids, ',');
    }

    private function getIdsSemester($smt)
    {
        $semester = semester()[$smt];
        $ids      = '';
        foreach ($semester as $key => $val) {
            $ids .= $key . ',';
        }
        return rtrim($ids, ',');
    }
}
