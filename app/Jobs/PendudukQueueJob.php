<?php

namespace App\Jobs;

use App\Models\Penduduk;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
     * @param array $request
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
            // Batch delete penduduk
            if (isset($this->request['hapus_penduduk'])) {
                foreach ($this->request['hapus_penduduk'] as $item) {
                    $id_pend_desa[] = $item['id_pend_desa'];
                    $foto[] = $item['foto'];
                    $desa_id[] = $item['desa_id'];
                }

                // Hapus data penduduk di database
                Penduduk::whereIn('desa_id', $desa_id)->whereIn('id_pend_desa', $id_pend_desa)->delete();

                // Hapus file foto di folder
                foreach ($foto as $hapusfoto) {
                  Storage::disk('public')->delete('penduduk/foto/' . 'kecil_' . $hapusfoto);
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
