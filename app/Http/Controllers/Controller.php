<?php

namespace App\Http\Controllers;

use App\Models\DataDesa;
use App\Models\Event;
use App\Models\Profil;
use App\Models\SettingAplikasi;
use App\Models\TipePotensi;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use View;

use function config;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * Menampilkan Sebutan Wilayah Tingkat III (Kecamatan/Distrik)
     */

    protected $nama_wilayah;
    protected $sebutan_wilayah;
    protected $sebutan_kepala_wilayah;
    protected $getProfilWilayah;
    
    public function __construct()
    {
        $defaultProfil = config('app.default_profile');

        $getProfilWilayah = Profil::where('kecamatan_id', $defaultProfil)->first();
        $kode_provinsi = $getProfilWilayah->provinsi->kode;
        if (in_array($kode_provinsi, [91, 92])){
            $this->sebutan_wilayah = 'Distrik';
            $this->sebutan_kepala_wilayah = 'Kepala Distrik';
        } else {
            $this->sebutan_wilayah = 'Kecamatan';
            $this->sebutan_kepala_wilayah = 'Camat';
        }

        $nama_wilayah                = $getProfilWilayah->kecamatan->nama;
        $nama_wilayah_kab            = $getProfilWilayah->kabupaten->nama;
        $events                      = Event::getOpenEvents();
        $navdesa                     = DataDesa::orderby('nama', 'ASC')->get();
        $navpotensi                  = TipePotensi::orderby('nama_kategori', 'ASC')->get();
        $this->default_browser_title = "Kecamatan $nama_wilayah, $nama_wilayah_kab";
        $browser_title               = SettingAplikasi::query()
                                        ->where('key', 'browser_title')
                                        ->first()
                                        ->value ?? $this->default_browser_title;

        View::share([
            'nama_wilayah'           => $this->nama_wilayah,
            'sebutan_wilayah'        => $this->sebutan_wilayah,
            'sebutan_kepala_wilayah' => $this->sebutan_kepala_wilayah,
            'events'                 => $events,
            'navdesa'                => $navdesa,
            'navpotensi'             => $navpotensi,
            'nama_wilayah'           => $nama_wilayah,
            'nama_wilayah_kab'       => $nama_wilayah_kab,
            'profil_wilayah'         => $getProfilWilayah,
            'browser_title'          => $browser_title
        ]);
    }
}
