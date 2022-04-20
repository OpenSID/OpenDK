<?php

namespace App\Imports;

use App\Models\Program;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SinkronBantuan implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
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
                'id'            => $value['id'],
                'nama'          => $value['nama'],
                'sasaran'       => $value['sasaran'],
                'status'        => $value['status'],
                'start_date'    => $value['sdate'],
                'end_date'      => $value['edate'],
                'description'   => $value['ndesc'],
                'desa_id'       => $value['kode_desa']
            ];

            Program::updateOrCreate([
                'desa_id' => $insert['desa_id'],
                'id'      => $insert['id']
            ], $insert);
        }
    }
}
