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

namespace App\Http\Controllers\Api\Frontend;

use App\Models\AnggaranDesa;
use App\Models\SubCoa;
use App\Models\SubSubCoa;
use App\Services\StatistikChartAnggaranDesaService;

class AnggaranDesaController extends BaseController
{
    public function getChartAnggaranDesa()
    {
        $mid = request('mid');
        $did = request('did');
        $year = request('y');
        theme_active();
        $dataAnggaran = (new StatistikChartAnggaranDesaService())->chart($mid, $did, $year);
        if($this->isDatabaseGabungan()){
            $dataDetail = collect($dataAnggaran['data-detail'])->keyBy('id');            
            unset($dataAnggaran['data-detail']);
            
            $dataAnggaran['detail'] = view('pages.anggaran_desa.gabungan.detail_anggaran', compact('did', 'mid', 'year', 'dataDetail'))->render();
        }else {
            $detailData = $this->buildDetailAnggaran($did, $mid, $year);
            $dataAnggaran['detail'] = view('pages.anggaran_desa.detail_anggaran', compact('detailData'))->render();
        }        
        return $dataAnggaran;
    }

    /**
     * Menyiapkan data detail anggaran untuk view non-gabungan.
     */
    private function buildDetailAnggaran($did, $mid, $year): array
    {
        $sections = [
            4 => ['label' => '4 - PENDAPATAN', 'collapse' => 'collapseOne'],
            5 => ['label' => '5 - BELANJA', 'collapse' => 'collapseTwo'],
            6 => ['label' => '6 - PEMBIAYAAN', 'collapse' => 'collapseThree'],
        ];

        $result = [];

        foreach ($sections as $typeId => $section) {
            $total = $this->sumAnggaran($typeId . '%', $did, $mid, $year);

            $subCoas = SubCoa::where('type_id', $typeId)
                ->orderBy($typeId === 4 ? 'type_id' : 'id')
                ->get();

            $subItems = [];
            foreach ($subCoas as $subCoa) {
                $subTotal = $this->sumAnggaran($typeId . $subCoa->id . '%', $did, $mid, $year);

                $subSubCoas = SubSubCoa::where('type_id', $subCoa->type_id)
                    ->where('sub_id', $subCoa->id)
                    ->orderBy('sub_id')
                    ->get();

                $subSubItems = [];
                foreach ($subSubCoas as $subSubCoa) {
                    $subSubTotal = $this->sumAnggaran($typeId . $subCoa->id . $subSubCoa->id . '%', $did, $mid, $year);

                    $subSubItems[] = [
                        'type_id'      => $typeId,
                        'sub_id'       => $subSubCoa->sub_id,
                        'id'           => $subSubCoa->id,
                        'sub_sub_name' => $subSubCoa->sub_sub_name,
                        'jumlah'       => format_number_id($subSubTotal),
                    ];
                }

                $subItems[] = [
                    'type_id'      => $subCoa->type_id,
                    'id'           => $subCoa->id,
                    'sub_name'     => $subCoa->sub_name,
                    'jumlah'       => format_number_id($subTotal),
                    'sub_sub_coas' => $subSubItems,
                ];
            }

            $result[] = [
                'type_id'  => $typeId,
                'label'    => $section['label'],
                'collapse' => $section['collapse'],
                'total'    => format_number_id($total),
                'sub_coas' => $subItems,
            ];
        }

        return $result;
    }

    /**
     * Hitung total jumlah anggaran berdasarkan pola no_akun dan filter.
     */
    private function sumAnggaran(string $akunPattern, $did, $mid, $year): float
    {
        $query = AnggaranDesa::where('no_akun', 'LIKE', $akunPattern);

        if ($did != 'Semua') {
            $query->where('desa_id', $did);
        }
        if ($mid != 'Semua') {
            $query->where('bulan', $mid);
        }
        if ($year != 'Semua') {
            $query->where('tahun', $year);
        }

        return (float) $query->sum('jumlah');
    }
}
