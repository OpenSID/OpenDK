<?php

namespace App\Imports;

use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use function config;
use function substr;

class ImporPenduduk implements ToCollection, WithHeadingRow
{
    use Importable;

    /** @var string */
    protected $provinsi_id;
    protected $kabupaten_id;
    protected $kecamatan_id;
    protected $desa_id;
    protected $tahun;

    public function __construct(Request $request)
    {
        $this->kecamatan_id = config('app.default_profile');
        $this->provinsi_id  = substr($this->kecamatan_id, 0, 2);
        $this->kabupaten_id = substr($this->kecamatan_id, 0, 5);
        $this->tahun        = $request->input('tahun');
        $this->desa_id      = $request->input('desa_id');
    }

    /**
     * {@inheritdoc}
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $value) {
            $insert = [
                'nik'                   => $value['nomor_nik'],
                'nama'                  => $value['nama'],
                'no_kk'                 => $value['nomor_kk'],
                'sex'                   => $value['jenis_kelamin'],
                'tempat_lahir'          => $value['tempat_lahir'],
                'tanggal_lahir'         => $value['tanggal_lahir'],
                'agama_id'              => $value['agama'],
                'pendidikan_kk_id'      => $value['pendidikan_dlm_kk'],
                'pendidikan_sedang_id'  => $value['pendidikan_sdg_ditempuh'],
                'pekerjaan_id'          => $value['pekerjaan'],
                'status_kawin'          => $value['kawin'],
                'kk_level'              => $value['hubungan_keluarga'],
                'warga_negara_id'       => $value['kewarganegaraan'],
                'nama_ibu'              => $value['nama_ibu'],
                'nama_ayah'             => $value['nama_ayah'],
                'golongan_darah_id'     => $value['gol_darah'],
                'akta_lahir'            => $value['akta_lahir'],
                'dokumen_pasport'       => $value['nomor_dokumen_pasport'],
                'tanggal_akhir_pasport' => $value['tanggal_akhir_pasport'],
                'dokumen_kitas'         => $value['nomor_dokumen_kitas'],
                'ayah_nik'              => $value['nik_ayah'],
                'ibu_nik'               => $value['nik_ibu'],
                'akta_perkawinan'       => $value['nomor_akta_perkawinan'],
                'tanggal_perkawinan'    => $value['tanggal_perkawinan'],
                'akta_perceraian'       => $value['nomor_akta_perceraian'],
                'tanggal_perceraian'    => $value['tanggal_perceraian'],
                'cacat_id'              => $value['cacat'],
                'cara_kb_id'            => $value['cara_kb'],
                'hamil'                 => $value['hamil'],

                // Tambahan
                'alamat_sekarang' => $value['alamat_sekarang'],
                'alamat'          => $value['alamat'],
                'dusun'           => $value['dusun'],
                'rw'              => $value['rw'],
                'rt'              => $value['rt'],
                'provinsi_id'     => $this->provinsi_id,
                'kabupaten_id'    => $this->kabupaten_id,
                'kecamatan_id'    => $this->kecamatan_id,
                'desa_id'         => $this->desa_id,
                'tahun'           => $this->tahun,
                'id_pend_desa'    => $value['id'],
                'status_dasar'    => $value['status_dasar'],
                'created_at'      => $value['created_at'],
                'updated_at'      => $value['updated_at'],
                'imported_at'     => now(),
            ];

            Penduduk::updateOrInsert(['nik' => $insert['nik'], 'id_pend_desa' => $insert['id_pend_desa']], $insert);
        }
    }
}
