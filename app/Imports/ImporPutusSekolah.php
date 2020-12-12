<?php

namespace App\Imports;

use App\Models\PutusSekolah;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use function config;

class ImporPutusSekolah implements ToModel, WithHeadingRow
{
    use Importable;

    /** @var Request $request */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function model(array $row)
    {
        return new PutusSekolah([
            'kecamatan_id'   => config('app.default_profile'),
            'desa_id'        => $this->request->input('desa_id'),
            'siswa_paud'     => $row['siswa_paud_ra'],
            'anak_usia_paud' => $row['anak_usia_paud_ra'],
            'siswa_sd'       => $row['siswa_sd_mi'],
            'anak_usia_sd'   => $row['anak_usia_sd_mi'],
            'siswa_smp'      => $row['siswa_smp_mts'],
            'anak_usia_smp'  => $row['anak_usia_smp_mts'],
            'siswa_sma'      => $row['siswa_sma_ma'],
            'anak_usia_sma'  => $row['anak_usia_sma_ma'],
            'semester'       => $this->request->input('semester'),
            'tahun'          => $this->request->input('tahun'),
        ]);
    }
}
