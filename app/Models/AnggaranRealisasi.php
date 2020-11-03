<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggaranRealisasi extends Model
{
    protected $table = 'das_anggaran_realisasi';

    protected $fillable = [
        'kecamatan_id',
        'bulan',
        'tahun',
        'total_anggaran',
        'total_belanja',
        'belanja_pegawai',
        'belanja_barang_jasa',
        'belanja_modal',
        'belanja_tidak_langsung',
    ];
}
