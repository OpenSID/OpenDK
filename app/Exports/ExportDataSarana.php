<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportDataSarana implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $author;
    protected $data;

    public function __construct(Collection $data, $author = 'Admin')
    {
        $this->data = $data;
        $this->author = $author;
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            return [
                $item->id,
                $item->desa->nama ?? '-',
                $item->nama,
                $item->jumlah,
                $item->kategori,
                $item->keterangan,
            ];
        });
    }

    public function headings(): array
    {
        return [
            ['Laporan Data Sarana'],
            [''],
            ['ID', 'Desa', 'Nama Sarana', 'Jumlah', 'Kategori', 'Keterangan'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            3 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->mergeCells('A1:F1');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                $sheet->setCellValue('A2', 'Nama: ' . $this->author);
                $sheet->setCellValue('F2', 'Tanggal: ' . date('d-m-Y'));

                $sheet->getStyle('A2')->getAlignment()->setHorizontal('left');
                $sheet->getStyle('F2')->getAlignment()->setHorizontal('right');

                $sheet->getStyle('A3:F3')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('4CAF50');

                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle("A3:F{$lastRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            },
        ];
    }
}
