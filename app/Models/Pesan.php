<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    protected $table     = 'das_pesan';

    public function detailPesan()
    {
        return $this->hasMany(PesanDetail::class, 'pesan_id', 'id');
    }

    public function dataDesa()
    {
        return $this->hasOne(DataDesa::class, "id", "das_data_desa_id");
    }
}
