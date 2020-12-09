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

    protected $nama_wilayah;
    protected $sebutan_wilayah;
    protected $sebutan_kepala_wilayah;

	/**
     * Menampilkan Sebutan Wilayah Tingkat III (Kecamatan/Distrik)
     */
	public function __construct()
    {
        $defaultProfil = config('app.default_profile');

        $getProfilWilayah = \App\Models\Profil::where('kecamatan_id', $defaultProfil)->first();
        $nama_wilayah = $getProfilWilayah->kecamatan->nama;
        // dd($nama_wilayah);    
        $getWilayah = \App\Models\Wilayah::where('kode', '=', config('app.default_profile'))->first();

        if(substr($getWilayah->kode,0,2) == 91 or substr($getWilayah->kode,0,2) == 92){
            $sebutan_wilayah = 'Kecamatan';
            $sebutan_kepala_wilayah = 'Camat';
        }else{
            $sebutan_wilayah = 'Distrik';
            $sebutan_kepala_wilayah = 'Distrik';
        }
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
    