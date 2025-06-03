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

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\FrontEndController;
use App\Services\DesaService;
use App\Services\StatistikChartAnggaranDesaService;
class AnggaranDesaController extends FrontEndController
{
    /**
     * Menampilkan Data Anggaran Dan realisasi Kecamatan
     **/
    public function showAnggaranDesa()
    {
        $data['page_title'] = 'Anggaran Desa (APBDes)';
        $data['page_description'] = 'Data Anggaran Desa (APBDes)';
        $data['year_list'] = years_list();
        $data['list_desa'] = (new DesaService())->listDesa();
        $data['hide_list_month'] = $this->isDatabaseGabungan() ? true : false;
        return view('pages.anggaran_desa.show_anggaran_desa')->with($data);
    }

    public function getChartAnggaranDesa()
    {
        $mid = request('mid');
        $did = request('did');
        $year = request('y');

        $dataAnggaran = (new StatistikChartAnggaranDesaService())->chart($mid, $did, $year);
        if($this->isDatabaseGabungan()){
            $dataDetail = collect($dataAnggaran['data-detail'])->keyBy('id');            
            unset($dataAnggaran['data-detail']);
            $dataAnggaran['detail'] = view('pages.anggaran_desa.gabungan.detail_anggaran', compact('did', 'mid', 'year', 'dataDetail'))->render();
        }else {
            $dataAnggaran['detail'] = view('pages.anggaran_desa.detail_anggaran', compact('did', 'mid', 'year'))->render();
        }        
        return $dataAnggaran;
    }
}
