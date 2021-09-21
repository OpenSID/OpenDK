<?php

/*
 * File ini bagian dari:
 *
 * PBB Desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright	Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Page;

use App\Facades\Counter;
use App\Http\Controllers\Controller;
use App\Models\Program;
use function compact;

use function config;
use Illuminate\Support\Facades\DB;
use function request;
use function rtrim;
use function view;
use function years_list;

class ProgramBantuanController extends Controller
{
    /**
     * Menampilkan Data Program Bantuan
     **/
    public function showProgramBantuan()
    {
        Counter::count('statistik.program-bantuan');

        $page_title       = 'Program Bantuan';
        $page_description = 'Data Program Bantuan';
        $defaultProfil    = config('app.default_profile');
        $year_list        = years_list();
        $list_desa        = DB::table('das_data_desa')->select('*')->where('kecamatan_id', '=', $defaultProfil)->get();
        return view('pages.program_bantuan.show_program_bantuan', compact('page_title', 'page_description', 'defaultProfil', 'year_list', 'list_desa'));
    }

    public function getChartBantuanPenduduk()
    {
        $kid  = config('app.default_profile');
        $did  = request('did');
        $year = request('y');

        // Data Grafik Bantuan Penduduk/Perorangan
        $data    = [];
        $program = Program::where('sasaran', 1)->get();

        foreach ($program as $prog) {
            $query_result = DB::table('das_peserta_program')
                ->join('das_penduduk', 'das_peserta_program.peserta', '=', 'das_penduduk.nik')
                ->where('das_penduduk.kecamatan_id', '=', $kid)
                ->where('das_peserta_program.sasaran', '=', 1)
                ->where('das_peserta_program.program_id', '=', $prog->id);
            if ($year == 'ALL') {
                $query_result->whereRaw('YEAR(das_peserta_program.created_at) in (?)', $this->where_year_helper());
            } else {
                $query_result->where('das_penduduk.tahun', $year);
            }

            if ($did != 'ALL') {
                $query_result->where('das_penduduk.desa_id', '=', $did);
            }

            $data[] = ['program' => $prog->nama, 'value' => $query_result->count()];
        }
        return $data;
    }

    public function getChartBantuanKeluarga()
    {
        $kid  = config('app.default_profile');
        $did  = request('did');
        $year = request('y');

        // Data Grafik Bantuan Penduduk/Perorangan
        $data    = [];
        $program = Program::where('sasaran', 2)->get();

        foreach ($program as $prog) {
            $query_result = DB::table('das_peserta_program')
                ->join('das_penduduk', 'das_peserta_program.peserta', '=', 'das_penduduk.no_kk')
                ->where('das_penduduk.kecamatan_id', '=', $kid)
                ->where('das_peserta_program.sasaran', '=', 2)
                ->where('das_peserta_program.program_id', '=', $prog->id);
            if ($year == 'ALL') {
                $query_result->whereRaw('YEAR(das_peserta_program.created_at) in (?)', $this->where_year_helper());
            } else {
                $query_result->where('das_penduduk.tahun', $year);
            }

            if ($did != 'ALL') {
                $query_result->where('das_penduduk.desa_id', '=', $did);
            }
            $query_result->groupBy('das_penduduk.no_kk');

            $data[] = ['program' => $prog->nama, 'value' => $query_result->count()];
        }
        return $data;
    }

    protected function where_year_helper()
    {
        $str = '';
        foreach (years_list() as $year) {
            $str .= $year . ',';
        }
        return rtrim($str, ',');
    }
}
