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
use App\Models\Penduduk;
use Illuminate\Support\Facades\DB;

class StatistikChartPendudukAgamaService extends BaseApiService
{    
    private $colors = [1 => '#dcaf1e', 2 => '#dc9f1e', 3 => '#dc8f1e', 4 => '#dc7f1e', 5 => '#dc6f1e', 6 => '#dc5f1e', 7 => '#dc4f1e'];
    public function chart($did, $year)
    {
        if ($this->useDatabaseGabungan()) {
            $data = [];
            try {
                $filters = [
                    'filter[id]' => 'agama',
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
                    $data[] = ['religion' => ucfirst(strtolower($item['attributes']['nama'])), 'total' => $item['attributes']['jumlah'], 'color' => $this->colors[$key] ?? '#'.random_color()];
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
        $penduduk = new Penduduk();
        // Data Chart Penduduk By Aama
        $data = [];
        $agama = DB::table('ref_agama')->orderBy('id')->get();        
        foreach ($agama as $val) {
            $total = $penduduk->getPendudukAktif($did, $year)
                ->where('agama_id', $val->id)
                ->count();

            $data[] = ['religion' => ucfirst(strtolower($val->nama)), 'total' => $total, 'color' => $this->colors[$val->id]];
        }

        return $data;
    }
}
