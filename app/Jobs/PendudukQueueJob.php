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

namespace App\Jobs;

use App\Models\Penduduk;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PendudukQueueJob implements ShouldQueue
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
     * @param  array  $request
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * The job failed to process.
     *
     * @return void
     */
    public function failed(\Throwable $e)
    {
        // TODO: Send notification when job failure.
        report($e);
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
            // Batch delete penduduk
            if (! empty($this->request['hapus_penduduk'])) {
                $hapus = collect($this->request['hapus_penduduk']);

                // Hapus per desa agar id_pend_desa milik desa lain dalam batch yang sama tidak ikut terhapus
                foreach ($hapus->groupBy('desa_id') as $desa_id => $items) {
                    Penduduk::where('desa_id', $desa_id)
                        ->whereIn('id_pend_desa', $items->pluck('id_pend_desa'))
                        ->delete();
                }

                // Hapus file foto di folder
                foreach ($hapus->pluck('foto')->filter() as $hapusfoto) {
                    Storage::disk('public')->delete('penduduk/foto/'.'kecil_'.$hapusfoto);
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            // Lempar ulang agar job tercatat gagal dan failed() terpanggil
            throw $e;
        }
    }
}
