<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class ArtikelKategori extends Model
{
    use HasFactory, Sluggable;

    protected $table = 'das_artikel_kategori';

    protected $primaryKey = 'id_kategori';

    protected $guarded = ['id_kategori'];

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'nama_kategori',
            ],
        ];
    }
}
