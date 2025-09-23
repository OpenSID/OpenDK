<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class DataSarana extends Model
{
    use HasFactory;

    protected $table = 'das_data_sarana';
    protected $fillable = ['desa_id','kategori','nama','jumlah','keterangan'];

    public function desa()
    {
        return $this->belongsTo(DataDesa::class, 'desa_id', 'id');
    }
}
