<?php

namespace App\Imports;

use App\Models\AnggaranRealisasi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImporAnggaranRealisasi implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue
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
        return new AnggaranRealisasi([
            'kecamatan_id'           => config('app.default_profile'),
            'total_anggaran'         => $row['total_anggaran'],
            'total_belanja'          => $row['total_belanja'],
            'belanja_pegawai'        => $row['belanja_pegawai'],
            'belanja_barang_jasa'    => $row['belanja_barang_jasa'],
            'belanja_modal'          => $row['belanja_modal'],
            'belanja_tidak_langsung' => $row['belanja_tidak_langsung'],
            'bulan'                  => $this->request['bulan'],
            'tahun'                  => $this->request['tahun'],
        ]);
    }
}
