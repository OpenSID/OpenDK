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
use App\Models\DataDesa;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;
use Illuminate\Support\Facades\DB;

class StatistikChartTingkatPendidikanService extends BaseApiService
{
    public function chart($did, $year)
    {
        if ($this->useDatabaseGabungan()) {
            $data = [
                'grafik' => $this->dataGabungan($did, $year),
                'tabel' => [],
            ];
            
            return $data;
        }
        return $this->localChart($did, $year);
    }

    private function dataGabungan($did, $year)
    {
        $dataPendidikan = [];
        if ($year == 'Semua' && $did == 'Semua') {
            $dataPendidikan = $this->dataGabunganSemua();
        } elseif ($year != 'Semua' && $did == 'Semua') {
            $dataPendidikan = $this->dataGabunganSemuaDesa($year);
        } elseif ($year != 'Semua' && $did != 'Semua') {
            $dataPendidikan = $this->dataGabunganSpesifik($did, $year);
        } elseif ($year == 'Semua' && $did != 'Semua') {
            $dataPendidikan = $this->dataGabunganSemuaTahun($did);
        }

        return $dataPendidikan;
    }

    private function dataGabunganSemua()
    {
        $client = new Client(['base_uri' => $this->baseUrl, 'headers' => $this->header]);
        $requests = [];
        $dataPendidikan = [];
        $filters = [
            'filter[id]' => 'pendidikan-dalam-kk',            
            'filter[kecamatan]' => config('profil.kecamatan_id'),
        ];
        foreach (years_list() as $year) {
            $filters['filter[tahun]'] = $year;
            $requests[$year] = $client->getAsync('/api/v1/statistik-web/penduduk', ['query' => $filters]);
        }
        try {
            $promises = Utils::unwrap($requests);
            $responses = Utils::settle($promises)->wait();
            
            foreach ($responses as $key => $response) {
                $json = collect(json_decode($response['value']->getBody()->getContents(), true)['data']);            
                $dataPendidikan[] = $this->mappingDataPendidikan($json, $key);
            }
        } catch (\Exception $e) {
            \Log::error('Failed get data in '.__FILE__.' function '.__METHOD__. $e->getMessage());
        }                
        return $dataPendidikan;
    }

    private function dataGabunganSemuaDesa($year)
    {
        $client = new Client(['base_uri' => $this->baseUrl, 'headers' => $this->header]);
        $requests = [];
        $dataPendidikan = [];
        $filters = [
            'filter[id]' => 'pendidikan-dalam-kk',            
            'filter[tahun]' => $year,
            'filter[kecamatan]' => config('profil.kecamatan_id'),
        ];
        $desaAll = (new DesaService)->listDesa();
        foreach ($desaAll as $desa) {
            $filters['filter[desa]'] = $desa->kode_desa;
            $requests[$desa->nama] = $client->getAsync('/api/v1/statistik-web/penduduk', ['query' => $filters]);
        }
        try {
            $promises = Utils::unwrap($requests);
            $responses = Utils::settle($promises)->wait();
            
            foreach ($responses as $key => $response) {
                $json = collect(json_decode($response['value']->getBody()->getContents(), true)['data']);            
                $dataPendidikan[] = $this->mappingDataPendidikan($json, $key);
            }
        } catch (\Exception $e) {
            \Log::error('Failed get data in '.__FILE__.' function '.__METHOD__. $e->getMessage());
        }                
        return $dataPendidikan;
    }

    private function dataGabunganSpesifik($did, $year)
    {        
        $dataPendidikan = [];
        $filters = [
            'filter[id]' => 'pendidikan-dalam-kk',            
            'filter[tahun]' => $year,
            'filter[desa]' => $did,
            'filter[kecamatan]' => config('profil.kecamatan_id'),
        ];
        
        try {
            $json = $this->apiRequest('/api/v1/statistik-web/penduduk', $filters);
            $dataPendidikan[] = $this->mappingDataPendidikan($json, $year);
        } catch (\Exception $e) {
            \Log::error('Failed get data in '.__FILE__.' function '.__METHOD__. $e->getMessage());
        }                
        return $dataPendidikan;
    }

    private function dataGabunganSemuaTahun($did)
    {
        $client = new Client(['base_uri' => $this->baseUrl, 'headers' => $this->header]);
        $requests = [];
        $dataPendidikan = [];
        $filters = [
            'filter[id]' => 'pendidikan-dalam-kk',     
            'filter[desa]' => $did,       
            'filter[kecamatan]' => config('profil.kecamatan_id'),
        ];
        foreach (years_list() as $year) {
            $filters['filter[tahun]'] = $year;
            $requests[$year] = $client->getAsync('/api/v1/statistik-web/penduduk', ['query' => $filters]);
        }
        try {
            $promises = Utils::unwrap($requests);
            $responses = Utils::settle($promises)->wait();
            
            foreach ($responses as $key => $response) {
                $json = collect(json_decode($response['value']->getBody()->getContents(), true)['data']);            
                $dataPendidikan[] = $this->mappingDataPendidikan($json, $key);
            }
        } catch (\Exception $e) {
            \Log::error('Failed get data in '.__FILE__.' function '.__METHOD__. $e->getMessage());
        }                
        return $dataPendidikan;
    }

    private function mappingDataPendidikan($response, $year){
        $dataFilter = collect($response)->filter(function ($item) {
            return !in_array($item['id'], [LabelStatistik::Total, LabelStatistik::Jumlah, LabelStatistik::BelumMengisi]);
        })->mapWithKeys(function ($item) {
            return [$item['attributes']['nama'] => $item['attributes']['jumlah']];
        })->toArray();
        
        return [
            'year' => $year,
            'tidak_tamat_sekolah' => ($dataFilter['TIDAK / BELUM SEKOLAH'] ?? 0) + ($dataFilter['BELUM TAMAT SD/SEDERAJAT'] ?? 0),
            'tamat_sd' => $dataFilter['TAMAT SD / SEDERAJAT'] ?? 0,
            'tamat_smp' => $dataFilter['SLTP/SEDERAJAT'] ?? 0,
            'tamat_sma' => $dataFilter['SLTA / SEDERAJAT'] ?? 0,
            'tamat_diploma_sederajat' => ($dataFilter['DIPLOMA I / II'] ?? 0) + ($dataFilter['AKADEMI/ DIPLOMA III/S. MUDA'] ?? 0),
        ];
    }
    private function localChart($did, $year)
    {
        // Grafik Data TIngkat Pendidikan
        $dataPendidikan = [];
        if ($year == 'Semua' && $did == 'Semua') {
            foreach (years_list() as $yearl) {
                // SD
                $queryPendidikan = DB::table('das_tingkat_pendidikan')
                    ->where('tahun', '=', $yearl);

                $dataPendidikan[] = [
                    'year' => $yearl,
                    'tidak_tamat_sekolah' => $queryPendidikan->sum('tidak_tamat_sekolah'),
                    'tamat_sd' => $queryPendidikan->sum('tamat_sd'),
                    'tamat_smp' => $queryPendidikan->sum('tamat_smp'),
                    'tamat_sma' => $queryPendidikan->sum('tamat_sma'),
                    'tamat_diploma_sederajat' => $queryPendidikan->sum('tamat_diploma_sederajat'),
                ];
            }
        } elseif ($year != 'Semua' && $did == 'Semua') {
            $dataTabel = [];
            // Quartal
            $desa = DataDesa::all();
            foreach ($desa as $value) {
                $queryPendidikan = DB::table('das_tingkat_pendidikan')
                    ->selectRaw('sum(tidak_tamat_sekolah) as tidak_tamat_sekolah, sum(tamat_sd) as tamat_sd, sum(tamat_smp) as tamat_smp, sum(tamat_sma) as tamat_sma, sum(tamat_diploma_sederajat) as tamat_diploma_sederajat')
                   // ->whereRaw('bulan in ('.$this->getIdsQuartal($key).')')
                    ->where('tahun', $year)
                    ->where('desa_id', '=', $value->desa_id)
                    ->get()->first();

                $dataTabel[] = [
                    'year' => $value->nama,
                    'tidak_tamat_sekolah' => intval($queryPendidikan->tidak_tamat_sekolah),
                    'tamat_sd' => intval($queryPendidikan->tamat_sd),
                    'tamat_smp' => intval($queryPendidikan->tamat_smp),
                    'tamat_sma' => intval($queryPendidikan->tamat_sma),
                    'tamat_diploma_sederajat' => intval($queryPendidikan->tamat_diploma_sederajat),
                ];
            }

            $dataPendidikan = $dataTabel;
        } elseif ($year != 'Semua' && $did != 'Semua') {
            $dataTabel = [];
            // Quartal
            foreach (semester() as $key => $value) {
                $queryPendidikan = DB::table('das_tingkat_pendidikan')
                    ->selectRaw('sum(tidak_tamat_sekolah) as tidak_tamat_sekolah, sum(tamat_sd) as tamat_sd, sum(tamat_smp) as tamat_smp, sum(tamat_sma) as tamat_sma, sum(tamat_diploma_sederajat) as tamat_diploma_sederajat')
                    // ->whereRaw('bulan in ('.$this->getIdsSemester($key).')')
                    ->where('tahun', $year)
                    ->where('desa_id', '=', $did)
                    ->get()->first();

                //return $queryPendidikan;
                $dataTabel[] = [
                    'year' => 'Semester '.$key,
                    'tidak_tamat_sekolah' => intval($queryPendidikan->tidak_tamat_sekolah),
                    'tamat_sd' => intval($queryPendidikan->tamat_sd),
                    'tamat_smp' => intval($queryPendidikan->tamat_smp),
                    'tamat_sma' => intval($queryPendidikan->tamat_sma),
                    'tamat_diploma_sederajat' => intval($queryPendidikan->tamat_diploma_sederajat),
                ];
            }

            $dataPendidikan = $dataTabel;
        } elseif ($year == 'Semua' && $did != 'Semua') {
            foreach (years_list() as $yearl) {
                // SD
                $queryPendidikan = DB::table('das_tingkat_pendidikan')
                    ->where('tahun', '=', $yearl)
                    ->where('desa_id', $did);

                $dataPendidikan[] = [
                    'year' => $yearl,
                    'tidak_tamat_sekolah' => $queryPendidikan->sum('tidak_tamat_sekolah'),
                    'tamat_sd' => $queryPendidikan->sum('tamat_sd'),
                    'tamat_smp' => $queryPendidikan->sum('tamat_smp'),
                    'tamat_sma' => $queryPendidikan->sum('tamat_sma'),
                    'tamat_diploma_sederajat' => $queryPendidikan->sum('tamat_diploma_sederajat'),
                ];
            }
        }

        // Data Tabel AKI & AKB
        $tabelKesehatan = [];

        return [
            'grafik' => $dataPendidikan,
            'tabel' => $tabelKesehatan,
        ];
    }
}
