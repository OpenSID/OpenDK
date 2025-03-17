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

namespace App\Services;

use App\Enums\LabelStatistik;
use Illuminate\Support\Facades\DB;

class StatistikChartPendudukGolDarahService extends BaseApiService
{
    private $colors = [1 => '#f97d7d', 2 => '#f86565', 3 => '#f74d4d', 4 => '#f63434', 13 => '#f51c1c'];
    public function chart($did, $year)
    {
        if ($this->useDatabaseGabungan()) {
            $data = [];
            try {
                $filters = [
                    'filter[id]' => 'golongan-darah',
                    'filter[tahun]' => $year,
                    'filter[kecamatan]' => $this->kodeKecamatan,
                ];
                if ($did != 'Semua') {
                    $filters['filter[desa]'] = $did;
                }
                $response = $this->apiRequest('/api/v1/statistik-web/penduduk', $filters);
                foreach ($response as $key => $item) {
                    if (in_array($item['id'], [LabelStatistik::Total, LabelStatistik::Jumlah, LabelStatistik::BelumMengisi])) {
                        continue;
                    }
                    $data[] = ['blod_type' => $item['attributes']['nama'], 'total' => $item['attributes']['jumlah'], 'color' => $this->colors[$key] ?? '#'.random_color()];
                }
            } catch (\Exception $e) {
                \Log::error('Failed get data in '.__FILE__.' function chart()'. $e->getMessage());
            }
            return $data;
        }
        return $this->localChart($did, $year);
    }

    private function localChart($did, $year)
    {
        // Data Chart Penduduk By Golongan Darah
        $data = [];
        $golonganDarah = DB::table('ref_golongan_darah')->orderBy('id')->get();
        foreach ($golonganDarah as $val) {
            $queryTotal = DB::table('das_penduduk')
                //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
                ->leftJoin('ref_pendidikan_kk', 'das_penduduk.pendidikan_kk_id', '=', 'ref_pendidikan_kk.id')
                //->whereRaw('year(das_keluarga.tgl_daftar)= ?', $year)
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year);
            if ($val->id != 13) {
                $queryTotal->where('das_penduduk.golongan_darah_id', '=', $val->id);
            } else {
                $queryTotal->whereRaw('das_penduduk.golongan_darah_id = 13 or das_penduduk.golongan_darah_id is null');
            }

            if ($did != 'Semua') {
                $queryTotal->where('das_penduduk.desa_id', '=', $did);
            }
            $total = $queryTotal->count();
            $data[] = ['blod_type' => $val->nama, 'total' => $total, 'color' => $this->colors[$val->id] ?? '#'.random_color()];
        }

        return $data;
    }
}
