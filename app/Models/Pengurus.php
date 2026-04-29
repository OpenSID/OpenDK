<?php

declare(strict_types=1);

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Models;

use App\Enums\JenisJabatan;
use App\Enums\Status;
use App\Traits\HandlesResourceDeletion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Pengurus extends Model
{
    use HandlesResourceDeletion;
    use HasFactory;

    protected $table = 'das_pengurus';

    /**
     * Field yang dilindungi dari mass assignment.
     *
     * Field-field ini bersifat sensitif dan tidak boleh diubah sembarangan
     * melalui mass assignment untuk keamanan data pengurus.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',
        'nik',                  // Identitas unik pengurus
        'nip',                  // Nomor Induk Pegawai
        'status',               // Status aktif/tidak - mencegah self-activation
        'jabatan_id',           // ID jabatan - mencegah promosi ilegal
        'created_at',
        'updated_at',
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

    public function getFotoAttribute(): ?string
    {
        return $this->attributes['foto'] ? Storage::url('pengurus/'.$this->attributes['foto']) : null;
    }

    /**
     * Getter untuk membuat nama dan gelar.
     */
    public function getNamaGelarAttribute(): string
    {
        $nama = $this->attributes['gelar_depan'].' '.$this->attributes['nama'];

        if ($this->attributes['gelar_belakang']) {
            $nama = $nama.', '.$this->attributes['gelar_belakang'];
        }

        return $nama;
    }

    public function jabatan(): HasOne
    {
        return $this->hasOne(Jabatan::class, 'id', 'jabatan_id');
    }

    public function pendidikan(): HasOne
    {
        return $this->hasOne(PendidikanKK::class, 'id', 'pendidikan_id');
    }

    public function agama(): HasOne
    {
        return $this->hasOne(Agama::class, 'id', 'agama_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'pengurus_id', 'id');
    }

    public function scopeStatus(\Illuminate\Database\Eloquent\Builder $query, int $value = 1): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', $value);
    }

    public function scopeCamat(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereHas('jabatan', function ($q) {
            $q->where('jenis', JenisJabatan::Camat);
        });
    }

    public function scopeAkunCamat(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereHas('jabatan', function ($q) {
            $q->where('jenis', JenisJabatan::Camat);
        })->whereHas('user');
    }

    public function scopeSekretaris(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereHas('jabatan', function ($q) {
            $q->where('jenis', JenisJabatan::Sekretaris);
        });
    }

    public function scopeAkunSekretaris(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereHas('jabatan', function ($q) {
            $q->where('jenis', JenisJabatan::Sekretaris);
        })->whereHas('user');
    }

    /**
     * Cek pengurus aktif.
     *
     * @return array<int>
     */
    public function cekPengurus(): array
    {
        $kecuali = [];

        // Cek apakah kades
        if (Pengurus::whereHas('jabatan', fn ($q) => $q->where('jenis', JenisJabatan::Camat))->where('status', Status::Aktif)->exists()) {
            $kecuali[] = JenisJabatan::Camat;
        }

        // Cek apakah sekdes
        if (Pengurus::whereHas('jabatan', fn ($q) => $q->where('jenis', JenisJabatan::Sekretaris))->where('status', Status::Aktif)->exists()) {
            $kecuali[] = JenisJabatan::Sekretaris;
        }

        return $kecuali;
    }

    public function scopeListAtasan(\Illuminate\Database\Eloquent\Builder $query, ?int $id = null): \Illuminate\Database\Eloquent\Builder
    {
        if ($id) {
            $query->where('das_pengurus.id', '<>', $id);
        }

        return $query->select([
            'das_pengurus.id AS id_pengurus',
            'ref_jabatan.id AS jabatan_id',
            'ref_jabatan.nama AS jabatan',
            'das_pengurus.nama AS nama_pengurus',
            'das_pengurus.nik',
        ])->join('ref_jabatan', 'das_pengurus.jabatan_id', '=', 'ref_jabatan.id');
    }
}
