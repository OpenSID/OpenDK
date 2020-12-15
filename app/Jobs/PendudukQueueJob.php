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
use Illuminate\Support\Facades\Log;

class PendudukQueueJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
     * @param  Exception $e
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
            // Batch insert atau update penduduk
            foreach ($this->request['penduduk'] as $value) {
                $insert = [
                    "id_pend_desa"          => $value["id_pend_desa"],
                    "nama"                  => $value["nama"],
                    "nik"                   => $value["nik"],
                    "id_kk"                 => $value["id_kk"],
                    "kk_level"              => $value["kk_level"],
                    "id_rtm"                => $value["id_rtm"],
                    "rtm_level"             => $value["rtm_level"],
                    "sex"                   => $value["sex"],
                    "tempat_lahir"          => $value["tempat_lahir"],
                    "tanggal_lahir"         => $value["tanggal_lahir"],
                    "agama_id"              => $value["agama_id"],
                    "pendidikan_kk_id"      => $value["pendidikan_kk_id"],
                    "pendidikan_sedang_id"  => $value["pendidikan_sedang_id"],
                    "pekerjaan_id"          => $value["pekerjaan_id"],
                    "status_kawin"          => $value["status_kawin"],
                    "warga_negara_id"       => $value["warga_negara_id"],
                    "dokumen_pasport"       => $value["dokumen_pasport"],
                    "dokumen_kitas"         => $value["dokumen_kitas"],
                    "ayah_nik"              => $value["ayah_nik"],
                    "ibu_nik"               => $value["ibu_nik"],
                    "nama_ayah"             => $value["nama_ayah"],
                    "nama_ibu"              => $value["nama_ibu"],
                    "foto"                  => $value["foto"],
                    "golongan_darah_id"     => $value["golongan_darah_id"],
                    "id_cluster"            => $value["id_cluster"],
                    "status"                => $value["status"],
                    "alamat_sebelumnya"     => $value["alamat_sebelumnya"],
                    "alamat_sekarang"       => $value["alamat_sekarang"],
                    "status_dasar"          => $value["status_dasar"],
                    "hamil"                 => $value["hamil"],
                    "cacat_id"              => $value["cacat_id"],
                    "sakit_menahun_id"      => $value["sakit_menahun_id"],
                    "akta_lahir"            => $value["akta_lahir"],
                    "akta_perkawinan"       => $value["akta_perkawinan"],
                    "tanggal_perkawinan"    => $value["tanggal_perkawinan"],
                    "akta_perceraian"       => $value["akta_perceraian"],
                    "tanggal_perceraian"    => $value["tanggal_perceraian"],
                    "cara_kb_id"            => $value["cara_kb_id"],
                    "telepon"               => $value["telepon"],
                    "tanggal_akhir_pasport" => $value["tanggal_akhir_pasport"],
                    "no_kk"                 => $value["no_kk"],
                    "no_kk_sebelumnya"      => $value["no_kk_sebelumnya"],
                    "ktp_el"                => $value["ktp_el"],
                    "status_rekam"          => $value["status_rekam"],
                    "alamat"                => $value["alamat"],
                    "dusun"                 => $value["dusun"],
                    "rw"                    => $value["rw"],
                    "rt"                    => $value["rt"],
                    "desa_id"               => $value["desa_id"],
                    "kecamatan_id"          => $value["kecamatan_id"],
                    "kabupaten_id"          => $value["kabupaten_id"],
                    "provinsi_id"           => $value["provinsi_id"],
                    "tahun"                 => $value["tahun"],
                    "created_at"            => $value["created_at"],
                    "updated_at"            => $value["updated_at"],
                    "imported_at"           => $value["imported_at"],
                ];

                Penduduk::updateOrInsert([
                    'desa_id'      => $insert['desa_id'],
                    'id_pend_desa' => $insert['id_pend_desa']
                ], $insert);
            }

            // Batch delete penduduk
            if (isset($this->request['hapus_penduduk'])) {
                foreach ($this->request['hapus_penduduk'] as $item) {
                    $nik[] = $item['nik'];
                    $desa_id[] = $item['desa_id'];
                }

                Penduduk::whereIn('desa_id', $desa_id)->whereNotIn('nik', $nik)->delete();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            // debug log when fail.
            Log::debug($e->getMessage());
        }
    }
}
