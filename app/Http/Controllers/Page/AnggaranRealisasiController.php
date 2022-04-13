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
use Illuminate\Support\Facades\DB;

class AnggaranRealisasiController extends Controller
{
    /**
     * Menampilkan Data Anggaran Dan realisasi Kecamatan
     **/
    public function showAnggaranDanRealisasi()
    {
        Counter::count('statistik.anggaran-dan-realisasi');

        $data['page_title']       = 'Anggaran & Realisasi';
        $data['page_description'] = 'Data Anggaran & Realisasi';
        $data['year_list']        = years_list();
        $data['list_desa']        = DataDesa::all();

        return view('pages.anggaran_realisasi.show_anggaran_realisasi')->with($data);
    }

    // TODO : Sederhanakan, buat jadi 1 kondisi yg lebih fleksibel.
    public function getChartAnggaranRealisasi()
    {
        $mid  = request('mid');
        $year = request('y');

        // Grafik Data Pendidikan
        $data_pendidikan = [];
        if ($year == 'Semua') {
            $total_anggaran         = 0;
            $total_belanja          = 0;
            $belanja_pegawai        = 0;
            $belanja_barang_jasa    = 0;
            $belanja_modal          = 0;
            $belanja_tidak_langsung = 0;

            foreach (array_sort(years_list()) as $yearls) {
                $query_result = DB::table('das_anggaran_realisasi')
                    ->select('*')
                    ->where('profil_id', '=', $this->profil->id);
                if ($mid != 'Semua') {
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
                'total_belanja'                     => (float) ($total_belanja),
                'total_belanja_persen'              => (float) (($total_belanja == 0) ? 0 : ($total_belanja / $total_anggaran) * 100),
                'selisih_anggaran_realisasi'        => (float)(0),
                'selisih_anggaran_realisasi_persen' => (float)(0),
                'belanja_pegawai'                   => (float)($belanja_pegawai),
                'belanja_pegawai_persen'            => (float)(($belanja_pegawai == 0) ? 0 : ($belanja_pegawai / $total_belanja) * 100),
                'belanja_barang_jasa'               => (float)($belanja_barang_jasa),
                'belanja_barang_jasa_persen'        => (float)(($belanja_pegawai == 0) ? 0 : ($belanja_barang_jasa / $total_belanja) * 100),
                'belanja_modal'                     => (float)($belanja_modal),
                'belanja_modal_persen'              => (float)(($belanja_modal == 0) ? 0 : ($belanja_modal / $total_belanja) * 100),
                'belanja_tidak_langsung'            => (float)($belanja_tidak_langsung),
                'belanja_tidak_langsung_persen'     => (float)(($belanja_tidak_langsung == 0) ? 0 : ($belanja_tidak_langsung / $total_belanja) * 100),
            ];
            $data_pendidikan['chart'] = [
                [
                    'anggaran' => 'Belanja Pegawai',
                    'value'    => (float)(($belanja_pegawai == 0) ? 0 : ($belanja_pegawai / $total_belanja) * 100),
                ],
                [
                    'anggaran' => 'Belanja Barang dan Jasa',
                    'value'    => (float)(($belanja_barang_jasa == 0) ? 0 : ($belanja_barang_jasa / $total_belanja) * 100),
                ],
                [
                    'anggaran' => 'Belanja Modal',
                    'value'    => (float)(($belanja_modal == 0) ? 0 : ($belanja_modal / $total_belanja) * 100),
                ],
                [
                    'anggaran' => 'Belanja Tidak Langsung',
                    'value'    => (float)(($belanja_tidak_langsung == 0) ? 0 : ($belanja_tidak_langsung / $total_belanja) * 100),
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
                ->where('profil_id', '=', $this->profil->id);

            if ($mid != 'Semua') {
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
                'total_belanja'                     => (float)($total_belanja),
                'total_belanja_persen'              => (float)(($total_belanja == 0) ? 0 : ($total_belanja / $total_anggaran) * 100),
                'selisih_anggaran_realisasi'        => (float)(0),
                'selisih_anggaran_realisasi_persen' => (float)(0),
                'belanja_pegawai'                   => (float)($belanja_pegawai),
                'belanja_pegawai_persen'            => (float)(($belanja_pegawai == 0) ? 0 : ($belanja_pegawai / $total_belanja) * 100),
                'belanja_barang_jasa'               => (float)($belanja_barang_jasa),
                'belanja_barang_jasa_persen'        => (float)(($belanja_pegawai == 0) ? 0 : ($belanja_barang_jasa / $total_belanja) * 100),
                'belanja_modal'                     => (float)($belanja_modal),
                'belanja_modal_persen'              => (float)(($belanja_modal == 0) ? 0 : ($belanja_modal / $total_belanja) * 100),
                'belanja_tidak_langsung'            => (float)($belanja_tidak_langsung),
                'belanja_tidak_langsung_persen'     => (float)(($belanja_tidak_langsung == 0) ? 0 : ($belanja_tidak_langsung / $total_belanja) * 100),
            ];
            $data_pendidikan['chart'] = [
                [
                    'anggaran' => 'Belanja Pegawai',
                    'value'    => (float)(($belanja_pegawai == 0) ? 0 : ($belanja_pegawai / $total_belanja) * 100),
                ],
                [
                    'anggaran' => 'Belanja Barang dan Jasa',
                    'value'    => (float)(($belanja_barang_jasa == 0) ? 0 : ($belanja_barang_jasa / $total_belanja) * 100),
                ],
                [
                    'anggaran' => 'Belanja Modal',
                    'value'    => (float)(($belanja_modal == 0) ? 0 : ($belanja_modal / $total_belanja) * 100),
                ],
                [
                    'anggaran' => 'Belanja Tidak Langsung',
                    'value'    => (float)(($belanja_tidak_langsung == 0) ? 0 : ($belanja_tidak_langsung / $total_belanja) * 100),
                ],
            ];
        }

        return $data_pendidikan;
    }
}
