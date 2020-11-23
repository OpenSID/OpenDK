<?php

namespace App\Imports;

use App\Models\EpidemiPenyakit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportEpidemiPenyakit implements ToModel, WithHeadingRow
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
        return new EpidemiPenyakit([
            'kecamatan_id'      => config('app.default_profile'),
            'desa_id'           => $row['desa_id'],
            'jumlah_penderita'  => $row['jumlah_penderita'],
            'penyakit_id'       => $this->request->input('penyakit_id'),
            'bulan'             => $this->request->input('bulan'),
            'tahun'             => $this->request->input('tahun'),
        ]);
    }
}
