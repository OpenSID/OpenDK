<?php

namespace App\Imports;

use App\Models\LogImport;
use App\Models\TingkatPendidikan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImporTingkatPendidikan implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue
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
        $import = LogImport::create([
            'nama_tabel' => 'das_tingkat_pendidikan',
            'desa_id'    => $this->request['desa_id'],
            'bulan'      => now()->month,
            'tahun'      => $this->request['tahun'],
        ]);

        return new TingkatPendidikan([
            'kecamatan_id'            => config('app.default_profile'),
            'desa_id'                 => $this->request['desa_id'],
            'tahun'                   => $this->request['tahun'],
            'tidak_tamat_sekolah'     => $row['tidak_tamat_sekolah'],
            'tamat_sd'                => $row['tamat_sd_sederajat'],
            'tamat_smp'               => $row['tamat_smp_sederajat'],
            'tamat_sma'               => $row['tamat_sma_sederajat'],
            'tamat_diploma_sederajat' => $row['tamat_diploma_sederajat'],
            'semester'                => $this->request['semester'],
            'import_id'               => $import->id,
        ]);
    }
}
