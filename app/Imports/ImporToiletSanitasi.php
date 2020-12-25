<?php

namespace App\Imports;

use App\Models\ToiletSanitasi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImporToiletSanitasi implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue
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
        return new ToiletSanitasi([
            'kecamatan_id' => config('app.default_profile'),
            'desa_id'      => $row['desa_id'],
            'bulan'        => $this->request['bulan'],
            'tahun'        => $this->request['tahun'],
            'toilet'       => $row['toilet'],
            'sanitasi'     => $row['sanitasi'],
        ]);
    }
}
