<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    protected $table     = 'das_pesan';

    public function detailPesan()
    {
        $this->hasMany(PesanDetail::class, 'id', 'pesan_id');
    }

    public function dataDesa()
    {
        $this->hasOne(DataDesa::class, "id", "das_data_desa_id");
    }
}
