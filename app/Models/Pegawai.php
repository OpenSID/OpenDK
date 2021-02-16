<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Jabatan;
class Pegawai extends Model
{
    protected $table      = "das_pegawai";
    protected $primaryKey = "id";
    protected $fillable   = [
        'nip',
        'nama_pegawai',
        'pangkat',
        'golongan',
        'ruang',
        'jabatan_id',
        'telepon',
        'jenis_kelamin',
        'agama_id',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'status_kawin_id',
        'nomor_karpeg',
        'nik',
        'status_pegawai',
        'pangkat_cpns',
        'tmt_cpns',
        'tmt_pangkat',
        'tmt_jabatan',
        'telepon',
        'status',
        'pendidikan',
        'tamat_pendidikan',
    ];
    public $timestamps    = false;

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }
}
