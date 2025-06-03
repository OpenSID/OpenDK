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

use App\Enums\Status;
use App\Enums\JenisJabatan;
use App\Traits\HandlesResourceDeletion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengurus extends Model
{
    use HasFactory;
    use HandlesResourceDeletion;

    protected $table = 'das_pengurus';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    /**
     * Daftar field-file yang harus dihapus.
     *
     * @var array
     */
    protected $resources = [
        'foto',
    ];

    protected $with = [
        'jabatan',
        'agama',
        'pendidikan',
    ];

    protected $appends = [
        'namaGelar',
    ];

    public function getFotoAttribute()
    {
        return $this->attributes['foto'] ? Storage::url('pengurus/'.$this->attributes['foto']) : null;
    }

    /**
     * Setter untuk membuat nama dan gelar.
     *
     * @return string
     */
    public function getNamaGelarAttribute()
    {
        $nama = $this->attributes['gelar_depan'].' '.$this->attributes['nama'];

        if ($this->attributes['gelar_belakang']) {
            $nama = $nama.', '.$this->attributes['gelar_belakang'];
        }

        return $nama;
    }

    public function jabatan()
    {
        return $this->hasOne(Jabatan::class, 'id', 'jabatan_id');
    }

    public function pendidikan()
    {
        return $this->hasOne(PendidikanKK::class, 'id', 'pendidikan_id');
    }

    public function agama()
    {
        return $this->hasOne(Agama::class, 'id', 'agama_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'pengurus_id', 'id');
    }

    public function scopeStatus($query, $value = 1)
    {
        return $query->where('status', $value);
    }

    public function scopeCamat($query)
    {
        return $query->whereHas('jabatan', function ($q) {
            $q->where('jenis', JenisJabatan::Camat);
        });
    }

    public function scopeAkunCamat($query)
    {
        return $query->whereHas('jabatan', function ($q) {
            $q->where('jenis', JenisJabatan::Camat);
        })->whereHas('user');
    }

    public function scopeSekretaris($query)
    {
        return $query->whereHas('jabatan', function ($q) {
            $q->where('jenis', JenisJabatan::Sekretaris);
        });
    }

    public function scopeAkunSekretaris($query)
    {
        return $query->whereHas('jabatan', function ($q) {
            $q->where('jenis', JenisJabatan::Sekretaris);
        })->whereHas('user');
    }

    /**
     * Cek pengurus aktif.
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function cekPengurus()
    {
        $kecuali = [];

        // Cek apakah kades
        if (Pengurus::where('jabatan_id', JenisJabatan::Camat)->where('status', Status::Aktif)->exists()) {
            $kecuali[] = 1;
        }

        // Cek apakah sekdes
        if (Pengurus::where('jabatan_id', JenisJabatan::Sekretaris)->where('status', Status::Aktif)->exists()) {
            $kecuali[] = 2;
        }

        return $kecuali;
    }

    public function scopeListAtasan($query, $id = null)
    {
        if ($id) {
            $query->where('das_pengurus.id', '<>', $id);
        }

        return $query->select([
            'das_pengurus.id AS id_pengurus',
            'ref_jabatan.id AS jabatan_id',
            'ref_jabatan.nama AS jabatan',
            'das_pengurus.nama AS nama_pengurus',
            'das_pengurus.nik'
        ])->join('ref_jabatan', 'das_pengurus.jabatan_id', '=', 'ref_jabatan.id');
    }
}
