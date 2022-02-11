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

use App\Models\LaporanPenduduk;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
        }

        // Hapus folder temp ketika sudah selesai
        Storage::deleteDirectory('temp');
    }
}
