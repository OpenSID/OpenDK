<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apbdes extends Model
{
    public $incrementing = false;
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

    public function desa()
    {
        return $this->hasOne(Wilayah::class, 'kode', 'desa_id');
    }

}