<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Album extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'judul',
        'gambar',
        'status',
    ];

    protected $casts = [
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

    // public function getThumbnailAttribute()
    // {
    //     return $this->attributes['thumbnail'] ? Storage::disk('public')->url($this->attributes['thumbnail']) : null;
    // }

    public function scopeStatus($query, $value = 1)
    {
        return $query->where('status', $value);
    }

    public function galeris(): HasMany
    {
        return $this->hasMany(Galeri::class);
    }
}
