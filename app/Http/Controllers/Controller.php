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

    protected $sebutan_wilayah;

	/**
     * Menampilkan Sebutan Wilayah Tongkat III (Kecamatan/Distrik)
     */
	public function __construct()
    {
        $getWilayah = \App\Models\Wilayah::where('kode', '=', config('app.default_profile'))->first();

        if(substr($getWilayah->kode,0,2) == 91 or substr($getWilayah->kode,0,2) == 92){
            $this->sebutan_wilayah = 'Distrik';
        }else{
            $this->sebutan_wilayah = 'Kecamatan';
        }
        \View::share('sebutan_wilayah', $this->sebutan_wilayah);
    }
}
