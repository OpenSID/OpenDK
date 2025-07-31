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
use Illuminate\Support\Facades\Cache;

class Profil extends Model
{
    // ID Kecamatan untuk default profil

    protected $table = 'das_profil';

    protected $fillable = [
        'provinsi_id',
        'nama_provinsi',
        'kabupaten_id',
        'nama_kabupaten',
        'kecamatan_id',
        'nama_kecamatan',
        'alamat',
        'kode_pos',
        'telepon',
        'email',
        'tahun_pembentukan',
        'dasar_pembentukan',
        'file_struktur_organisasi',
        'file_logo',
        'sambutan',
        'visi',
        'misi',
    ];

    public static function getProfilTanpaDataUmum()
    {
        $data_umums = DataUmum::get();
        $ids = [];
        foreach ($data_umums as $val) {
            $ids[] = $val->kecamatan_id;
        }

        return self::with('Kecamatan')->whereNotIn('kecamatan_id', $ids)->get();
    }

    public function dataUmum()
    {
        return $this->hasOne(DataUmum::class, 'profil_id', 'id');
    }

    public function dataDesa()
    {
        return $this->hasMany(DataDesa::class, 'profil_id', 'id');
    }

    // public function dataPenduduk()
    // {
    //     return $this->hasMany(Penduduk::class, 'kecamatan_id', 'kecamatan_id')->where('status_dasar', 1);
    // }

    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('profil');
            Cache::forget('setting');
            // Clear semua cache yang berhubungan dengan profil kecamatan
            if (function_exists('cache')) {
                try {
                    Cache::tags(['profil', 'kecamatan', 'frontend'])->flush();
                } catch (\Exception $e) {
                    // Jika cache tags tidak didukung oleh driver, gunakan clear individual
                    \Log::info('Cache tags not supported, using individual cache clear');
                }
            }
        });

        static::updated(function () {
            Cache::forget('profil');
            Cache::forget('setting');
            // Clear semua cache yang berhubungan dengan profil kecamatan
            if (function_exists('cache')) {
                try {
                    Cache::tags(['profil', 'kecamatan', 'frontend'])->flush();
                } catch (\Exception $e) {
                    // Jika cache tags tidak didukung oleh driver, gunakan clear individual
                    \Log::info('Cache tags not supported, using individual cache clear');
                }
            }
        });

        static::created(function () {
            Cache::forget('profil');
            Cache::forget('setting');
            // Clear cache saat data profil baru dibuat (install pertama kali)
            if (function_exists('cache')) {
                try {
                    Cache::tags(['profil', 'kecamatan', 'frontend'])->flush();
                } catch (\Exception $e) {
                    // Jika cache tags tidak didukung oleh driver, gunakan clear individual
                    \Log::info('Cache tags not supported, using individual cache clear');
                }
            }
        });
    }
}
