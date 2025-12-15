<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Galeri extends BaseModel
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

    protected $appends = ['gambar_path'];

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

    protected function getGambarPathAttribute(){
        $gambar = $this->gambar[0];
        return $this->attributes['jenis'] == 'file' ? isThumbnail("publikasi/galeri/".$gambar) : asset('/img/no-image.png');
    }
}
