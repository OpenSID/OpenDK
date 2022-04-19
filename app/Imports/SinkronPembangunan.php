<?php

namespace App\Imports;

use App\Models\Pembangunan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SinkronPembangunan implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
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
                "sumber_dana" => $value['sumber_dana'],
                "lokasi" => $value['lokasi'],
                "keterangan" => $value['keterangan'],
                "judul" => $value['judul'],
                "volume" => $value['volume'],
                "tahun_anggaran" => $value['tahun_anggaran'],
                "pelaksana_kegiatan" => $value['pelaksana_kegiatan'],
                "status" => $value['status'],
                "anggaran" => $value['anggaran'],
                "perubahan_anggaran" => $value['perubahan_anggaran'],
                "sumber_biaya_pemerintah" => $value['sumber_biaya_pemerintah'],
                "sumber_biaya_provinsi" => $value['sumber_biaya_provinsi'],
                "sumber_biaya_kab_kota" => $value['sumber_biaya_kab_kota'],
                "sumber_biaya_swadaya" => $value['sumber_biaya_swadaya'],
                "sumber_biaya_jumlah" => $value['sumber_biaya_jumlah'],
                "manfaat" => $value['manfaat'],
                "waktu" => $value['waktu'],
                "foto" => $value['foto'],
                "kode_desa" => (string) $value['desa_id'],
            ];

            Pembangunan::updateOrCreate([
                'kode_desa' => $insert['kode_desa'],
                'id'     => $insert['id']
            ], $insert);
        }
    }
}
