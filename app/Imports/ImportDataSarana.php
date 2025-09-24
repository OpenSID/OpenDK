<?php

namespace App\Imports;

use App\Models\DataDesa;
use App\Models\DataSarana;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportDataSarana implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (!DataDesa::where('id', $row['desa_id'])->exists()) {
            return null;
        }

        return new DataSarana([
            'desa_id'    => $row['desa_id'],
            'nama'       => $row['nama'],
            'jumlah'     => $row['jumlah'],
            'kategori'   => $row['kategori'],
            'keterangan' => $row['keterangan'],
        ]);
    }
}
