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

namespace App\Repositories;

use App\Models\DataDesa;
use App\Services\DesaService;
use App\Services\StatistikChartPendudukAgamaService;
use App\Services\StatistikChartPendudukGolDarahService;
use App\Services\StatistikChartPendudukPendidikanService;
use App\Services\StatistikChartPendudukPerkawinanService;
use App\Services\StatistikChartPendudukService;
use App\Services\StatistikChartPendudukUsiaService;
use App\Services\StatistikPendudukService;

class StatistikPendudukApiRepository extends BaseApiRepository
{
    /**
     * Constructor
     */
    public function __construct(DataDesa $model)
    {
        parent::__construct($model);                        
    }
    
    public function data($did, $year)
    {
        $listYears = $this->yearsList($year)['tahun'] ?? [date('Y')];
        return [            
            [
                'id' => 1,                
                'dashboard' => (new StatistikPendudukService())->dashboard($did, $year),
                'chart' => [
                    'penduduk' => (new StatistikChartPendudukService())->chart($did, $listYears),
                    'penduduk-usia' => (new StatistikChartPendudukUsiaService())->chart($did, $year),
                    'penduduk-pendidikan' => (new StatistikChartPendudukPendidikanService())->chart($did, $year),
                    'penduduk-golongan-darah' => (new StatistikChartPendudukGolDarahService())->chart($did, $year),
                    'penduduk-kawin' => (new StatistikChartPendudukPerkawinanService())->chart($did, $year),
                    'penduduk-agama' => (new StatistikChartPendudukAgamaService())->chart($did, $year)
                ]
            ]            
        ];
    }

    public function yearsList($max_tahun = null)
    {
        $min_tahun = (new StatistikPendudukService())->minYear() ?? date('Y');

        $daftar_tahun = [];
        for ($y = $min_tahun; $y <= ($max_tahun ?? date('Y')); $y++) {
            $daftar_tahun[] = $y;
        }

        return ['id' => 'tahun', 'tahun' => $daftar_tahun];
    }        
}