<?php

/*
* Class ini digunakan untuk export data dalam bentuk file
*/

namespace App\Exports;

use App\Models\Penduduk;
use App\Services\PendudukService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportPenduduk implements FromCollection, WithHeadings
{
    protected int $pageSize;

    protected int $pageNumber;

    protected string $filterSearch;

    protected bool $gabungan;

    protected PendudukService $pendudukService;

    public function __construct($gabungan, $params)
    {
        $page = $params['page'] ?? ['size' => 0, 'number' => 1];
        $filter = $params['filter'] ?? ['search' => ''];

        $this->pageSize = $page['size'];
        $this->pageNumber = $page['number'];
        $this->filterSearch = $filter['search'];

        $this->gabungan = $gabungan;
        $this->pendudukService = new PendudukService();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if ($this->gabungan) {
            return $this->pendudukService->exportPenduduk($this->pageSize, $this->pageNumber, $this->filterSearch);
        } else {
            $data = [];
            $penduduks = Penduduk::with('desa', 'pendidikan_kk', 'pekerjaan', 'kawin')->get();

            foreach ($penduduks as $penduduk) {
                array_push($data, (object) [
                    'id' => $penduduk->id,
                    'nama' => $penduduk->nama,
                    'nik' => $penduduk->nik,
                    'no_kk' => $penduduk->no_kk,
                    'nama_desa' => $penduduk->desa?->nama ?? 'tidak diketahui',
                    'alamat' => $penduduk->alamat,
                    'pendidikan' => $penduduk->pendidikan_kk?->nama,
                    'tanggal_lahir' => $penduduk->tanggal_lahir,
                    'umur' => $penduduk->umur,
                    'pekerjaan' => $penduduk->pekerjaan?->nama,
                    'status_kawin' => $penduduk->pekerjaan?->nama,
                ]);
            }

            return collect($data);
        }
    }

    public function headings(): array
    {
        return [
            'ID',
            'NAMA',
            'NIK',
            'NO.KK',
            'DESA',
            'ALAMAT',
            'PENDIDIKAN DALAM KK',
            'TANGGAL LAHIR',
            'UMUR',
            'PEKERJAAN',
            'STATUS KAWIN',
        ];
    }
}
