<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PesanDetail extends Model
{
    protected $table     = 'das_pesan_detail';

    public function headerPesan()
    {
        return $this->hasOne(Pesan::class, 'id', 'pesan_id');
    }

    public function dataDesa()
    {
        return $this->hasOne(DataDesa::class, "id", "desa_id");
    }

    public function getCustomDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('d-m-Y H:i');

    }
}
