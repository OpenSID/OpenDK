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

class StatistikChartPendudukUsiaService extends BaseApiService
{
    private $colors = [7 => '#09a8ff', 6 => '#09bcff', 5 => '#09d1ff', 4 => '#09e5ff', 3 => '#09faff', 2 => '#09fff0', 1 => '#09ffdc'];
    public function chart($did, $year)
    {
        if ($this->useDatabaseGabungan()) {
            $data = [];
            try {
                $filters = [
                    'filter[id]' => 'rentang-umur',
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
                    $data[] = ['umur' => ucfirst(strtolower($item['attributes']['nama'])), 'value' => $item['attributes']['jumlah'], 'color' => $this->colors[$key] ?? '#09a8ff'];
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
        // Data Grafik Kategori Usia
        $data = [];
        $categories = DB::table('ref_umur')->orderBy('ref_umur.dari')->where('status', '=', 1)->get();
        $data = [];
        $penduduk = new Penduduk();
        foreach ($categories as $umur) {
            $query_total = $penduduk->getPendudukAktif($did, $year)
                ->whereRaw('DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(das_penduduk.tanggal_lahir)), \'%Y\')+0 >= ? ', $umur->dari)
                ->whereRaw('DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(tanggal_lahir)), \'%Y\')+0 <= ?', $umur->sampai);

            $total = $query_total->count();
            $data[] = ['umur' => ucfirst(strtolower($umur->nama)).' ('.$umur->dari.' - '.$umur->sampai.' tahun)', 'value' => $total, 'color' => $this->colors[$umur->id]];
        }
        return $data;
    }
}
