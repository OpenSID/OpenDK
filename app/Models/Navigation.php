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

use App\Enums\MenuTipe;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Navigation extends Model
{

    /**
     * {@inheritDoc}
     */
    protected $table = 'das_navigation';

    /**
     * {@inheritDoc}
     */
    protected $fillable = ['name', 'slug', 'parent_id', 'type', 'url', 'order', 'status'];

    protected $appends = [
        'full_url',
    ];

    /**
     * Return user's query for Datatables.
     *
     * @return Builder
     */
    public static function datatables()
    {
        return static::select('name', 'slug', 'parent_id', 'type', 'url', 'order', 'id', 'status')->get();
    }

    public static function lastOrder($parent_id = 0)
    {
        return static::where('parent_id', $parent_id)->max('order');
    }

    public function parent()
    {
        return $this->belongsTo(Navigation::class, 'parent_id');
    }

    public function childrens()
    {
        return $this->hasMany(Navigation::class, 'parent_id');
    }

    public function setParentIdAttribute($value)
    {
        $this->attributes['parent_id'] = $value == 0 ? null : $value;
    }

    public function setNavTypeAttribute($value)
    {
        $this->attributes['nav_type'] = 'system';
    }

    public function setUrlAttribute($value)
    {
        if ($this->type != MenuTipe::EKSTERNAL) {
            $this->attributes['url'] = str_replace(url('/') . '/', '', $value);
        } else {
            $this->attributes['url'] = $value;
        }
    }

    public function getFullUrlAttribute()
    {
        switch ($this->type) {
            case MenuTipe::PROFIL:
            case MenuTipe::DESA:
            case MenuTipe::STATISTIK:
            case MenuTipe::POTENSI:
            case MenuTipe::UNDUHAN:
                return url($this->url);
                break;
            case MenuTipe::EKSTERNAL:
                return $this->url;
                break;
            default:
                return '#';
                break;
        }
    }
}
