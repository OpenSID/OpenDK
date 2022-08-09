<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers;

use App\Models\DataDesa;
use App\Models\Event;
use App\Models\MediaSosial;
use App\Models\Profil;
use App\Models\SettingAplikasi;
use App\Models\TipePotensi;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * Menampilkan Sebutan Wilayah Tingkat III (Kecamatan/Distrik)
     */

    protected $profil;
    protected $sebutan_wilayah;
    protected $sebutan_kepala_wilayah;
    protected $browser_title;

    public function __construct()
    {
        $this->profil = Profil::first();

        if (in_array($this->profil->provinsi_id, [91, 92])) {
            $this->sebutan_wilayah = 'Distrik';
            $this->sebutan_kepala_wilayah = 'Kepala Distrik';
        } else {
            $this->sebutan_wilayah = 'Kecamatan';
            $this->sebutan_kepala_wilayah = 'Camat';
        }

        // TODO : Gunakan untuk semua pengaturan jika sudah tersedia
        $this->browser_title = SettingAplikasi::where('key', 'judul_aplikasi')->first()->value ?? ucwords($this->sebutan_wilayah . ' ' . $this->profil->nama_kecamatan);

        $events                      = Event::getOpenEvents();
        $medsos                      = MediaSosial::where('status', 1)->get();
        $medsos = $medsos->map(function ($medsos) {
            $list_domain = [
                'facebook.com',
                'instagram.com',
                't.me',
                'telegram.me',
                'twitter.com',
                'whatsapp.com',
                'youtube.com',
            ];

            foreach ($list_domain as $key) {
                if (strpos($medsos->link, $key)) {
                    // tambahkan https di awal link
                    $medsos->link = preg_replace('/^http:/i', 'https:', prep_url($medsos->link));
                }
            }

            switch (true) {
                case $medsos->id == '1' && $medsos->tipe == '1':
                    $medsos->link = str_replace('https://web.facebook.com/', '', $medsos->link);
                    $medsos->link = 'https://web.facebook.com/' . $medsos->link;
                    break;

                case $medsos->id == '1' && $medsos->tipe == '2':
                    $medsos->link = str_replace('https://web.facebook.com/', '', $medsos->link);
                    $medsos->link = 'https://web.facebook.com/groups/' . $medsos->link;
                    break;

                case $medsos->id == '2':
                    $medsos->link = str_replace('https://twitter.com/', '', $medsos->link);
                    $medsos->link = 'https://twitter.com/' . $medsos->link;
                    break;

                case $medsos->id == '3':
                    $medsos->link = str_replace('https://www.youtube.com/channel/', '', $medsos->link);
                    $medsos->link = 'https://www.youtube.com/channel/' . $medsos->link;
                    break;

                case $medsos->id == '4':
                    $medsos->link = str_replace('https://www.instagram.com/', '', $medsos->link);
                    $medsos->link = 'https://www.instagram.com/' . $medsos->link . '/';
                    break;

                case $medsos->id == '5' && $medsos->tipe == '1':
                    $medsos->link = str_replace('https://api.whatsapp.com/send?phone=', '', $medsos->link);
                    $medsos->link = 'https://api.whatsapp.com/send?phone=' . $medsos->link;
                    $medsos->link = str_replace('phone=0', 'phone=+62', $medsos->link);
                    break;

                case $medsos->id == '5' && $medsos->tipe == '2':
                    $medsos->link = str_replace('https://chat.whatsapp.com/', '', $medsos->link);
                    $medsos->link = 'https://chat.whatsapp.com/' . $medsos->link;
                    break;

                case $medsos->id == '6' && $medsos->tipe == '1':
                    $medsos->link = str_replace('https://t.me/', '', $medsos->link);
                    $medsos->link = 'https://t.me/' . $medsos->link;
                    break;

                case $medsos->id == '6' && $medsos->tipe == '2':
                    $medsos->link = str_replace('https://t.me/joinchat/', '', $medsos->link);
                    $medsos->link = 'https://t.me/joinchat/' . $medsos->link;
                    break;

                default:
                }
            return $medsos;
        });
        $navdesa                     = DataDesa::all();
        $navpotensi                  = TipePotensi::orderby('nama_kategori', 'ASC')->get();

        View::share([
            'profil'                 => $this->profil,
            'sebutan_wilayah'        => $this->sebutan_wilayah,
            'sebutan_kepala_wilayah' => $this->sebutan_kepala_wilayah,
            'browser_title'          => $this->browser_title,
            'events'                 => $events,
            'medsos'                 => $medsos,
            'navdesa'                => $navdesa,
            'navpotensi'             => $navpotensi,
        ]);
    }
}
