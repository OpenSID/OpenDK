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

namespace App\Http\Controllers;

use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\View;

class FrontEndController extends Controller
{
    protected $settings;
    protected $urlApi = 'api/frontend/v1';
    public function __construct()
    {
        parent::__construct();
        theme_active();

        $this->settings = SettingAplikasi::pluck('value', 'key');
        View::share([
            'urlApi' => url($this->urlApi),
            'events' => null, // Placeholder for events
            'medsos' => null, // Placeholder for media sosial
            'media_terkait' => null, // Placeholder for media terkait
            'navigations' => null, // Placeholder for navigations
            'navmenus' => null, // Placeholder for navmenus
            'pengurus' => null, // Placeholder for pengurus
            'sinergi' => null, // Placeholder for sinergi
            'slides' => null, // Placeholder for slides
        ]);
    }

    protected function isDatabaseGabungan()
    {
        return ($this->settings['sinkronisasi_database_gabungan'] ?? null) === '1';
    }
}
