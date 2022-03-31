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

use App\Models\AnggaranDesa;
use App\Models\DataDesa;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImporAPBDesa implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
{
    use Importable;

    /** @var $request */
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
        $kode_desa = Arr::flatten(DataDesa::pluck('desa_id'));
        DB::beginTransaction(); //multai transaction

        foreach ($collection as $value) {
            if (! in_array($this->request['desa'], $kode_desa)) {
                Log::debug('Desa tidak terdaftar');
                DB::rollBack(); // rollback data yang sudah masuk karena ada data yang bermasalah
                throw  new Exception('kode Desa tidak terdaftar . kode desa yang bermasalah : '. $value['desa_id']);
            }

            $insert = [
                'desa_id'   => $this->request['desa'],
                'bulan'     => $this->request['bulan'],
                'tahun'     => $this->request['tahun'],
                'no_akun'   => $value['no_akun'],
                'nama_akun' => $value['nama_akun'],
                'jumlah'    => $value['jumlah'],
            ];

            AnggaranDesa::updateOrInsert([
                'desa_id'      => $insert['desa_id'],
                'bulan'        => $insert['bulan'],
                'tahun'        => $insert['tahun'],
                'no_akun'      => $value['no_akun'],
            ], $insert);
        }
        DB::commit();
    }
}
