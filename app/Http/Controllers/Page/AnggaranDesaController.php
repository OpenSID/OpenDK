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
 * @package	    OpenDK
 * @author	    Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\CoaType;
use App\Models\DataDesa;
use Illuminate\Support\Facades\DB;

class AnggaranDesaController extends Controller
{
    /**
     * Menampilkan Data Anggaran Dan realisasi Kecamatan
     **/
    public function showAnggaranDesa()
    {
        $data['page_title']       = 'Anggaran Desa (APBDes)';
        $data['page_description'] = 'Data Anggaran Desa (APBDes)';
        $data['year_list']        = years_list();
        $data['list_desa']        = DataDesa::all();

        return view('pages.anggaran_desa.show_anggaran_desa')->with($data);
    }

    public function getChartAnggaranDesa()
    {
        $mid  = request('mid');
        $did  = request('did');
        $year = request('y');

        // Grafik Data Pendidikan
        $data_anggaran = [];
        $type          = CoaType::all();

        if ($mid == 'Semua' && $year == 'Semua') {
            $tmp = [];
            foreach ($type as $val) {
                $query_anggaran = DB::table('das_anggaran_desa')->select('*')
                    ->where('no_akun', 'LIKE', $val->id . '%');
                if ($did != 'Semua') {
                    $query_anggaran->where('desa_id', $did);
                }
                $tmp[] = [
                    'anggaran' => $val->id . ' - ' . $val->type_name,
                    'jumlah'   => $query_anggaran->sum('jumlah'),
                ];
            }

            $data_anggaran['grafik'] = $tmp;
        } elseif ($mid != 'Semua' && $year == 'Semua') {
            $tmp = [];
            foreach ($type as $val) {
                $query_anggaran = DB::table('das_anggaran_desa')->select('*')
                    ->where('no_akun', 'LIKE', $val->id . '%')
                    ->where('bulan', $mid);
                if ($did != 'Semua') {
                    $query_anggaran->where('desa_id', $did);
                }
                $tmp[] = [
                    'anggaran' => $val->id . ' - ' . $val->type_name,
                    'jumlah'   => $query_anggaran->sum('jumlah'),
                ];
            }

            $data_anggaran['grafik'] = $tmp;
        } elseif ($mid == 'Semua' && $year != 'Semua') {
            $tmp = [];
            foreach ($type as $val) {
                $query_anggaran = DB::table('das_anggaran_desa')->select('*')
                    ->where('no_akun', 'LIKE', $val->id . '%')
                    ->where('tahun', $year);
                if ($did != 'Semua') {
                    $query_anggaran->where('desa_id', $did);
                }
                $tmp[] = [
                    'anggaran' => $val->id . ' - ' . $val->type_name,
                    'jumlah'   => $query_anggaran->sum('jumlah'),
                ];
            }
            $data_anggaran['grafik'] = $tmp;
        } elseif ($mid != 'Semua' && $year != 'Semua') {
            $tmp = [];
            foreach ($type as $val) {
                $query_anggaran = DB::table('das_anggaran_desa')->select('*')
                    ->where('no_akun', 'LIKE', $val->id . '%')
                    ->where('bulan', $mid)
                    ->where('tahun', $year);
                if ($did != 'Semua') {
                    $query_anggaran->where('desa_id', $did);
                }
                $tmp[] = [
                    'anggaran' => $val->id . ' - ' . $val->type_name,
                    'jumlah'   => $query_anggaran->sum('jumlah'),
                ];
            }
            $data_anggaran['grafik'] = $tmp;
        }
        $data_anggaran['detail'] = view('pages.anggaran_desa.detail_anggaran', compact('did', 'mid', 'year'))->render();
        return $data_anggaran;
    }
}
