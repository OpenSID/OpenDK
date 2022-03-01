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

namespace App\Http\Controllers\Page;

use App\Facades\Counter;
use App\Http\Controllers\Controller;
use App\Models\DataDesa;
use App\Models\Program;
use Illuminate\Support\Facades\DB;

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
        $year_list        = years_list();
        $list_desa        = DataDesa::all();

        return view('pages.program_bantuan.show_program_bantuan', compact('page_title', 'page_description', 'year_list', 'list_desa'));
    }

    public function getChartBantuanPenduduk()
    {
        return $this->get_data(1);
    }

    public function getChartBantuanKeluarga()
    {
        return $this->get_data(2);
    }

    // TODO : Gunakan relasi antar tabel.
    private function get_data(int $sasaran = 1)
    {
        $did  = request('did');
        $year = request('y');
        $data    = [];
        $program = Program::where('sasaran', $sasaran)->get();

        foreach ($program as $prog) {
            $query_result = DB::table('das_peserta_program')
                ->join('das_penduduk', 'das_peserta_program.kartu_nik', '=', 'das_penduduk.nik')
                ->where('das_peserta_program.sasaran', '=', $sasaran)
                ->where('das_peserta_program.program_id', '=', $prog->id);

            if ($year != 'Semua') {
                $query_result->whereYear('das_peserta_program.created_at', '=', $year);
            }

            if ($did != 'Semua') {
                $query_result->where('das_penduduk.desa_id', '=', $did);
            }

            $data[] = ['program' => $prog->nama, 'value' => $query_result->count()];
        }
        return $data;
    }
}
