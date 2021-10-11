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
use Illuminate\Pagination\LengthAwarePaginator;

class PesanController extends Controller
{
    public const PESAN_MASUK = "Pesan Masuk";
    public const PESAN_KELUAR = "Pesan Keluar";
    public const BELUM_DIBACA = 0;
    public const MASUK_ARSIP = 1;
    public const PER_PAGE = 10;

    public function index()
    {
        $data = collect([]);
        $data->put('page_title', 'Pesan');
        $data->put('page_description', 'Managemen Pesan');
        $data = $data->merge($this->loadCounter());
        $pesan = Pesan::with(['dataDesa', 'detailPesan'])
            ->where('jenis', self::PESAN_MASUK)
            ->paginate(self::PER_PAGE);
        $data = $data->merge($this->getPaginationAttribute($pesan));
        $data->put('list_pesan', $pesan);
        return view('pesan.masuk.index', $data->all());
    }

    public function getPaginationAttribute(LengthAwarePaginator $pesan): array
    {
        $first_data = (($pesan->currentPage() * $pesan->perPage()) - $pesan->perPage()) + 1;
        $last_data = $pesan->perPage() * $pesan->currentPage();
        $last_data = $pesan->total() < $last_data ? $pesan->total() : $last_data;
        return [
            'first_data' => $first_data,
            'last_data'=> $last_data
        ];

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
        $data = collect([]);
        $data->put('page_title', 'Pesan Keluar');
        $data->put('page_description', 'Managemen Pesan');
        $data = $data->merge($this->loadCounter());
        $pesan = Pesan::with(['dataDesa', 'detailPesan'])
            ->where('jenis', self::PESAN_KELUAR)
            ->paginate(self::PER_PAGE);
        $data = $data->merge($this->getPaginationAttribute($pesan));
        $data->put('list_pesan', $pesan);
        return view('pesan.keluar.index', $data->all());
    }

    public function loadPesanArsip()
    {
        $data = collect([]);
        $data->put('page_title', 'Pesan Arsip');
        $data->put('page_description', 'Managemen Pesan');
        $data = $data->merge($this->loadCounter());
        $pesan = Pesan::with(['dataDesa', 'detailPesan'])
            ->where('diarsipkan', self::MASUK_ARSIP)
            ->paginate(self::PER_PAGE);
        $data = $data->merge($this->getPaginationAttribute($pesan));
        $data->put('list_pesan', $pesan);
        return view('pesan.arsip.index', $data->all());
    }

    public function readPesan($id_pesan)
    {
        $pesan  = Pesan::with(['dataDesa', 'detailPesan'])->findOrFail($id_pesan);
        $data = collect([]);
        $data->put('page_title', 'Pesan');
        $data->put('page_description', 'Managemen Pesan');
        $data->put('pesan', $pesan);
        $data = $data->merge($this->loadCounter());
        return view('pesan.read_pesan', $data->all());

    }
}
