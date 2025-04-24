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

use App\Models\CoaType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StatistikChartAnggaranDesaService extends BaseApiService
{
    private $dataTree;
    public function chart($mid, $did, $year)
    {
        if ($this->useDatabaseGabungan()) {
            $data = [];
            $data['data-detail'] = [
                4 => [
                    'id' => 4,
                    'attributes' => [
                        'template_uuid' => 4,
                        'uraian' => 'Pendapatan',
                        'anggaran' => 0,
                        'parent_uuid' => null,                        
                    ],
                    'children' => [],                    
                ],
                5 => [
                    'id' => 5,
                    'attributes' => [
                        'template_uuid' => 4,
                        'uraian' => 'Belanja',
                        'anggaran' => 0,
                        'parent_uuid' => null,                        
                    ],
                    'children' => [],                    
                ],
                6 => [
                    'id' => 6,
                    'attributes' => [
                        'template_uuid' => 6,
                        'uraian' => 'Pembiayaan',
                        'anggaran' => 0,
                        'parent_uuid' => null,                        
                    ],
                    'children' => [],                    
                ],
            ];
            try {
                $filters = [
                    'filter[kode_kecamatan]' => $this->kodeKecamatan,
                    'filter[template_uuid_length_lt]' => 6,
                    'page[size]' => 100,
                ];
                if ($did != 'Semua') {
                    $filters['filter[kode_desa]'] = $did;
                }
                if ($year != 'Semua') {
                    $filters['filter[tahun]'] = $year;
                }
                $response = $this->apiRequest('/api/v1/keuangan/summary', $filters);
                $data['grafik'] = collect($response)->filter(static fn ($q) => strlen(trim($q['attributes']['template_uuid'])) == 1)->map(function ($item) {
                    return [
                        'anggaran' => $item['attributes']['template_uuid'].'-'.$item['attributes']['uraian'],
                        'jumlah' => intval($item['attributes']['anggaran']),
                    ];
                })->values();
                if($response){
                    $this->dataTree = collect($response);
                    $data['data-detail'] = $this->buildTree();
                }                                
            } catch (\Exception $e) {
                \Log::error('Failed get data in '.__FILE__.' function chart()'. $e->getMessage());
            }
            return $data;
        }
        return $this->localChart($mid, $did, $year);
    }

    private function localChart($mid, $did, $year)
    {
        // Grafik Data Anggaran
        $dataAnggaran = [];
        $type = CoaType::all();

        if ($mid == 'Semua' && $year == 'Semua') {
            $tmp = [];
            foreach ($type as $val) {
                $queryAnggaran = DB::table('das_anggaran_desa')->select('*')
                    ->where('no_akun', 'LIKE', $val->id.'%');
                if ($did != 'Semua') {
                    $queryAnggaran->where('desa_id', $did);
                }
                $tmp[] = [
                    'anggaran' => $val->id.' - '.$val->type_name,
                    'jumlah' => $queryAnggaran->sum('jumlah'),
                ];
            }

            $dataAnggaran['grafik'] = $tmp;
        } elseif ($mid != 'Semua' && $year == 'Semua') {
            $tmp = [];
            foreach ($type as $val) {
                $queryAnggaran = DB::table('das_anggaran_desa')->select('*')
                    ->where('no_akun', 'LIKE', $val->id.'%')
                    ->where('bulan', $mid);
                if ($did != 'Semua') {
                    $queryAnggaran->where('desa_id', $did);
                }
                $tmp[] = [
                    'anggaran' => $val->id.' - '.$val->type_name,
                    'jumlah' => $queryAnggaran->sum('jumlah'),
                ];
            }

            $dataAnggaran['grafik'] = $tmp;
        } elseif ($mid == 'Semua' && $year != 'Semua') {
            $tmp = [];
            foreach ($type as $val) {
                $queryAnggaran = DB::table('das_anggaran_desa')->select('*')
                    ->where('no_akun', 'LIKE', $val->id.'%')
                    ->where('tahun', $year);
                if ($did != 'Semua') {
                    $queryAnggaran->where('desa_id', $did);
                }
                $tmp[] = [
                    'anggaran' => $val->id.' - '.$val->type_name,
                    'jumlah' => $queryAnggaran->sum('jumlah'),
                ];
            }
            $dataAnggaran['grafik'] = $tmp;
        } elseif ($mid != 'Semua' && $year != 'Semua') {
            $tmp = [];
            foreach ($type as $val) {
                $queryAnggaran = DB::table('das_anggaran_desa')->select('*')
                    ->where('no_akun', 'LIKE', $val->id.'%')
                    ->where('bulan', $mid)
                    ->where('tahun', $year);
                if ($did != 'Semua') {
                    $queryAnggaran->where('desa_id', $did);
                }
                $tmp[] = [
                    'anggaran' => $val->id.' - '.$val->type_name,
                    'jumlah' => $queryAnggaran->sum('jumlah'),
                ];
            }
            $dataAnggaran['grafik'] = $tmp;
        }

        return $dataAnggaran;
    }

    protected function buildTree($parentId = null)
    {
        return $this->dataTree->filter(function ($item) use ($parentId) {
            return $item['attributes']['parent_uuid'] === $parentId;
        })->map(function ($item) {
            return [
                'id' => $item['attributes']['template_uuid'],
                'attributes' => $item['attributes'],
                'children' => $this->buildTree($item['attributes']['template_uuid']),
            ];
        })->values()->all();
    }
}
