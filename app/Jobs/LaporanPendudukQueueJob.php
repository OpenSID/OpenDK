<?php

namespace App\Jobs;

use App\Models\LaporanPenduduk;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LaporanPendudukQueueJob implements ShouldQueue
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

            if (isset($this->request['laporan_penduduk'])) {
                foreach ($this->request['laporan_penduduk'] as $value) {
                    $file_name = $desa_id . '_laporan_penduduk_' . $value['bulan'] . '_' . $value['tahun'] . '.' .  explode('.', $value['nama_file'])[1];

                    $insert = [
                        'judul'                => $value['judul'],
                        'bulan'                => $value['bulan'],
                        'tahun'                => $value['tahun'],
                        'nama_file'            => $file_name,
                        'desa_id'              => $desa_id,
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

                    Storage::disk('public')->put('laporan_penduduk/' . $file_name, base64_decode($value['file']));
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            // debug log when fail.
            Log::debug($e->getMessage());
        }

        // Hapus folder temp ketika sudah selesai
        Storage::deleteDirectory('temp');
    }
}
