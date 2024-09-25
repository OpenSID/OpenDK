<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Galeri extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'album_id',
        'judul',
        'gambar',
        'link',
        'jenis',
        'status',
    ];

    protected $casts = [
        'gambar' => 'array',
        'status' => 'boolean',
    ];

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'judul',
            ],
        ];
    }

    // public function getGambarAttribute()
    // {
    //     return $this->attributes['gambar'] ? Storage::url('publikasi/galeri/' . $this->attributes['gambar']) : null;
    // }

    public function scopeStatus($query, $value = 1)
    {
        return $query->where('status', $value);
    }

    public function Album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }
}
