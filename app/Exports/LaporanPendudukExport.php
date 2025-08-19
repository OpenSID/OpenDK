<?php

namespace App\Exports;

use App\Models\LaporanPenduduk;
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
     * Mengambil koleksi data untuk ekspor berdasarkan mode gabungan
     * 
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if ($this->gabungan) {
            // Jika gabungan aktif, gunakan service untuk mengambil data dari API
            return $this->laporanPendudukService->exportLaporanPenduduk();
        } else {
            // Jika gabungan tidak aktif, ambil data dari database lokal
            return LaporanPenduduk::with('desa')
                ->get()
                ->map(function ($laporan) {
                    return [
                        'id' => $laporan->id,
                        'nama_desa' => $laporan->desa->nama ?? 'Tidak Diketahui',
                        'judul' => $laporan->judul,
                        'bulan' => $laporan->bulan,
                        'tahun' => $laporan->tahun,
                        'tanggal_lapor' => $laporan->created_at ? $laporan->created_at->format('Y-m-d') : '',
                    ];
                });
        }
    }

    /**
     * Header kolom untuk file Excel
     * 
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
