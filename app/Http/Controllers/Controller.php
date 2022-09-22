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

use Exception;
use App\Models\Event;
use App\Models\Profil;
use App\Models\DataDesa;
use App\Models\DataUmum;
use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\MediaSosial;
use App\Models\SinergiProgram;
use App\Models\TipePotensi;
use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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


        $this->kirimTrack();

        // TODO : Gunakan untuk semua pengaturan jika sudah tersedia
        $this->browser_title = SettingAplikasi::where('key', 'judul_aplikasi')->first()->value ?? ucwords($this->sebutan_wilayah . ' ' . $this->profil->nama_kecamatan);

        $events                      = Event::getOpenEvents();
        $sinergi                     = SinergiProgram::where('status', 1)->orderBy('urutan', 'asc')->get();
        $medsos                      = MediaSosial::where('status', 1)->get();
        $navdesa                     = DataDesa::all();
        $navpotensi                  = TipePotensi::orderby('nama_kategori', 'ASC')->get();

        View::share([
            'profil'                 => $this->profil,
            'sebutan_wilayah'        => $this->sebutan_wilayah,
            'sebutan_kepala_wilayah' => $this->sebutan_kepala_wilayah,
            'browser_title'          => $this->browser_title,
            'events'                 => $events,
            'sinergi'                => $sinergi,
            'medsos'                 => $medsos,
            'navdesa'                => $navdesa,
            'navpotensi'             => $navpotensi,
        ]);
    }

    protected function kirimTrack()
    {
        if (config('app.demo') == true) { // jika posisi demo, matikan tracking
            return;
        }

        if (session('track') != null && session('track') == date('Y m d')) {
            return;
        }

        $host_pantau = config('app.host_pantau');
        $data = [
            'url' => url('/'),
            'versi' => config('app.version'),
            'jml_desa' => DataDesa::count(),
            'jumlah_penduduk' => Penduduk::where('status_dasar', 1)->count(),
            'jumlah_keluarga' => Keluarga::count(),
            'peta_wilayah'  => DataUmum::first()->path ?? '[[[[]]]]',
            'sebutan_wilayah' => $this->sebutan_wilayah,
            'kode_kecamatan' => $this->profil->kecamatan_id,
            'kode_kabupaten' => $this->profil->kabupaten_id,
            'kode_provinsi' => $this->profil->provinsi_id,
            'nama_kecamatan' => $this->profil->nama_kecamatan,
            'nama_kabupaten' => $this->profil->nama_kabupaten,
            'nama_provinsi' => $this->profil->nama_provinsi,
        ];

        try {
            Http::withHeaders([
                'token' => config('app.token_pantau')
            ])->post($host_pantau.'track/opendk?token='.config('app.token_pantau'), $data);
            session(['track' => date('Y m d')]);
            return;
        } catch (Exception $e) {
            Log::notice($e);
            return;
        }

    }
}
