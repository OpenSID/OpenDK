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

namespace App\Observers;

use App\Events\ArtikelChanged;
use App\Models\Artikel;

class ArtikelObserver
{
    /**
     * Handle the Artikel "created" event.
     *
     * @param  Artikel  $artikel
     * @return void
     */
    public function created(Artikel $artikel)
    {
        ArtikelChanged::dispatch($artikel);
    }

    /**
     * Handle the Artikel "updated" event.
     *
     * @param  Artikel  $artikel
     * @return void
     */
    public function updated(Artikel $artikel)
    {
        ArtikelChanged::dispatch($artikel);
    }

    /**
     * Handle the Artikel "deleted" event.
     *
     * @param  Artikel  $artikel
     * @return void
     */
    public function deleted(Artikel $artikel)
    {
        ArtikelChanged::dispatch($artikel);
    }

    /**
     * Handle the Artikel "restored" event.
     *
     * @param  Artikel  $artikel
     * @return void
     */
    public function restored(Artikel $artikel)
    {
        ArtikelChanged::dispatch($artikel);
    }

    /**
     * Handle the Artikel "force deleted" event.
     *
     * @param  Artikel  $artikel
     * @return void
     */
    public function forceDeleted(Artikel $artikel)
    {
        ArtikelChanged::dispatch($artikel);
    }
}