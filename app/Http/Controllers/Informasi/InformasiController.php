<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright	Hak Cipta 2017 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Informasi;

use App\Http\Controllers\Controller;

use function view;

class InformasiController extends Controller
{
    /**
     * Menampilkan Kumpulan Prosedur
     **/

    public function showProsedur()
    {
        $data['page_title']       = 'Kumpulan Prosedur ';
        $data['page_description'] = 'Kumpulan Prosedur ';

        return view('Informasi.prosedur')->with($data);
    }

    /**
     * Menampilkan Data Potensi
     **/

    public function showPotensi()
    {
        $data['page_title']       = 'Potensi Kecamatan';
        $data['page_description'] = 'Menampilkan Data Potensi Kecamatan';

        return view('informasi.potensi')->with($data);
    }

    /**
     * Menampilkan Data Event
     **/

    public function showEvent()
    {
        $data['page_title']       = 'Event';
        $data['page_description'] = 'Menampilkan Event Terdekat';

        return view('Informasi.event')->with($data);
    }

    /**
     * Menampilkan Data FAQ
     **/

    public function showFAQ()
    {
        $data['page_title'] = 'FAQ';
        //$data['page_description'] = 'Menampilkan Event Terdekat';

        return view('Informasi.faq')->with($data);
    }

    /**
     * Menampilkan Kontak Kecamatan
     **/

    public function showKontak()
    {
        $data['page_title'] = 'Kontak Kecamatan ';
        //$data['page_description'] = 'Menampilkan Event Terdekat';

        return view('informasi.kontak')->with($data);
    }

    /**
     * Menampilkan Kalender Kecamatan
     **/

    public function showKalender()
    {
        $data['page_title'] = 'Kalender Kecamatan ';
        //$data['page_description'] = 'Menampilkan Event Terdekat';

        return view('informasi.kalender')->with($data);
    }
}
