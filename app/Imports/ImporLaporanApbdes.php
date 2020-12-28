<?php

namespace App\Imports;

use App\Models\Apbdes;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use function config;
use function substr;

class ImporLaporanApbdes implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
{
    use Importable;

    /** @var string */
    protected $provinsi_id;
    protected $kabupaten_id;
    protected $kecamatan_id;

    public function __construct()
    {
        $this->kecamatan_id = config('app.default_profile');
        $this->provinsi_id  = substr($this->kecamatan_id, 0, 2);
        $this->kabupaten_id = substr($this->kecamatan_id, 0, 5);
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
              'nama'                 => $value['nama'],
              'tahun'                => $value['tahun'],
              'semester'             => $value['semester'],
              'tgl_upload'           => $value['tgl_upload'],
              'nama_file'            => $value['nama_file'],
              'mime_type'            => $value['mime_type'],
              'provinsi_id'          => $this->provinsi_id,
              'kabupaten_id'         => $this->kabupaten_id,
              'kecamatan_id'         => $this->kecamatan_id,
              'desa_id'              => $value['desa_id'],
              'id_apbdes'            => $value['id'],
              'created_at'           => $value['created_at'],
              'updated_at'           => $value['updated_at'],
              'imported_at'          => now(),
            ];

            Apbdes::updateOrInsert([
                'desa_id'      => $insert['desa_id'],
                'id_apbdes' => $insert['id_apbdes']
            ], $insert);
        }
    }
}
