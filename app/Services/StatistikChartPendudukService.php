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

use App\Models\Penduduk;
use BeyondCode\QueryDetector\Outputs\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;

class StatistikChartPendudukService extends BaseApiService
{
    public function chart($did, $listYears)
    {        
        if ($this->useDatabaseGabungan()) {
            $data = [];
            try {                
                $filters = [
                    'filter[id]' => 'jenis-kelamin',                    
                    'filter[kecamatan]' => $this->kodeKecamatan,
                ];
                if($did != 'Semua') {
                    $filters['filter[desa]'] = $did;
                }
                $client = new Client(['base_uri' => $this->baseUrl, 'headers' => $this->header]);
                $requests = [];
                foreach ($listYears as $year) {
                    $filters['filter[tahun]'] = $year;
                    $requests[$year] = $client->getAsync('/api/v1/statistik-web/penduduk', ['query' => $filters]);
                }        
                $promises = Utils::unwrap($requests);
                
                $responses = Utils::settle($promises)->wait();                
                foreach ($responses as $key => $response) {
                    $json = collect(json_decode($response['value']->getBody()->getContents(), true)['data'])->keyBy('id');                    
                    $queryResultLaki = $json->get(1)['attributes']['jumlah'] ?? 0;
                    $queryResultPerempuan = $json->get(2)['attributes']['jumlah'] ?? 0;                    
                    $data[] = ['year' => $key, 'value_lk' => $queryResultLaki, 'value_pr' => $queryResultPerempuan];
                }
                
            } catch (\Exception $e) {                
                \Log::error('Failed get data in '.__FILE__.' function chart()'. $e->getMessage());
            }
            return $data;
        }
        return $this->localChart($did, $listYears);
    }

    private function localChart($did, $listYears){
        // Data Grafik Pertumbuhan        
        $penduduk = new Penduduk();
        $data = [];
        foreach ($listYears as $yearls) {
            $query = $penduduk->getPendudukAktif($did, $yearls);
            $queryResultLaki = (clone $query)->where('sex', 1)->count();
            $queryResultPerempuan = (clone $query)->where('sex', 2)->count();

            $data[] = ['year' => $yearls, 'value_lk' => $queryResultLaki, 'value_pr' => $queryResultPerempuan];
        }
        return $data;
    }
}
