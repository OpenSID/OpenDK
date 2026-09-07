<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Observers\GaleriObserver;

class Galeri extends Model
{
    use HasFactory, Sluggable;

    /**
     * Register model lifecycle hooks.
     */
    protected static function booted(): void
    {
        static::observe(GaleriObserver::class);
    }

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

    public function getGambarPathAttribute(): string
    {
        if (($this->attributes['jenis'] ?? null) === 'file' && ! empty($this->gambar)) {
            $gambar = is_array($this->gambar) ? ($this->gambar[0] ?? null) : $this->gambar;
            if ($gambar) {
                return isThumbnail('publikasi/galeri/' . $gambar);
            }
        }

        if (($this->attributes['jenis'] ?? null) === 'url' && ! empty($this->link)) {
            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $this->link, $matches)) {
                return 'https://img.youtube.com/vi/' . $matches[1] . '/hqdefault.jpg';
            }
        }

        return asset('/img/no-image.png');
    }
}
