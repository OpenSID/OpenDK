<?php

namespace App\Imports;

use App\Models\FasilitasPAUD;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImporFasilitasPaud implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue
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
        return new FasilitasPAUD([
            'kecamatan_id'      => config('app.default_profile'),
            'desa_id'           => $this->request['desa_id'],
            'jumlah_paud'       => $row['jumlah_paud_ra'],
            'jumlah_guru_paud'  => $row['jumlah_guru_paud_ra'],
            'jumlah_siswa_paud' => $row['jumlah_siswa_paud_ra'],
            'semester'          => $this->request['semester'],
            'tahun'             => $this->request['tahun'],
        ]);
    }
}
