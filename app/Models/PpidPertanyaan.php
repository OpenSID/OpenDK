<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PpidPertanyaan extends Model
{
    use HasFactory;

    protected $table = 'ppid_pertanyaan';

    protected $fillable = [
        'ppid_judul',
        'ppid_status',
        'ppid_tipe',
        'urutan',
    ];

    /**
     * Scope untuk pertanyaan tipe Informasi
     */
    public function scopeInformasi($query)
    {
        return $query->where('ppid_tipe', '1')->orderBy('urutan', 'asc');
    }

    /**
     * Scope untuk pertanyaan tipe Mendapatkan
     */
    public function scopeMendapatkan($query)
    {
        return $query->where('ppid_tipe', '2')->orderBy('urutan', 'asc');
    }

    /**
     * Scope untuk pertanyaan tipe Keberatan
     */
    public function scopeKeberatan($query)
    {
        return $query->where('ppid_tipe', '0')->orderBy('urutan', 'asc');
    }

    /**
     * Get label tipe
     */
    public function getTipeLabelAttribute()
    {
        $labels = [
            '0' => 'Keberatan',
            '1' => 'Informasi',
            '2' => 'Mendapatkan',
        ];

        return $labels[$this->ppid_tipe] ?? 'Unknown';
    }

    /**
     * Check jika aktif
     */
    public function isAktif()
    {
        return $this->ppid_status === '1';
    }
}
