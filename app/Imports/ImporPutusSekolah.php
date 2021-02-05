<?php

namespace App\Imports;

use App\Models\PutusSekolah;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImporPutusSekolah implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue
{
    use Importable;

    /** @var array $request */
    protected $request;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * {@inheritdoc}
     */
    public function model(array $row)
    {
        return new PutusSekolah([
            'kecamatan_id'   => config('app.default_profile'),
            'desa_id'        => $this->request['desa_id'],
            'siswa_paud'     => $row['siswa_paud_ra'],
            'anak_usia_paud' => $row['anak_usia_paud_ra'],
            'siswa_sd'       => $row['siswa_sd_mi'],
            'anak_usia_sd'   => $row['anak_usia_sd_mi'],
            'siswa_smp'      => $row['siswa_smp_mts'],
            'anak_usia_smp'  => $row['anak_usia_smp_mts'],
            'siswa_sma'      => $row['siswa_sma_ma'],
            'anak_usia_sma'  => $row['anak_usia_sma_ma'],
            'semester'       => $this->request['semester'],
            'tahun'          => $this->request['tahun'],
        ]);
    }
}
