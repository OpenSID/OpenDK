<?php

namespace App\Classes\Data;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\Penduduk;
use Excel;


class ImporPenduduk
{
    protected $request = [];
    protected $path;
    protected $data = [];
    protected $desa_id;
    protected $tahun;

    /**
     * Constructor.
     *
     * @param request dari form impor.
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(Request $request)
    {
		$this->request = $request;
        $this->tahun = $request->input('tahun');
        $this->desa_id = $request->input('desa_id');

        request()->validate([
            'file' => 'file|mimes:xls,xlsx,csv|max:5120',
        ]);

        if (!$request->hasFile('file')) {
            throw new \InvalidArgumentException(
				'File excel belum dipilih.'
            );
        }

        $this->path = Input::file('file')->getRealPath();
        $this->data = Excel::selectSheetsByIndex(0)->load($this->path, function ($reader) {
        })->get();
        /*$data = Excel::load($path, function ($reader) {
        })->get();*/

        if (empty($this->data) or !$this->data->count()) {
            throw new \InvalidArgumentException(
				'Data sudah pernah diimport.'
			);
        }
    }

    public function insertOrUpdate()
    {
        ini_set('max_execution_time', 0);

        foreach ($this->data as $key => $value) {
            if (empty($value)) {
                continue;
            }
            $insert = [
                'nik' => $value['nomor_nik'],
                'nama' => $value['nama'],
                'no_kk' => $value['nomor_kk'],
                'sex' => $value['jenis_kelamin'],
                'tempat_lahir' => $value['tempat_lahir'],
                'tanggal_lahir' => $value['tanggal_lahir'],
                'agama_id' => $value['agama'],
                'pendidikan_kk_id' => $value['pendidikan_dlm_kk'],
                'pendidikan_sedang_id' => $value['pendidikan_sdg_ditempuh'],
                'pekerjaan_id' => $value['pekerjaan'],
                'status_kawin' => $value['kawin'],
                'kk_level' => $value['hubungan_keluarga'],
                'warga_negara_id' => $value['kewarganegaraan'],
                'nama_ibu' => $value['nama_ibu'],
                'nama_ayah' => $value['nama_ayah'],
                'golongan_darah_id' => $value['gol_darah'],
                'akta_lahir' => $value['akta_lahir'],
                'dokumen_pasport' => $value['nomor_dokumen_pasport'],
                'tanggal_akhir_pasport' => $value['tanggal_akhir_pasport'],
                'dokumen_kitas' => $value['nomor_dokumen_kitas'],
                'ayah_nik' => $value['nik_ayah'],
                'ibu_nik' => $value['nik_ibu'],
                'akta_perkawinan' => $value['nomor_akta_perkawinan'],
                'tanggal_perkawinan' => $value['tanggal_perkawinan'],
                'akta_perceraian' => $value['nomor_akta_perceraian'],
                'tanggal_perceraian' => $value['tanggal_perceraian'],
                'cacat_id' => $value['cacat'],
                'cara_kb_id' => $value['cara_kb'],
                'hamil' => $value['hamil'],

                // Tambahan
                'alamat_sekarang' => $value['alamat'],
                'alamat' => $value['alamat'],
                'dusun' => $value['dusun'],
                'rw' => $value['rw'],
                'rt' => $value['rt'],

                'kecamatan_id' => config('app.default_profile'),
                'desa_id' => $this->desa_id,
                'tahun' => $this->tahun,
                'status_dasar' => 1,
            ];

            if (empty($insert)) {
                continue;
            }
            $penduduk = Penduduk::where('nik', $insert['nik'])->first();
            if ($penduduk) {
                $penduduk->update($insert);
            } else {
                Penduduk::insert($insert);
            }
        }
    }
}
