<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPenduduk extends Model
{
    protected $table     = 'das_laporan_penduduk';

    protected $fillable = [
        'judul',
        'bulan',
        'tahun',
        'nama_file',
        'desa_id',
        'imported_at'
    ];

    protected $guarded   = [];

    /**
     * Relation Methods
     * */
}
