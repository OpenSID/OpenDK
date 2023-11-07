<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers;

use App\Enums\JenisJabatan;
use App\Models\DataDesa;
use App\Models\DataUmum;
use App\Models\Event;
use App\Models\Jabatan;
use App\Models\Keluarga;
use App\Models\MediaSosial;
use App\Models\Penduduk;
use App\Models\Pengurus;
use App\Models\Profil;
use App\Models\Program;
use App\Models\SettingAplikasi;
use App\Models\SinergiProgram;
use App\Models\Slide;
use App\Models\TipePotensi;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * Menampilkan Sebutan Wilayah Tingkat III (Kecamatan/Distrik)
     */
    protected $akun_camat;
    protected $akun_sekretaris;
    protected $browser_title;
    protected $nama_camat;
    protected $profil;
    protected $sebutan_kepala_wilayah;
    protected $sebutan_tambahan;
    protected $sebutan_wilayah;
    protected $settings;
    protected $umum;

    public function __construct()
    {
        if (sudahInstal()) {
            $this->profil     = Profil::first();
            $this->umum       = DataUmum::first();
            $this->nama_camat = Pengurus::status()->camat()->first();

            // Pemeriksaan akun pengurus untuk alur pemeriksaan surat
            $this->akun_camat      = Pengurus::status()->akunCamat()->first();
            $this->akun_sekretaris = Pengurus::status()->akunSekretaris()->first();

            if (!$this->akun_camat) {
                SettingAplikasi::where('key', 'tte')->update(['value' => 0]);
                SettingAplikasi::where('key', 'pemeriksaan_camat')->update(['value' => 0]);
            }

            if (!$this->akun_sekretaris) {
                SettingAplikasi::where('key', 'pemeriksaan_sekretaris')->update(['value' => 0]);
            }

            // Tambahan global variabel di luar setting aplikasi
            $this->sebutan_tambahan = [
                'sebutan_camat'      => Jabatan::where('jenis', JenisJabatan::Camat)->first()->nama,
                'sebutan_sekretaris' => Jabatan::where('jenis', JenisJabatan::Sekretaris)->first()->nama,
            ];

            // Global variabel setting aplikasi
            $this->settings = SettingAplikasi::pluck('value', 'key');
            $this->settings = $this->settings->merge($this->sebutan_tambahan);
            View::share('settings', $this->settings);

            if (in_array($this->profil->provinsi_id, [91, 92])) {
                $this->sebutan_wilayah = 'Distrik';
                $this->sebutan_kepala_wilayah = 'Kepala Distrik';
            } else {
                $this->sebutan_wilayah = 'Kecamatan';
                $this->sebutan_kepala_wilayah = 'Camat';
            }

            if ($this->settings['tte']) {
                SettingAplikasi::where('key', 'pemeriksaan_camat')->update(['value' => 1]);
            }

            $this->kirimTrack();

            // TODO : Gunakan untuk semua pengaturan jika sudah tersedia
            $this->browser_title = SettingAplikasi::where('key', 'judul_aplikasi')->first()->value ?? ucwords($this->sebutan_wilayah . ' ' . $this->profil->nama_kecamatan);

            $events     = Event::getOpenEvents();
            $sinergi    = SinergiProgram::where('status', 1)->orderBy('urutan', 'asc')->get();
            $medsos     = MediaSosial::where('status', 1)->get();
            $navdesa    = DataDesa::all();
            $navpotensi = TipePotensi::orderby('nama_kategori', 'ASC')->get();
            $pengurus   = Pengurus::status()->get();
            $slides     = Slide::orderBy('created_at', 'DESC')->get();

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
                'camat'                  => $this->nama_camat,
                'pengurus'               => $pengurus->sortBy('jabatan.jenis'),
                'slides'                 => $slides,
            ]);
        }
    }

    protected function kirimTrack()
    {
        if (config('app.demo') == true) { // jika posisi demo, matikan tracking
            return;
        }

        if (cache()->get('track') != null && cache()->get('track') == date('Y m d')) {
            return;
        }

        $host_pantau = config('app.host_pantau');
        $data = [
            'url' => url('/'),
            'versi' => config('app.version'),
            'jml_desa' => DataDesa::count(),
            'desa' => json_encode(DataDesa::select(['desa_id', 'nama', 'sebutan_desa', 'path', 'website'])->get()),
            'jumlahdesa_sinkronisasi' => DataDesa::count(),
            'jumlah_penduduk' => Penduduk::where('status_dasar', 1)->count(),
            'jumlah_keluarga' => Keluarga::count(),
            'peta_wilayah'  => $this->umum->path ?? '[[[[]]]]',
            'batas_wilayah' => json_encode([
                'bts_wil_utara' => $this->umum->bts_wil_utara,
                'bts_wil_timur' => $this->umum->bts_wil_timur,
                'bts_wil_selatan' => $this->umum->bts_wil_selatan,
                'bts_wil_barat' => $this->umum->bts_wil_barat
            ]),
            'sebutan_wilayah' => $this->sebutan_wilayah,
            'alamat' => $this->profil->alamat,
            'jumlah_bantuan' => Program::count(),
            'kode_kecamatan' => $this->profil->kecamatan_id,
            'kode_kabupaten' => $this->profil->kabupaten_id,
            'kode_provinsi' => $this->profil->provinsi_id,
            'nama_kecamatan' => $this->profil->nama_kecamatan,
            'nama_kabupaten' => $this->profil->nama_kabupaten,
            'nama_provinsi' => $this->profil->nama_provinsi,
            'nama_camat' => $this->nama_camat
        ];

        try {
            $response = Http::withHeaders([
                'token' => config('app.token_pantau')
            ])->post($host_pantau . 'track/opendk?token=' . config('app.token_pantau'), $data);
            cache()->put('track', date('Y m d'), 60 * 60 * 24);
            return;
        } catch (Exception $e) {
            Log::notice($e);
            return;
        }
    }
}
