<?php

namespace App\Imports;

use App\Models\Imunisasi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportImunisasi implements ToModel, WithHeadingRow
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
        return new Imunisasi([
            'kecamatan_id'      => config('app.default_profile'),
            'desa_id'           => $row['desa_id'],
            'bulan'             => $this->request->input('bulan'),
            'tahun'             => $this->request->input('tahun'),
            'cakupan_imunisasi' => $row['cakupan_imunisasi'],
        ]);
    }
}
