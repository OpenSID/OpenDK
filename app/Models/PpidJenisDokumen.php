<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PpidJenisDokumen extends Model
{
    use SoftDeletes;
    protected $table = 'ppid_jenis_dokumen';
    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'kode',
        'icon',
        'urut',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        // Pastikan slug dibuat saat creating
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = \Str::slug($model->nama);
            }
        });

        // PROTEKSI: Mencegah perubahan slug untuk 3 data utama saat update
        static::updating(function ($model) {
            $protectedSlugs = ['secara-berkala', 'serta-merta', 'tersedia-setiap-saat'];

            // Jika slug lama masuk daftar proteksi, kembalikan slug ke nilai originalnya
            if (in_array($model->getOriginal('slug'), $protectedSlugs)) {
                $model->slug = $model->getOriginal('slug');
            } else {
                // Untuk data non-proteksi, slug boleh berubah mengikuti nama
                if ($model->isDirty('nama')) {
                    $model->slug = \Str::slug($model->nama);
                }
            }
        });
    }

    public function scopeAktif($query)
    {
        return $query->where('status', '1');
    }

    public function getStatusLabelAttribute()
    {
        return $this->status == '1'
            ? '<span class="label label-success">Aktif</span>'
            : '<span class="label label-danger">Tidak-Aktif</span>';
    }
}
