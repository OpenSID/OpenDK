<?php

/*
* Class ini digunakan untuk export data dalam bentuk file
*/

namespace App\Exports;

use App\Models\Penduduk;
use App\Models\SettingAplikasi;
use App\Services\PendudukService;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Http;

class ExportPenduduk implements FromCollection, WithHeadings
{
    protected bool $gabungan;
    protected PendudukService $pendudukService;

    public function __construct($gabungan)
    {
        $this->gabungan = $gabungan;
        $this->pendudukService = new PendudukService();
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        if($this->gabungan){

            return $this->pendudukService->exportPenduduk();

        }else{

            $data = [];
            $penduduks = Penduduk::with('desa', 'pendidikan_kk', 'pekerjaan', 'kawin')->get();
            
            foreach ($penduduks as $penduduk) {
                array_push($data, (object)[
                    'id' => $penduduk->id,
                    'nama' => $penduduk->nama,
                    'nik' => $penduduk->nik,
                    'no_kk' => $penduduk->no_kk,
                    'nama_desa' => $penduduk->desa->nama,
                    'alamat' => $penduduk->alamat,
                    'pendidikan' => $penduduk->pendidikan_kk->nama,
                    'tanggal_lahir' => $penduduk->tanggal_lahir,
                    'pekerjaan' => $penduduk->pekerjaan->nama,
                    'status_kawin' => $penduduk->pekerjaan->nama,
                ]);
            }
    
            return collect($data);
        }
    }

    /**
    * @return array
    */
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
            'PEKERJAAN',
            'STATUS KAWIN'
        ];
    }
}
