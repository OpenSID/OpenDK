<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LembagaAnggota extends Model
{
    use HasFactory;
    protected $table = 'das_lembaga_anggota';

    protected $guarded = ['id'];

    // Relasi ke model Lembaga (Many-to-One)
    public function lembaga()
    {
        return $this->belongsTo(Lembaga::class, 'lembaga_id');
    }

    // Relasi ke model Penduduk (Many-to-One)
    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_id');
    }

    // Menentukan kolom slug yang digunakan untuk pencarian di rute
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
