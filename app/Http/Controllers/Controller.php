<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Facades\Counter;
use View;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

	/**
     * Menampilkan Sebutan Wilayah Tingkat III (Kecamatan/Distrik)
     */
	public function __construct()
    {
        $defaultProfil = config('app.default_profile');

        $getProfilWilayah = \App\Models\Profil::where('kecamatan_id', $defaultProfil)->first();
    
        if($getProfilWilayah->provinsi_id == 91 or $getProfilWilayah->provinsi_id == 92){
            $sebutan_wilayah = 'Distrik';
            $sebutan_kepala_wilayah = 'Distrik';
        }else{
            $sebutan_wilayah = 'Kecamatan';
            $sebutan_kepala_wilayah = 'Camat';
        }
        $nama_wilayah = $getProfilWilayah->kecamatan->nama;
        $events     = \App\Models\Event::getOpenEvents();
        $navdesa     = \App\Models\DataDesa::orderby('nama','ASC')->get();
        $navpotensi     = \App\Models\TipePotensi::orderby('nama_kategori','ASC')->get();

        View::share(['sebutan_wilayah'=> $sebutan_wilayah, 
        'sebutan_kepala_wilayah'=> $sebutan_kepala_wilayah, 
        'events'=> $events,
        'navdesa'=> $navdesa,
        'navpotensi'=> $navpotensi,
        'nama_wilayah'=> $nama_wilayah,
        'profil_wilayah'=> $getProfilWilayah
                      ]);
    }
}
    