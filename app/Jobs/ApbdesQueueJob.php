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
            // Batch insert atau update apbdes
            foreach ($this->request['apbdes'] as $value) {
                $insert = [
                    'nama'                  => $value['nama'],
                    'tahun'                 => $value['tahun'],
                    'semester'              => $value['semester'],
                    'tgl_upload'            => $value['tgl_upload'],
                    'nama_file'             => $value['nama_file'],
                    'desa_id'               => $value['desa_id'],
                    'id_apbdes'             => $value['id'],
                    'created_at'            => $value['created_at'],
                    'updated_at'            => $value['updated_at'],
                    'imported_at'           => now(),
                ];

                Apbdes::updateOrInsert([
                    'desa_id'               => $insert['desa_id'],
                    'id_apbdes'             => $insert['id_apbdes']
                ], $insert);
            }

            // Batch delete apbdes
            if (isset($this->request['hapus_apbdes'])) {
                foreach ($this->request['hapus_apbdes'] as $item) {
                    $id_apbdes[] = $item['id_apbdes'];
                    $desa_id[] = $item['desa_id'];
                }

                Apbdes::whereIn('desa_id', $desa_id)->whereNotIn('id_apbdes', $id_apbdes)->delete();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            // debug log when fail.
            Log::debug($e->getMessage());
        }
    }
}