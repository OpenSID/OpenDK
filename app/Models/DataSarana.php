<?php

namespace App\Models;

use App\Enums\KategoriSarana;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class DataSarana extends Model
{
    use HasFactory;

    protected $table = 'das_data_sarana';
    protected $fillable = ['desa_id','kategori','nama','jumlah','keterangan'];
    
    protected $casts = [
        'kategori' => KategoriSarana::class,
    ];

    public function desa()
    {
        return $this->belongsTo(DataDesa::class, 'desa_id', 'desa_id');
    }
    
    /**
     * Get the human readable category name
     *
     * @return string
     */
    public function getKategoriLabelAttribute()
    {
        return KategoriSarana::getDescription($this->kategori);
    }
}
