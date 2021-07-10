<?php

namespace App\Imports;

use App\Models\Apbdes;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImporLaporanApbdes implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
{
    use Importable;

    public function __construct()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * {@inheritdoc}
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $value) {
            $insert = [
                'judul'                => $value['judul'],
                'tahun'                => $value['tahun'],
                'semester'             => $value['semester'],
                'nama_file'            => $value['nama_file'],
                'desa_id'              => $value['desa_id'],
                'id_apbdes'            => $value['id'],
                'created_at'           => $value['created_at'],
                'updated_at'           => $value['updated_at'],
                'imported_at'          => now(),
            ];

            Apbdes::updateOrInsert([
                'desa_id'              => $insert['desa_id'],
                'id_apbdes'            => $insert['id_apbdes']
            ], $insert);
        }
    }
}