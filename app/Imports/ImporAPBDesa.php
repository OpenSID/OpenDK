<?php

namespace App\Imports;

use App\Models\AnggaranDesa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImporAPBDesa implements ToModel, WithHeadingRow
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
        return new AnggaranDesa([
            'no_akun'   => $row['no_akun'],
            'nama_akun' => $row['nama_akun'],
            'jumlah'    => $row['jumlah'],
            'bulan'     => $this->request->input('bulan'),
            'tahun'     => $this->request->input('tahun'),
            'desa_id'   => $this->request->input('desa'),
        ]);
    }
}
