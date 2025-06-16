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

use App\Enums\StatusSurat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;
    
    protected $table = 'das_log_surat';

    protected $fillable = [
        'desa_id',
        'nik',
        'pengurus_id',
        'tanggal',
        'nomor',
        'nama',
        'file',
        'keterangan',
        'log_verifikasi',
        'verifikasi_operator',
        'verifikasi_sekretaris',
        'verifikasi_camat',
        'status_tte',
        'status',
    ];

    protected $with = [
        'penduduk',
        'pengurus',
        'desa',
    ];

    public function pengurus()
    {
        return $this->hasOne(Pengurus::class, 'id', 'pengurus_id');
    }

    public function penduduk()
    {
        return $this->hasOne(Penduduk::class, 'nik', 'nik');
    }

    public function desa()
    {
        return $this->hasOne(DataDesa::class, 'desa_id', 'desa_id');
    }

    public function scopePermohonan($query)
    {
        return $query->where('status', StatusSurat::Permohonan);
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', StatusSurat::Ditolak);
    }

    public function scopeArsip($query)
    {
        return $query->where('status', StatusSurat::Arsip);
    }
}
