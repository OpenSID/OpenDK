<?php

namespace App\Imports;

use App\Models\AnggaranDesa;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImporAPBDesa implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue
{
    use Importable;

    /** @var $request */
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
        return new AnggaranDesa([
            'no_akun'   => $row['no_akun'],
            'nama_akun' => $row['nama_akun'],
            'jumlah'    => $row['jumlah'],
            'bulan'     => $this->request['bulan'],
            'tahun'     => $this->request['tahun'],
            'desa_id'   => $this->request['desa'],
        ]);
    }
}
