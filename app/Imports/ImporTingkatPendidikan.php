<?php

namespace App\Imports;

use App\Models\LogImport;
use App\Models\TingkatPendidikan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use function config;
use function now;

class ImporTingkatPendidikan implements ToModel, WithHeadingRow
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
        $import = LogImport::create([
            'nama_tabel' => 'das_tingkat_pendidikan',
            'desa_id'    => $this->request->input('desa_id'),
            'bulan'      => now()->month,
            'tahun'      => $this->request->input('tahun'),
        ]);

        return new TingkatPendidikan([
            'kecamatan_id'            => config('app.default_profile'),
            'desa_id'                 => $this->request->input('desa_id'),
            'tahun'                   => $this->request->input('tahun'),
            'tidak_tamat_sekolah'     => $row['tidak_tamat_sekolah'],
            'tamat_sd'                => $row['tamat_sd_sederajat'],
            'tamat_smp'               => $row['tamat_smp_sederajat'],
            'tamat_sma'               => $row['tamat_sma_sederajat'],
            'tamat_diploma_sederajat' => $row['tamat_diploma_sederajat'],
            'semester'                => $this->request->input('semester'),
            'import_id'               => $import->id,
        ]);
    }
}
