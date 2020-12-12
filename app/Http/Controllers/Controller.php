<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    protected $nama_wilayah;
    protected $sebutan_wilayah;
    protected $sebutan_kepala_wilayah;

	/**
     * Menampilkan Sebutan Wilayah Tongkat III (Kecamatan/Distrik)
     */
	public function __construct()
    {
        $getWilayah = \App\Models\Profil::where('kecamatan_id', '=', config('app.default_profile'))->first();
        $this->nama_wilayah = $getWilayah->kecamatan->nama;

        if(substr($getWilayah->provinsi_id ,0,2) == 91 or substr($getWilayah->provinsi_id ,0,2) == 92){
            $this->sebutan_wilayah = 'Kecamatan';
            $this->sebutan_kepala_wilayah = 'Camat';
        }else{
            $this->sebutan_wilayah = 'Distrik';
            $this->sebutan_kepala_wilayah = 'Kepala Distrik';
        }
        \View::share([
                'nama_wilayah'=> $this->nama_wilayah, 
                'sebutan_wilayah'=> $this->sebutan_wilayah, 
                'sebutan_kepala_wilayah'=> $this->sebutan_kepala_wilayah
        ]);
    }
}
