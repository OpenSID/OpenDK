<?php

namespace App\Imports;

use App\Models\Pembangunan;
use App\Models\PembangunanDokumentasi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SinkronPembangunanDokumentasi implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
{
    use Importable;

    /**
     * {@inheritdoc}
     */
    public function chunkSize(): int
    {
        return 1000;
    }
    
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $value) {
            $insert = [
                "id" => $value['id'],
                "id_pembangunan" => $value['id_pembangunan'],
                "gambar" => $value['gambar'],
                "persentase" => $value['persentase'],
                "keterangan" => $value['keterangan'],
                "created_at" => $value['created_at'],
                "updated_at" => $value['updated_at'],
                "kode_desa" => (string) $value['desa_id'],
            ];

            PembangunanDokumentasi::updateOrCreate([
                'kode_desa' => $insert['kode_desa'],
                'id'     => $insert['id'],
                'id_pembangunan'     => $insert['id_pembangunan']
            ], $insert);
        }
    }
}
