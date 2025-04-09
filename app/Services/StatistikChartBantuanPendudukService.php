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
use App\Models\Program;
use Illuminate\Support\Facades\DB;

class StatistikChartBantuanPendudukService extends BaseApiService
{
    protected $sasaran = 1;
    protected $kategori = 'penduduk';
    public function chart($did, $year)
    {
        if ($this->useDatabaseGabungan()) {
            $data = [];
            try {
                $filters = [                 
                    'filter[id]' => $this->kategori, 
                    'kode_kecamatan'  => $this->kodeKecamatan,  
                ];
                if ($did != 'Semua') {
                    $filters['kode_desa'] = $did;
                }
                if($year != 'Semua') {
                    $filters['filter[tahun]'] = $year;
                }
                $response = $this->apiRequest('/api/v1/statistik-web/bantuan', $filters);   
                          
                $data = collect($response)
                ->filter(function ($item) {
                    return !in_array($item['id'], [LabelStatistik::Total, LabelStatistik::Jumlah, LabelStatistik::BelumMengisi]);
                })->groupBy('attributes.nama')->map(function ($item, $key) {
                    return ['program' => $key, 'value' => $item->sum('attributes.jumlah')];
                })->values();
                
                return $data;
            } catch (\Exception $e) {
                \Log::error('Failed get data in '.__FILE__.' function chart()'. $e->getMessage());
            }
            return $data;
        }
        return $this->localChart($did, $year);
    }

    protected function localChart($did, $year)
    {
        $data = [];
        $sasaran = $this->sasaran;
        $program = Program::where('sasaran', $sasaran)->get();

        foreach ($program as $prog) {
            $queryResult = DB::table('das_peserta_program')
                ->join('das_penduduk', 'das_peserta_program.kartu_nik', '=', 'das_penduduk.nik')
                ->where('das_peserta_program.sasaran', '=', $sasaran)
                ->where('das_peserta_program.program_id', '=', $prog->id);

            if ($year != 'Semua') {
                $queryResult->whereYear('das_peserta_program.created_at', '=', $year);
            }

            if ($did != 'Semua') {
                $queryResult->where('das_penduduk.desa_id', '=', $did);
            }

            $data[] = ['program' => $prog->nama, 'value' => $queryResult->count()];
        }

        return $data;
    }
}
