<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penduduk extends Model
{
    public $incrementing = false;
    public $timestamps   = true;
    protected $table     = 'das_penduduk';
    protected $fillable  = [];
    protected $guarded   = [];

    /**
     * Relation Methods
     * */

    public function pekerjaan()
    {
        return $this->hasOne(Pekerjaan::class, 'id', 'pekerjaan_id');
    }

    public function kawin()
    {
        return $this->hasOne(Kawin::class, 'id', 'status_kawin');
    }

    public function pendidikan_kk()
    {
        return $this->hasOne(PendidikanKK::class, 'id', 'pendidikan_kk_id');
    }

    public function keluarga()
    {
        return $this->hasOne(Keluarga::class, 'no_kk', 'no_kk');
    }
}
