<?php

namespace App\Models;

use App\Enums\TipeMedia;
use App\Observers\GaleriObserver;
use App\Services\MediaLinkService;
use App\ValueObjects\MediaLink;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Galeri extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'album_id',
        'judul',
        'gambar',
        'link',
        'jenis',
        'media_type',
        'status',
    ];

    protected $casts = [
        'gambar' => 'array',
        'status' => 'boolean',
    ];

    protected $appends = ['gambar_path', 'media_type', 'media_url', 'media_embed_url'];

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
        // Media lokal selalu berupa foto, jadi cukup pakai berkas pertama.
        if (($this->attributes['jenis'] ?? null) === 'file' && ! empty($this->gambar)) {
            $gambar = is_array($this->gambar) ? ($this->gambar[0] ?? null) : $this->gambar;
            if ($gambar) {
                return isThumbnail('publikasi/galeri/' . $gambar);
            }
        }

        return $this->media()->thumbnail ?? asset('/img/no-image.png');
    }

    /**
     * Tipe media galeri: image, video, youtube, atau unknown.
     *
     * Untuk media berbasis link, kolom media_type hasil deteksi saat penyimpanan
     * lebih dipercaya daripada menebak ulang dari URL, karena link Google Drive
     * tidak memiliki ekstensi berkas sehingga jenis media tidak bisa dibaca
     * langsung dari URL.
     */
    public function getMediaTypeAttribute(): string
    {
        if (($this->attributes['jenis'] ?? null) === 'file') {
            return empty($this->gambar) ? TipeMedia::Unknown : TipeMedia::Image;
        }

        $stored = $this->attributes['media_type'] ?? null;

        if (is_string($stored) && in_array($stored, [TipeMedia::Image, TipeMedia::Video, TipeMedia::Youtube], true)) {
            return $stored;
        }

        return $this->media()->type;
    }

    /**
     * URL media yang bisa dimuat langsung oleh browser (src).
     */
    public function getMediaUrlAttribute(): ?string
    {
        if (($this->attributes['jenis'] ?? null) === 'file' && ! empty($this->gambar)) {
            $gambar = is_array($this->gambar) ? ($this->gambar[0] ?? null) : $this->gambar;

            return $gambar ? isThumbnail('publikasi/galeri/' . $gambar) : null;
        }

        return $this->media()->url;
    }

    /**
     * URL untuk <iframe>, dipakai bila <video> tidak mendukung media tersebut.
     */
    public function getMediaEmbedUrlAttribute(): ?string
    {
        return $this->media()->embedUrl;
    }

    /**
     * Register model lifecycle hooks.
     */
    protected static function booted(): void
    {
        static::observe(GaleriObserver::class);
    }

    /**
     * Resolusi link galeri menjadi objek media siap tampil.
     */
    protected function media(): MediaLink
    {
        return app(MediaLinkService::class)->resolve(
            $this->attributes['link'] ?? null,
            $this->attributes['media_type'] ?? null
        );
    }
}
