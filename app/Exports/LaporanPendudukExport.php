<?php

namespace App\Exports;

use App\Services\LaporanPendudukService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanPendudukExport implements FromCollection, WithHeadings
{

    protected bool $gabungan;
    protected LaporanPendudukService $laporanPendudukService;

    public function __construct($gabungan)
    {
        $this->gabungan = $gabungan;
        $this->laporanPendudukService = new LaporanPendudukService();
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->laporanPendudukService->exportLaporanPenduduk();
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
