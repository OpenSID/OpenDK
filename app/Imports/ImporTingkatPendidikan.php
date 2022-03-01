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

use App\Models\LogImport;
use App\Models\TingkatPendidikan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImporTingkatPendidikan implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
{
    use Importable;

    /** @var array $request */
    protected $request;

    public function __construct(array $request)
    {
        $this->request = $request;
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
        DB::beginTransaction();

        try {
            $import = LogImport::create([
                'nama_tabel' => 'das_tingkat_pendidikan',
                'desa_id'    => $this->request['desa_id'],
                'bulan'      => now()->month,
                'tahun'      => $this->request['tahun'],
            ]);

            foreach ($collection as $value) {
                $insert = [
                    'desa_id'                 => $this->request['desa_id'],
                    'semester'                => $this->request['semester'],
                    'tahun'                   => $this->request['tahun'],
                    'tidak_tamat_sekolah'     => $value['tidak_tamat_sekolah'],
                    'tamat_sd'                => $value['tamat_sd_sederajat'],
                    'tamat_smp'               => $value['tamat_smp_sederajat'],
                    'tamat_sma'               => $value['tamat_sma_sederajat'],
                    'tamat_diploma_sederajat' => $value['tamat_diploma_sederajat'],
                    'import_id'               => $import->id,
                ];

                TingkatPendidikan::updateOrInsert([
                    'desa_id'      => $insert['desa_id'],
                    'semester'     => $insert['semester'],
                    'tahun'        => $insert['tahun'],
                ], $insert);
            }

            DB::commit();
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
        }
    }
}
