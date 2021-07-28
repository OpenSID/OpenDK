<?php

namespace App\Jobs;

use App\Models\LaporanApbdes;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
                    
                    $file_name = $desa_id . '_' . $value['id'] . '_' . $value['nama_file'];
                    
                    $insert = [
                        'judul'                => $value['judul'],
                        'tahun'                => $value['tahun'],
                        'semester'             => $value['semester'],
                        'nama_file'            => $file_name,
                        'desa_id'              => $desa_id,
                        'id_apbdes'            => $value['id'],
                        'created_at'           => $value['created_at'],
                        'updated_at'           => $value['updated_at'],
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
