<?php

namespace App\Imports;

use App\Models\AnggaranRealisasi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImporAnggaranRealisasi implements ToModel, WithHeadingRow
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
        return new AnggaranRealisasi([
            'kecamatan_id'           => config('app.default_profile'),
            'total_anggaran'         => $row['total_anggaran'],
            'total_belanja'          => $row['total_belanja'],
            'belanja_pegawai'        => $row['belanja_pegawai'],
            'belanja_barang_jasa'    => $row['belanja_barang_jasa'],
            'belanja_modal'          => $row['belanja_modal'],
            'belanja_tidak_langsung' => $row['belanja_tidak_langsung'],
            'bulan'                  => $this->request->input('bulan'),
            'tahun'                  => $this->request->input('tahun'),
        ]);
    }
}
