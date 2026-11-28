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

namespace App\Services;

use App\Models\Profil;
use Illuminate\Support\Facades\DB;

class AnggaranRealisasiChartService
{
    public function chart($mid, $year)
    {
        $profil_id = Profil::first()->id;
        // Grafik Data Pendidikan
        $data_pendidikan = [];
        if ($year == 'Semua') {
            $total_anggaran = 0;
            $total_belanja = 0;
            $belanja_pegawai = 0;
            $belanja_barang_jasa = 0;
            $belanja_modal = 0;
            $belanja_tidak_langsung = 0;

            foreach (array_sort(years_list()) as $yearls) {
                $query_result = DB::table('das_anggaran_realisasi')
                    ->select('*')
                    ->where('profil_id', '=', $profil_id);
                if ($mid != 'Semua') {
                    $query_result->where('bulan', '=', $mid);
                }
                $query_result->where('tahun', '=', $yearls);

                $res = $query_result->first();

                if (! empty($res)) {
                    $total_anggaran = $res->total_anggaran;
                    $total_belanja = $res->total_belanja;
                    $belanja_pegawai = $res->belanja_pegawai;
                    $belanja_barang_jasa = $res->belanja_barang_jasa;
                    $belanja_modal = $res->belanja_modal;
                    $belanja_tidak_langsung = $res->belanja_tidak_langsung;
                } else {
                    $total_anggaran += 0;
                    $total_belanja += 0;
                    $belanja_pegawai += 0;
                    $belanja_barang_jasa += 0;
                    $belanja_modal += 0;
                    $belanja_tidak_langsung += 0;
                }
            }

            $data_pendidikan['sum'] = [
                'total_belanja' => (float) ($total_belanja),
                'total_belanja_persen' => $this->persen($total_belanja, $total_anggaran),
                'selisih_anggaran_realisasi' => (float) (0),
                'selisih_anggaran_realisasi_persen' => (float) (0),
                'belanja_pegawai' => (float) ($belanja_pegawai),
                'belanja_pegawai_persen' => $this->persen($belanja_pegawai, $total_belanja),
                'belanja_barang_jasa' => (float) ($belanja_barang_jasa),
                'belanja_barang_jasa_persen' => $this->persen($belanja_barang_jasa, $total_belanja),
                'belanja_modal' => (float) ($belanja_modal),
                'belanja_modal_persen' => $this->persen($belanja_modal, $total_belanja),
                'belanja_tidak_langsung' => (float) ($belanja_tidak_langsung),
                'belanja_tidak_langsung_persen' => $this->persen($belanja_tidak_langsung, $total_belanja),
            ];
            $data_pendidikan['chart'] = [
                [
                    'anggaran' => 'Belanja Pegawai',
                    'value' => $this->persen($belanja_pegawai, $total_belanja),
                ],
                [
                    'anggaran' => 'Belanja Barang dan Jasa',
                    'value' => $this->persen($belanja_barang_jasa, $total_belanja),
                ],
                [
                    'anggaran' => 'Belanja Modal',
                    'value' => $this->persen($belanja_modal, $total_belanja),
                ],
                [
                    'anggaran' => 'Belanja Tidak Langsung',
                    'value' => $this->persen($belanja_tidak_langsung, $total_belanja),
                ],
            ];
        } else {
            $total_anggaran = 0;
            $total_belanja = 0;
            $belanja_pegawai = 0;
            $belanja_barang_jasa = 0;
            $belanja_modal = 0;
            $belanja_tidak_langsung = 0;

            $query_result = DB::table('das_anggaran_realisasi')
                ->select(
                    DB::raw('sum(total_anggaran) as total_anggaran'),
                    DB::raw('sum(total_belanja) as total_belanja'),
                    DB::raw('sum(belanja_pegawai) as belanja_pegawai'),
                    DB::raw('sum(belanja_barang_jasa) as belanja_barang_jasa'),
                    DB::raw('sum(belanja_modal) as belanja_modal'),
                    DB::raw('sum(belanja_tidak_langsung) as belanja_tidak_langsung')
                )
                ->where('profil_id', '=', $profil_id);

            if ($mid != 'Semua') {
                $query_result->where('bulan', '=', $mid);
            }
            $query_result->where('tahun', '=', $year);

            $res = $query_result->first();

            if (! empty($res)) {
                $total_anggaran = $res->total_anggaran;
                $total_belanja = $res->total_belanja;
                $belanja_pegawai = $res->belanja_pegawai;
                $belanja_barang_jasa = $res->belanja_barang_jasa;
                $belanja_modal = $res->belanja_modal;
                $belanja_tidak_langsung = $res->belanja_tidak_langsung;
            }

            $data_pendidikan['sum'] = [
                'total_belanja' => (float) ($total_belanja),
                'total_belanja_persen' => $this->persen($total_belanja, $total_anggaran),
                'selisih_anggaran_realisasi' => (float) (0),
                'selisih_anggaran_realisasi_persen' => (float) (0),
                'belanja_pegawai' => (float) ($belanja_pegawai),
                'belanja_pegawai_persen' => $this->persen($belanja_pegawai, $total_belanja),
                'belanja_barang_jasa' => (float) ($belanja_barang_jasa),
                'belanja_barang_jasa_persen' => $this->persen($belanja_barang_jasa, $total_belanja),
                'belanja_modal' => (float) ($belanja_modal),
                'belanja_modal_persen' => $this->persen($belanja_modal, $total_belanja),
                'belanja_tidak_langsung' => (float) ($belanja_tidak_langsung),
                'belanja_tidak_langsung_persen' => $this->persen($belanja_tidak_langsung, $total_belanja),
            ];
            $data_pendidikan['chart'] = [
                [
                    'anggaran' => 'Belanja Pegawai',
                    'value' => $this->persen($belanja_pegawai, $total_belanja),
                ],
                [
                    'anggaran' => 'Belanja Barang dan Jasa',
                    'value' => $this->persen($belanja_barang_jasa, $total_belanja),
                ],
                [
                    'anggaran' => 'Belanja Modal',
                    'value' => $this->persen($belanja_modal, $total_belanja),
                ],
                [
                    'anggaran' => 'Belanja Tidak Langsung',
                    'value' => $this->persen($belanja_tidak_langsung, $total_belanja),
                ],
            ];
        }

        return $data_pendidikan;
    }

    /**
     * Hitung persentase nilai terhadap pembagi; bernilai 0 bila pembagi kosong/nol.
     */
    private function persen($nilai, $pembagi): float
    {
        $pembagi = (float) $pembagi;

        return $pembagi == 0.0 ? 0.0 : ((float) $nilai / $pembagi) * 100;
    }
}
