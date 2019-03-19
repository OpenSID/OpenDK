<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataUmum extends Model
{
    // Attributes
    protected $table = 'das_data_umum';

    protected $fillable = [
        'kecamatan_id',
        'tipologi',
        'ketinggian',
        'luas_wilayah',
        'jumlah_penduduk',
        'jml_laki_laki',
        'jml_perempuan',
        'bts_wil_utara',
        'bts_wil_timur',
        'bts_wil_selatan',
        'bts_wil_barat',
        'jml_puskesmas',
        'jml_puskesmas_pembantu',
        'jml_posyandu',
        'jml_pondok_bersalin',
        'jml_paud',
        'jml_sd',
        'jml_smp',
        'jml_sma',
        'jml_masjid_besar',
        'jml_mushola',
        'jml_gereja',
        'jml_pasar',
        'jml_balai_pertemuan',
        'kepadatan_penduduk',
        'embed_peta'
    ];


    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id', 'id');
    }
}