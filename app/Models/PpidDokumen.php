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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PpidDokumen extends Model
{
    use HandlesResourceDeletion, SoftDeletes;

    protected $table = 'das_ppid_dokumen';

    protected $fillable = [
        'judul',
        'jenis_dokumen_id',
        'tipe_dokumen',
        'file_path',
        'url',
        'ringkasan',
        'status',
        'tanggal_publikasi',
    ];

    protected $casts = [
        'tanggal_publikasi' => 'date',
    ];

    protected $resources = [
        'file_path',
    ];

    public function jenisDokumen()
    {
        return $this->belongsTo(PpidJenisDokumen::class, 'jenis_dokumen_id');
    }

    public function scopeTerbit($query)
    {
        return $query->where('status', 'terbit');
    }

    public function scopeTidakTerbit($query)
    {
        return $query->where('status', 'tidak_terbit');
    }

    public function scopeFile($query)
    {
        return $query->where('tipe_dokumen', 'file');
    }

    public function scopeUrl($query)
    {
        return $query->where('tipe_dokumen', 'url');
    }
}
