<?php

/*
* Class ini digunakan untuk export data dalam bentuk file
*/

namespace App\Exports;

use App\Models\Penduduk;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportPenduduk implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('das_penduduk')
            ->leftJoin('das_data_desa', 'das_penduduk.desa_id', '=', 'das_data_desa.desa_id')
            ->leftJoin('ref_pendidikan_kk', 'das_penduduk.pendidikan_kk_id', '=', 'ref_pendidikan_kk.id')
            ->leftJoin('ref_kawin', 'das_penduduk.status_kawin', '=', 'ref_kawin.id')
            ->leftJoin('ref_pekerjaan', 'das_penduduk.pekerjaan_id', '=', 'ref_pekerjaan.id')
            ->select([
                'das_penduduk.id',
                'das_penduduk.nama',
                'das_penduduk.nik',
                'das_penduduk.no_kk',
                'das_data_desa.nama as nama_desa',
                'das_penduduk.alamat',
                'ref_pendidikan_kk.nama as pendidikan',
                'das_penduduk.tanggal_lahir',
                'ref_pekerjaan.nama as pekerjaan',
                'ref_kawin.nama as status_kawin',
            ])
            ->where('status_dasar', 1)
            ->get();
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
