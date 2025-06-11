<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Lembaga extends Model
{
    use HasFactory;

    protected $table = 'das_lembaga';

    protected $guarded = ['id'];

    // Relasi ke model KategoriLembaga (Many-to-One)
    public function lembagaKategori()
    {
        return $this->belongsTo(KategoriLembaga::class, 'lembaga_kategori_id');
    }

    // Relasi ke model LembagaAnggota (One-to-Many)
    public function lembagaAnggota()
    {
        return $this->hasMany(LembagaAnggota::class, 'lembaga_id');
    }

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_id', 'id');
    }

    // Scope untuk membuat slug yang unik
    public static function generateUniqueSlug($nama)
    {
        // Generate slug awal
        $slug = Str::slug($nama);
        $originalSlug = $slug;
        $counter = 1;

        // Cek apakah slug sudah ada di database, jika ya, tambahkan angka di belakangnya
        while (self::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
