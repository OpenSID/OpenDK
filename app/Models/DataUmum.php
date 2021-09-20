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
        'sumber_luas_wilayah',
        'luas_wilayah',
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
        'embed_peta',
    ];

    protected $appends = ['luas_wilayah_dari_data_desa'];

    public function kecamatan()
    {
        return $this->belongsTo(Wilayah::class, 'kecamatan_id', 'kode');
    }

    public function getLuasWilayahValueAttribute()
    {
        return $this->sumber_luas_wilayah==1 ? $this->luas_wilayah : \DB::table('das_data_desa')->sum('luas_wilayah');
    }

    public function getLuasWilayahDariDataDesaAttribute()
    {
        return \DB::table('das_data_desa')->sum('luas_wilayah');
    }
}
