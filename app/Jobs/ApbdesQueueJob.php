<?php

namespace App\Jobs;

use App\Models\Apbdes;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ApbdesQueueJob implements ShouldQueue
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
            // Batch delete Apbdes
            if (isset($this->request['hapus_Apbdes'])) {
                foreach ($this->request['hapus_Apbdes'] as $item) {
                    $id_apbdes[] = $item['id_apbdes'];
                    $nama_file[] = $item['nama_file'];
                    $desa_id[] = $item['desa_id'];
                }

                // Hapus data Apbdes di database
                Apbdes::whereIn('desa_id', $desa_id)->whereIn('id_apbdes', $id_apbdes)->delete();

                // Hapus file nama_file di folder
                foreach ($nama_file as $hapusfile) {
                  Storage::disk('public')->delete('Apbdes/' . $hapusfile);
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
