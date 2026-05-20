<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Models;

use App\Traits\HandlesResourceDeletion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Artikel extends Model
{
    use Sluggable;
    use HasFactory;
    use HandlesResourceDeletion;

    protected $table = 'das_artikel';

    protected $fillable = [
        'id_kategori',
        'judul',
        'gambar',
        'kategori_id',
        'isi',
        'status',
        'tanggal_terbit'
    ];

    protected $casts = [
        'tanggal_terbit' => 'date:Y-m-d'
    ];

    /**
     * Daftar field-file yang harus dihapus.
     *
     * @var array
     */
    protected $resources = [
        'gambar',
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

    public function getGambarAttribute(): ?string
    {
        return $this->attributes['gambar'] ? Storage::url('artikel/' . $this->attributes['gambar']) : null;
    }

    public function getIsiAttribute(): string
    {
        return str_replace('//storage', '/storage', $this->attributes['isi']);
    }

    public function scopeStatus(Builder $query, int $value = 1): Builder
    {
        return $query->where('status', $value);
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(ArtikelKategori::class, 'id_kategori');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'das_artikel_id')->orderBy('created_at', 'desc');
    }

    public function getLinkAttribute(): string
    {
        return Str::replaceFirst(url('/'), '', route('berita.detail', ['slug' => $this->slug]));
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('tanggal_terbit', '<=', Carbon::today());
    }
}
