<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'slug'
    ];

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'nama',
            ],
        ];
    }

    public function artikels(): HasMany
    {
        return $this->hasMany(Artikel::class, 'kategori_id');
    }

    public function getLinkAttribute(): string
    {
        return  Str::replaceFirst(url('/'), '', route('berita.kategori', ['slug' => $this->slug]));
    }
}
