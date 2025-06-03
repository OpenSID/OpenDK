<?php

namespace App\Exports;

use App\Services\LaporanPendudukService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanPendudukByIdExport implements FromCollection, WithHeadings
{
    protected bool $gabungan;
    protected $data;
    protected LaporanPendudukService $laporanPendudukService;

    public function __construct($gabungan, $data)
    {
        $this->data = json_decode($data);  // Pastikan data adalah objek JSON yang valid
        $this->gabungan = $gabungan;
        $this->laporanPendudukService = new LaporanPendudukService();
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Menyiapkan data sebagai array untuk satu baris
        $arr = [
            [
                'id' => $this->data->id,
                'nama_desa' => $this->data->nama_desa,
                'judul' => $this->data->judul,
                'bulan' => $this->data->bulan,
                'tahun' => $this->data->tahun,
                'tanggal_lapor' => $this->data->tanggal_lapor,
            ]
        ];

        // Mengembalikan koleksi yang berisi satu baris data
        return collect($arr);
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            'ID',
            'DESA',
            'JUDUL',
            'BULAN',
            'TAHUN',
            'TANGGAL LAPOR',
        ];
    }
}
