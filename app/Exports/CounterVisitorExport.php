<?php

namespace App\Exports;

use App\Models\Visitor;
use Maatwebsite\Excel\Concerns\FromCollection;

class CounterVisitorExport implements FromCollection
{
    protected $yearlyVisitors;
    protected $topPagesVisited;
    protected $profile;

    public function __construct($yearlyVisitors, $topPagesVisited, $profile = [])
    {
        $this->yearlyVisitors = $yearlyVisitors;
        $this->topPagesVisited = $topPagesVisited;
        $this->profile = $profile;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = [];

        $data[] = [strtoupper('Pemerintah Kab. ' . $this->profile['nama_kabupaten'])];
        $data[] = [strtoupper('Kecamatan ' . $this->profile['nama_kecamatan'])];
        $data[] = ['Tanggal Cetak: ' .\Carbon\Carbon::now()->translatedFormat('d F Y')];

        // Spacer
        $data[] = [''];

        // Data tahunan
        $data[] = ['LAPORAN DATA STATISTIK PENGUNJUNG WEBSITE SETIAP TAHUN'];
        $data[] = ['No', 'Tanggal', 'Page Views', 'Unique Visitors'];
        foreach ($this->yearlyVisitors as $index => $stat) {
            $data[] = [
                $index + 1,
                \Carbon\Carbon::createFromFormat('Y', $stat->date)->translatedFormat('Y'),
                $stat->page_views,
                $stat->unique_visitors,
            ];
        }

        // Spacer
        $data[] = [''];

        // Data halaman populer
        $data[] = ['HALAMAN POPULER'];
        $data[] = ['No', 'URL', 'Page Views', 'Unique Visitors', 'Bounce Rate'];
        foreach ($this->topPagesVisited as $index => $page) {
            $bounceRate = isset($page->bounces) ? round(($page->bounces / $page->total_views) * 100, 1) . '%' : '-';
            $data[] = [
                $index + 1,
                $page->url,
                $page->total_views,
                $page->unique_visitors,
                $bounceRate,
            ];
        }

        return collect($data);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [];
    }
}
