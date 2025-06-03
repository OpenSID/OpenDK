<?php

namespace App\Http\Controllers\Kerjasama;

use Carbon\Carbon;
use App\Http\Controllers\Controller;

class PendaftaranKerjasamaController extends Controller
{
    public function dokumen_template()
    {
        $date = Carbon::now();

        $data['kecamatan']         = $this->profil['nama_kecamatan'];
        $data['logo']         = $this->profil['file_logo'];
        $data['random']       = substr(str_shuffle('0123456789'), 0, 4);
        $data['hari']         = $date->format('d');
        $data['nama_hari']    = ucwords(hari($date->getTimestamp()));
        $data['nama_tanggal'] = ucwords(to_word($date->format('d')));
        $data['bulan']        = $date->format('m');
        $data['nama_bulan']   = ucwords(getBulan($date->format('m')));
        $data['tahun']        = $date->format('Y');
        $data['nama_tahun']   = ucwords(to_word($date->format('Y')));
        $data['nama_camat']  = strtoupper($this->profil['nama_camat']);
        $data['alamat']       = $this->profil['alamat'];
        $data['stempel']      = asset('img/layanan/stempel.png');
        $data['layanan_logo'] = asset('img/layanan/logo.png');

        Carbon::setLocale('id');
        $data['tanggal'] = $date->translatedFormat('l, d F Y');

        return view('template.dokumen_kerjasama', $data);
    }
}
