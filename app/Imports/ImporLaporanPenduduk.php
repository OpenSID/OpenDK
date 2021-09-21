<?php

namespace App\Imports;

use App\Models\LaporanPenduduk;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImporLaporanPenduduk implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
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
        DB::beginTransaction();

        try {
            foreach ($collection as $value) {
                $file_name = $value['desa_id'] . '_laporan_penduduk_' . $value['bulan'] . '_' . $value['tahun'] . '.' .  explode('.', $value['nama_file'])[1];

                $insert = [
                    'judul'                => $value['judul'],
                    'bulan'                => $value['bulan'],
                    'tahun'                => $value['tahun'],
                    'nama_file'            => $file_name,
                    'desa_id'              => $value['desa_id'],
                    'id_laporan_penduduk'  => $value['id'],
                    'imported_at'          => now(),
                ];

                LaporanPenduduk::updateOrInsert([
                    'desa_id'              => $insert['desa_id'],
                    'id_laporan_penduduk'  => $insert['id_laporan_penduduk']
                ], $insert);

                // Hapus file yang lama
                if (Storage::exists('public/laporan_penduduk/' . $file_name)) {
                    Storage::delete('public/laporan_penduduk/' . $file_name);
                }

                // Pindahkan file yang dibutuhkan saja
                Storage::move('temp/laporan_penduduk/' . $value['nama_file'], 'public/laporan_penduduk/' . $file_name);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            // debug log when fail.
            Log::debug($e->getMessage());
        }

        // Hapus folder temp ketika sudah selesai
        Storage::deleteDirectory('temp');
    }
}
