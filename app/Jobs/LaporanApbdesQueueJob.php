<?php

/*
 * File ini bagian dari:
 *
 * PBB Desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @package	    OpenDK
 * @author	    Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

namespace App\Jobs;

use App\Models\LaporanApbdes;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LaporanApbdesQueueJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    public $timeout = 0;
    /** @var array request data */
    protected $request;

    /**
     * Create a new job instance.
     *
     * @param array $request
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;

        Log::debug($this->request);
    }

    /**
     * The job failed to process.
     *
     * @return void
     */
    public function failed(Exception $e)
    {
        // TODO: Send notification when job failure.
        Log::debug($e->getMessage());
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::beginTransaction();

        try {
            $desa_id = $this->request['desa_id'];

            if (isset($this->request['laporan_apbdes'])) {
                foreach ($this->request['laporan_apbdes'] as $value) {
                    $file_name = $desa_id . '_laporan_apbdes_' . $value['bulan'] . '_' . $value['tahun'] . '.' .  explode('.', $value['nama_file'])[1];

                    $insert = [
                        'judul'                => $value['judul'],
                        'tahun'                => $value['tahun'],
                        'semester'             => $value['semester'],
                        'nama_file'            => $file_name,
                        'desa_id'              => $desa_id,
                        'id_apbdes'            => $value['id'],
                        'imported_at'          => now(),
                    ];

                    LaporanApbdes::updateOrInsert([
                        'desa_id'              => $insert['desa_id'],
                        'id_apbdes'            => $insert['id_apbdes'],
                    ], $insert);

                    // Encode File
                    $file = base64_decode($value['file']);
                    Storage::disk('public')->put('apbdes/' . $file_name, $file);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            // debug log when fail.
            Log::debug($e->getMessage());
        }
    }
}
