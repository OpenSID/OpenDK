<?php

namespace App\Imports;

use Exception;
use App\Models\Keluarga;
use App\Models\Penduduk;
use Illuminate\Support\Arr;
use App\Models\PesertaProgram;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SinkronPesertaBantuan implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
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
        $col_kk = Arr::flatten(Keluarga::pluck('no_kk'));
        $col_nik = Arr::flatten(Keluarga::pluck('no_kk'));
        DB::beginTransaction(); //multai transaction
        
         foreach ($collection as $value) {
            if ( $value['sasaran'] == 1) {
                // cek nik penduduk    
                if (!Penduduk::where('nik', $value['kartu_nik'])->exists()) {
                    Log::debug("Sinkronisasi Peserta Bantuan Gagal. Nomor NIK {$value['kartu_nik']} tidak terdaftar.");
                    DB::rollBack(); // rollback data yang sudah masuk karena ada data yang bermasalah
                    Storage::deleteDirectory('temp'); // Hapus folder temp ketika gagal
                    throw  new Exception("Nomor NIK {$value['kartu_nik']} tidak terdaftar.");
                }
            }else{
                // cek kk penduduk    
                if (!Penduduk::where('nik', $value['kartu_nik'])->whereHas('keluarga')->exists()) {
                    Log::debug("Sinkronisasi Peserta Bantuan Gagal. Nomor KK dari NIK {$value['kartu_nik']} tidak terdaftar.");
                    DB::rollBack(); // rollback data yang sudah masuk karena ada data yang bermasalah
                    Storage::deleteDirectory('temp'); // Hapus folder temp ketika gagal
                    throw  new Exception("Nomor KK dari NIK {$value['kartu_nik']} tidak terdaftar.");
                }
            }
            
            $insert = [
                'id'                    => $value['id'],
                'peserta'               => $value['peserta'],
                'program_id'            => $value['program_id'],
                'no_id_kartu'           => $value['no_id_kartu'],
                'kartu_nik'             => $value['kartu_nik'],
                'kartu_nama'            => $value['kartu_nama'],
                'sasaran'               => $value['sasaran'],
                'kartu_tempat_lahir'    => $value['kartu_tempat_lahir'],
                'kartu_tanggal_lahir'   => $value['kartu_tanggal_lahir'],
                'kartu_alamat'          => $value['kartu_alamat'],
                'kartu_peserta'         => $value['kartu_peserta'],
                'desa_id'               => $value['kode_desa'],
            ];

            PesertaProgram::updateOrCreate([
                'kartu_nik'     => $insert['kartu_nik'],
                'program_id'    => $insert['program_id'],
                'desa_id'       => $insert['desa_id']
            ], $insert);
        }

        DB::commit(); // commit data dan simpan ke dalam database
        // Hapus folder temp ketika sudah selesai
        Storage::deleteDirectory('temp');
    }
}
