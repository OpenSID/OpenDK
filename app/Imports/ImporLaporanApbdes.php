<?php

namespace App\Imports;

use App\Models\LaporanApbdes;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImporLaporanApbdes implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
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
     * {@inheritdoc}
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $value) {
            $file_name = $value['desa_id'] . '_' . $value['id'] . '_' . $value['nama_file'];

            $insert = [
                'judul'                => $value['judul'],
                'tahun'                => $value['tahun'],
                'semester'             => $value['semester'],
                'nama_file'            => $file_name,
                'desa_id'              => $value['desa_id'],
                'id_apbdes'            => $value['id'],
                'created_at'           => $value['created_at'],
                'updated_at'           => $value['updated_at'],
                'imported_at'          => now(),
            ];

            LaporanApbdes::updateOrInsert([
                'desa_id'              => $insert['desa_id'],
                'id_apbdes'            => $insert['id_apbdes']
            ], $insert);

            // Pindahkan file yang dibutuhkan saja
            Storage::move('temp/apbdes/' . $value['nama_file'], 'public/apbdes/' . $file_name);
        }

        // Hapus folder temp ketika sudah selesai
        Storage::deleteDirectory('temp');
    }
}