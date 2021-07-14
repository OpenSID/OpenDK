<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanApbdes extends Model
{
    protected $table     = 'das_apbdes';
    
    protected $fillable = [
        'judul',
        'tahun',
        'semester',
        'nama_file',
        'desa_id',
        'imported_at'
    ];

    protected $guarded   = [];

    /**
     * Relation Methods
     * */

}