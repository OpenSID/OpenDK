<?php

/*
 * File ini bagian dari:
 *
 * PBB Desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @package	    OpenDK
 * @author	    Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Pesan;

use App\Http\Controllers\Controller;
use App\Models\Pesan;

class PesanController extends Controller
{
    public const PESAN_MASUK = "Pesan Masuk";
    public const PESAN_KELUAR = "Pesan Keluar";
    public const BELUM_DIBACA = 0;
    public const MASUK_ARSIP = 1;
    public const PER_PAGE = 3;

    public function index()
    {
        $data = collect([]);
        $data->put('page_title', 'Pesan');
        $data->put('page_description', 'Managemen Pesan');
        $data = $data->merge($this->loadCounter());
        $pesan = Pesan::with(['dataDesa', 'detailPesan'])
            ->where('jenis', 'Pesan Masuk')
            ->paginate(self::PER_PAGE);
        $first_data = (($pesan->currentPage() * $pesan->perPage()) - $pesan->perPage()) + 1;
        $last_data = $pesan->perPage() * $pesan->currentPage();
        $last_data = $pesan->total() < $last_data ? $pesan->total() : $last_data;
        $data->put('list_pesan', $pesan);
        $data->put('first_data', $first_data);
        $data->put('last_data', $last_data);
        return view('pesan.masuk.index', $data->all());
    }

    protected function loadCounter()
    {
        $counter_unread =  Pesan::where(['jenis' => self::PESAN_MASUK, 'sudah_dibaca' => self::BELUM_DIBACA])->count();
        $counter_pesan_keluar =  Pesan::where(['jenis' => self::PESAN_KELUAR])->count();
        $counter_arsip =  Pesan::where(['diarsipkan' => self::MASUK_ARSIP])->count();

        return [
            'counter_unread' => $counter_unread,
            'counter_pesan_keluar' => $counter_pesan_keluar,
            'counter_arsip' => $counter_arsip
        ];
    }

    public function loadPesanKeluar()
    {
        $page_title = 'Pesan Keluar';
        $page_description =   'Managemen Pesan';
        return view('pesan.keluar.index', compact('page_title', 'page_description'));
    }

    public function loadPesanArsip()
    {
        $page_title = 'Pesan Arsip';
        $page_description =   'Managemen Pesan';
        return view('pesan.arsip.index', compact('page_title', 'page_description'));
    }
}
