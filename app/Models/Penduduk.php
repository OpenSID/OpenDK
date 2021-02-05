<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penduduk extends Model
{
    public $incrementing = false;
    protected $table     = 'das_penduduk';
    protected $fillable  = [];
    protected $guarded   = [];

    /**
     * Relation Methods
     * */

    public function getPendudukAktif($kid, $did, $year)
    {
        $penduduk =  $this
            ->where('status_dasar', 1)
            ->where('kecamatan_id', $kid)
            ->whereRaw('YEAR(created_at) <= ?', $year);
        if ($did != 'ALL') {
            $penduduk->where('desa_id', $did);
        }
        return $penduduk;
    }

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
