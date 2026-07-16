<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Imports;

use App\Models\PesertaProgram;
use App\Models\Program;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SinkronBantuan implements ShouldQueue, ToCollection, WithChunkReading, WithHeadingRow
{
    use Importable;

    /**
     * {@inheritdoc}
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $data) {
            $desa_id[] = $data['desa_id'];
        }

        PesertaProgram::whereIn('desa_id', $desa_id)->delete();
        Program::whereIn('desa_id', $desa_id)->delete();

        foreach ($collection as $value) {
            $insert = [
                'desa_id' => $value['desa_id'],
                'id' => $value['id'],
                'nama' => $value['nama'],
                'sasaran' => $value['sasaran'],
                'status' => $value['status'],
                'start_date' => $this->parseTanggal($value['sdate']),
                'end_date' => $this->parseTanggal($value['edate']),
                'description' => $value['ndesc'],
            ];

            Program::updateOrCreate([
                'desa_id' => $insert['desa_id'],
                'id' => $insert['id'],
            ], $insert);
        }
    }

    /**
     * Konversi berbagai format tanggal (termasuk ISO 8601 dari Excel)
     * menjadi format MySQL DATETIME yang valid.
     */
    private function parseTanggal($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return null;
        }
    }
}
