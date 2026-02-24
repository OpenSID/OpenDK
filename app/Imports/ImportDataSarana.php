<?php

namespace App\Imports;

use App\Enums\KategoriSarana;
use App\Models\DataDesa;
use App\Models\DataSarana;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportDataSarana implements ToModel, WithHeadingRow
{
    public function __construct(public String $type) {}
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if ($this->type == 'local') {
            if (!DataDesa::where('desa_id', $row['desa_id'])->exists()) {
                return null;
            }
        }
        $kategori = KategoriSarana::getValueFromDescription($row['kategori']);
        if(!$kategori) return null;
        return new DataSarana([
            'desa_id'    => $row['desa_id'],
            'nama'       => $row['nama'],
            'jumlah'     => $row['jumlah'],
            'kategori'   => $kategori,
            'keterangan' => $row['keterangan'],
        ]);
    }
}
