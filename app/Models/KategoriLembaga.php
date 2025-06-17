<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriLembaga extends Model
{
    use HasFactory;
    protected $table = 'das_lembaga_kategori';

    protected $guarded = ['id'];

    // Relasi ke model Lembaga (One-to-Many)
    public function lembaga()
    {
        return $this->hasMany(Lembaga::class, 'lembaga_kategori_id');
    }
}
