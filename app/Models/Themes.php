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

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Themes extends Model
{
    protected $table = 'das_themes';

    protected $guarded = [];

    // append slug attribute
    protected $appends = [
        'slug',
        'full_path',
        'view_path',
        'asset_path',
    ];

    public function getFullPathAttribute()
    {
        return $this->path;
    }

    public function getViewPathAttribute(): string
    {
        return $this->getFullPathAttribute() . 'resources/views';
    }

    public function getAssetPathAttribute(): string
    {
        return $this->getFullPathAttribute() . '/assets';
    }


    public function getSlugAttribute()
    {
        return $this->vendor.'/'.$this->name;
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($themes) {
            if ($themes->system != 0) {
                $path = base_path('themes/'.$themes->slug);
                if (file_exists($path)) {
                    File::deleteDirectory($path);
                }
            }
        });
    }
}
