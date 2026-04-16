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
use Illuminate\Database\Eloquent\SoftDeletes;

class PpidPengaturan extends Model
{
    use SoftDeletes;

    protected $table = 'das_ppid_pengaturan';

    protected $fillable = [
        'kunci',
        'nilai',
        'keterangan',
    ];

    protected $casts = [
        'nilai' => 'array',
    ];

    /**
     * Get value by key
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('kunci', $key)->first();

        if ($setting) {
            return $setting->nilai;
        }

        return $default;
    }

    /**
     * Set value by key
     */
    public static function setValue(string $key, $value, ?string $keterangan = null): void
    {
        $setting = static::where('kunci', $key)->first();

        if ($setting) {
            $setting->update([
                'nilai' => $value,
                'keterangan' => $keterangan,
            ]);
        } else {
            static::create([
                'kunci' => $key,
                'nilai' => $value,
                'keterangan' => $keterangan,
            ]);
        }
    }
}
