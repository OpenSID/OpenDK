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
use App\Models\Profil;
use function array_sort;

use function config;
use Illuminate\Support\Facades\DB;
use function number_format;
use function request;
use function view;
use function years_list;

class AnggaranRealisasiController extends Controller
{
    /**
     * Menampilkan Data Anggaran Dan realisasi Kecamatan
     **/
    public function showAnggaranDanRealisasi()
    {
        Counter::count('statistik.anggaran-dan-realisasi');

        $data['page_title']       = 'Anggaran & Realisasi';
        $data['page_description'] = 'Data Anggaran & Realisasi ' .$this->sebutan_wilayah;
        $defaultProfil            = config('app.default_profile');
        $data['defaultProfil']    = $defaultProfil;
        $data['year_list']        = years_list();
        $data['list_kecamatan']   = Profil::with('kecamatan')->orderBy('kecamatan_id', 'desc')->get();
        /*$data['list_desa'] = DB::table('ref_desa')->select('*')->where('kecamatan_id', '=', $defaultProfil)->get();*/

        return view('pages.anggaran_realisasi.show_anggaran_realisasi')->with($data);
    }

    public function getChartAnggaranRealisasi()
    {
        $mid  = request('mid');
        $year = request('y');

        // Grafik Data Pendidikan
        $data_pendidikan = [];
        if ($year == 'ALL') {
            $total_anggaran         = 0;
            $total_belanja          = 0;
            $belanja_pegawai        = 0;
            $belanja_barang_jasa    = 0;
            $belanja_modal          = 0;
            $belanja_tidak_langsung = 0;

            foreach (array_sort(years_list()) as $yearls) {
                $query_result = DB::table('das_anggaran_realisasi')
                    ->select('*')
                    ->where('kecamatan_id', '=', config('app.default_profile'));
                if ($mid != 'ALL') {
                    $query_result->where('bulan', '=', $mid);
                }
                $query_result->where('tahun', '=', $yearls);

                $res = $query_result->first();

                if (! empty($res)) {
                    $total_anggaran         = $res->total_anggaran;
                    $total_belanja          = $res->total_belanja;
                    $belanja_pegawai        = $res->belanja_pegawai;
                    $belanja_barang_jasa    = $res->belanja_barang_jasa;
                    $belanja_modal          = $res->belanja_modal;
                    $belanja_tidak_langsung = $res->belanja_tidak_langsung;
                } else {
                    $total_anggaran         += 0;
                    $total_belanja          += 0;
                    $belanja_pegawai        += 0;
                    $belanja_barang_jasa    += 0;
                    $belanja_modal          += 0;
                    $belanja_tidak_langsung += 0;
                }
            }

            $data_pendidikan['sum']   = [
                'total_belanja'                     => number_format($total_belanja),
                'total_belanja_persen'              => number_format(($total_belanja / $total_anggaran) * 100, 1),
                'selisih_anggaran_realisasi'        => number_format(0),
                'selisih_anggaran_realisasi_persen' => number_format(0),
                'belanja_pegawai'                   => number_format($belanja_pegawai),
                'belanja_pegawai_persen'            => number_format(($belanja_pegawai / $total_belanja) * 100, 1),
                'belanja_barang_jasa'               => number_format($belanja_barang_jasa),
                'belanja_barang_jasa_persen'        => number_format(($belanja_barang_jasa / $total_belanja) * 100, 1),
                'belanja_modal'                     => number_format($belanja_modal),
                'belanja_modal_persen'              => number_format(($belanja_modal / $total_belanja) * 100, 1),
                'belanja_tidak_langsung'            => number_format($belanja_tidak_langsung),
                'belanja_tidak_langsung_persen'     => number_format(($belanja_tidak_langsung / $total_belanja) * 100, 1),
            ];
            $data_pendidikan['chart'] = [
                [
                    'anggaran' => 'Belanja Pegawai',
                    'value'    => number_format(($belanja_pegawai / $total_belanja) * 100, 1),
                ],
                [
                    'anggaran' => 'Belanja Barang dan Jasa',
                    'value'    => number_format(($belanja_barang_jasa / $total_belanja) * 100, 1),
                ],
                [
                    'anggaran' => 'Belanja Modal',
                    'value'    => number_format(($belanja_modal / $total_belanja) * 100, 1),
                ],
                [
                    'anggaran' => 'Belanja Tidak Langsung',
                    'value'    => number_format(($belanja_tidak_langsung / $total_belanja) * 100, 1),
                ],
            ];
        } else {
            $total_anggaran         = 0;
            $total_belanja          = 0;
            $belanja_pegawai        = 0;
            $belanja_barang_jasa    = 0;
            $belanja_modal          = 0;
            $belanja_tidak_langsung = 0;

            $query_result = DB::table('das_anggaran_realisasi')
                ->selectRaw('sum(total_anggaran) as total_anggaran, sum(total_belanja) as total_belanja,
                sum(belanja_pegawai) as belanja_pegawai, sum(belanja_barang_jasa) as belanja_barang_jasa,
                sum(belanja_modal) as belanja_modal, sum(belanja_tidak_langsung) as belanja_tidak_langsung')
                ->where('kecamatan_id', '=', config('app.default_profile'));

            if ($mid != 'ALL') {
                $query_result->where('bulan', '=', $mid);
            }
            $query_result->where('tahun', '=', $year);

            $res = $query_result->first();

            if (! empty($res)) {
                $total_anggaran         = $res->total_anggaran;
                $total_belanja          = $res->total_belanja;
                $belanja_pegawai        = $res->belanja_pegawai;
                $belanja_barang_jasa    = $res->belanja_barang_jasa;
                $belanja_modal          = $res->belanja_modal;
                $belanja_tidak_langsung = $res->belanja_tidak_langsung;
            }

            $data_pendidikan['sum']   = [
                'total_belanja'                     => number_format($total_belanja),
                'total_belanja_persen'              => number_format(($total_belanja / $total_anggaran) * 100, 1),
                'selisih_anggaran_realisasi'        => number_format(0),
                'selisih_anggaran_realisasi_persen' => number_format(0),
                'belanja_pegawai'                   => number_format($belanja_pegawai),
                'belanja_pegawai_persen'            => number_format(($belanja_pegawai / $total_belanja) * 100, 1),
                'belanja_barang_jasa'               => number_format($belanja_barang_jasa),
                'belanja_barang_jasa_persen'        => number_format(($belanja_barang_jasa / $total_belanja) * 100, 1),
                'belanja_modal'                     => number_format($belanja_modal),
                'belanja_modal_persen'              => number_format(($belanja_modal / $total_belanja) * 100, 1),
                'belanja_tidak_langsung'            => number_format($belanja_tidak_langsung),
                'belanja_tidak_langsung_persen'     => number_format(($belanja_tidak_langsung / $total_belanja) * 100, 1),
            ];
            $data_pendidikan['chart'] = [
                [
                    'anggaran' => 'Belanja Pegawai',
                    'value'    => number_format(($belanja_pegawai / $total_belanja) * 100, 1),
                ],
                [
                    'anggaran' => 'Belanja Barang dan Jasa',
                    'value'    => number_format(($belanja_barang_jasa / $total_belanja) * 100, 1),
                ],
                [
                    'anggaran' => 'Belanja Modal',
                    'value'    => number_format(($belanja_modal / $total_belanja) * 100, 1),
                ],
                [
                    'anggaran' => 'Belanja Tidak Langsung',
                    'value'    => number_format(($belanja_tidak_langsung / $total_belanja) * 100, 1),
                ],
            ];
        }

        return $data_pendidikan;
    }
}
