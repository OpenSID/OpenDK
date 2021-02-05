<?php

namespace App\Imports;

use App\Models\AkiAkb;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImporAKIAKB implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue
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
        return new AkiAkb([
            'kecamatan_id' => config('app.default_profile'),
            'desa_id'      => $row['desa_id'],
            'bulan'        => $this->request['bulan'],
            'tahun'        => $this->request['tahun'],
            'aki'          => $row['jumlah_aki'],
            'akb'          => $row['jumlah_akb'],
        ]);
    }
}
