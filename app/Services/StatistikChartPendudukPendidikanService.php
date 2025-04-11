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

class StatistikChartPendudukPendidikanService extends BaseApiService
{
    public function chart($did, $year)
    {
        if ($this->useDatabaseGabungan()) {
            $data = [];
            try {
                $filters = [
                    'filter[id]' => 'pendidikan-dalam-kk',
                    'filter[tahun]' => $year,
                    'filter[kecamatan]' => $this->kodeKecamatan,
                ];
                if ($did != 'Semua') {
                    $filters['filter[desa]'] = $did;
                }
                $response = $this->apiRequest('/api/v1/statistik-web/penduduk', $filters);
                $dataFilter = collect($response)->filter(function ($item) {
                    return !in_array($item['id'], [LabelStatistik::Total, LabelStatistik::Jumlah, LabelStatistik::BelumMengisi]);
                })->mapWithKeys(function ($item) {
                    return [$item['attributes']['nama'] => $item['attributes']['jumlah']];
                })->toArray();
                //{"TIDAK \/ BELUM SEKOLAH":1355,"BELUM TAMAT SD\/SEDERAJAT":1208,"TAMAT SD \/ SEDERAJAT":2360,"SLTP\/SEDERAJAT":1758,"SLTA \/ SEDERAJAT":4632,"DIPLOMA I \/ II":209,"AKADEMI\/ DIPLOMA III\/S. MUDA":348,"DIPLOMA IV\/ STRATA I":1421,"STRATA II":93,"STRATA III":4}
                $data[] = [
                    'year' => $year,
                    'SD' => $dataFilter['TAMAT SD / SEDERAJAT'] ?? 0,
                    'SLTP' => $dataFilter['SLTP/SEDERAJAT'] ?? 0,
                    'SLTA' => $dataFilter['SLTA / SEDERAJAT'] ?? 0,
                    'DIPLOMA' => ($dataFilter['DIPLOMA I / II'] ?? 0) + ($dataFilter['AKADEMI/ DIPLOMA III/S. MUDA'] ?? 0),
                    'SARJANA' => ($dataFilter['DIPLOMA IV/ STRATA I'] ?? 0) + ($dataFilter['STRATA II']  ?? 0) + ($dataFilter['STRATA III'] ?? 0),
                ];
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
        // Grafik Data Pendidikan
        $data = [];
        $query_pendidikan = $penduduk->getPendudukAktif($did, $year)
            ->leftJoin('ref_pendidikan_kk', 'pendidikan_kk_id', '=', 'ref_pendidikan_kk.id');
        // SD
        $totalSd = (clone $query_pendidikan)
                ->where('pendidikan_kk_id', 3)
                ->count();

        // SMP
        $totalSltp = (clone $query_pendidikan)
            ->where('pendidikan_kk_id', 4)
            ->count();

        //SMA
        $totalSlta = (clone $query_pendidikan)
                ->where('pendidikan_kk_id', 5)
                ->count();

        // DIPLOMA
        $totalDiploma = (clone $query_pendidikan)
                ->whereRaw('(pendidikan_kk_id = 6 or pendidikan_kk_id = 7)')
                ->count();

        // SARJANA
        $totalSarjana = (clone $query_pendidikan)
                ->whereRaw('(pendidikan_kk_id = 8 or pendidikan_kk_id = 9 or pendidikan_kk_id = 10)')
                ->count();

        $data[] = [
            'year' => $year,
            'SD' => $totalSd,
            'SLTP' => $totalSltp,
            'SLTA' => $totalSlta,
            'DIPLOMA' => $totalDiploma,
            'SARJANA' => $totalSarjana,
        ];
        return $data;
    }
}
