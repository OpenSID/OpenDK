<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Penduduk extends Model
{
    public $incrementing = false;
    protected $table = 'das_penduduk';
    protected $fillable = [
        'nama',
        'nik',
        'kk_level',
        'rtm_level',
        'sex',
        'tempat_lahir',
        'tanggal_lahir',
        'agama_id',
        'pendidikan_kk_id',
        'pendidikan_id',
        'pendidikan_sedang_id',
        'pekerjaan_id',
        'status_kawin',
        'warga_negara_id',
        'dokumen_pasport',
        'dokumen_kitas',
        'ayah_nik',
        'ibu_nik',
        'nama_ayah',
        'nama_ibu',
        'foto',
        'golongan_darah_id',
        'id_cluster',
        'status',
        'alamat_sebelumnya',
        'alamat_sekarang',
        'status_rekam',
        'ktp_el',
        'status_dasar',
        'hamil',
        'cacat_id',
        'sakit_menahun_id',
        'akta_lahir',
        'akta_perkawinan',
        'tanggal_perkawinan',
        'akta_perceraian',
        'tanggal_perceraian',
        'cara_kb_id',
        'telepon',
        'tanggal_akhir_pasport',
        'no_kk_sebelumnya',
    ];

    /**
     *
     * Relation Methods
     * */


    public function pekerjaan()
    {
        return $this->hasOne(Pekerjaan::class, 'id', 'pekerjaan_id');
    }

    public function kawin()
    {
        return $this->hasOne(Kawin::class, 'id', 'status_kawin');
    }

    public function pendidikan_kk()
    {
        return $this->hasOne(PendidikanKK::class, 'id', 'pendidikan_kk_id');
    }

    public function keluarga()
    {
        return $this->hasOne(Keluarga::class, 'no_kk', 'no_kk');
    }
}