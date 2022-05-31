<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Imports;

use App\Models\Penduduk;
use App\Models\PesertaProgram;
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
        DB::beginTransaction(); //multai transaction

        foreach ($collection as $value) {
            if ($value['sasaran'] == 1) {
                // cek nik penduduk
                if (!Penduduk::where('nik', $value['kartu_nik'])->exists()) {
                    Log::debug("Sinkronisasi Peserta Bantuan Gagal. Nomor NIK {$value['kartu_nik']} tidak terdaftar.");
                    DB::rollBack(); // rollback data yang sudah masuk karena ada data yang bermasalah
                    Storage::deleteDirectory('temp'); // Hapus folder temp ketika gagal
                    throw  new Exception("Nomor NIK {$value['kartu_nik']} tidak terdaftar.");
                }
            } else {
                // cek kk penduduk
                if (!Penduduk::where('nik', $value['kartu_nik'])->whereHas('keluarga')->exists()) {
                    Log::debug("Sinkronisasi Peserta Bantuan Gagal. Nomor KK dari NIK {$value['kartu_nik']} tidak terdaftar.");
                    DB::rollBack(); // rollback data yang sudah masuk karena ada data yang bermasalah
                    Storage::deleteDirectory('temp'); // Hapus folder temp ketika gagal
                    throw  new Exception("Nomor KK dari NIK {$value['kartu_nik']} tidak terdaftar.");
                }
            }

            $insert = [
                'desa_id'               => $value['desa_id'],
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
            ];

            PesertaProgram::updateOrCreate([
                'desa_id'       => $insert['desa_id'],
                'program_id'    => $insert['program_id'],
                'kartu_nik'     => $insert['kartu_nik'],
            ], $insert);
        }

        DB::commit(); // commit data dan simpan ke dalam database
        // Hapus folder temp ketika sudah selesai
        Storage::deleteDirectory('temp');
    }
}
