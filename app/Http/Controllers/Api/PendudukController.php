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

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SinkronPendudukRequest;
use App\Http\Requests\PendudukRequest;
use App\Imports\SinkronPenduduk;
use App\Jobs\PendudukQueueJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class PendudukController extends Controller
{
/**
     * Hapus data penduduk secara batch (via JSON).
     *
     * @group OpenSID Integration
     *
     * @bodyParam desa_id string required Kode desa. Example: 3201012001
     * @bodyParam hapus_penduduk array required Array objek penduduk yang akan dihapus. Setiap objek berisi: id_pend_desa (int), foto (string/null), desa_id (string).
     *
     * @response {
     *   "status": "success",
     *   "message": "Proses sync Data Penduduk OpenSID sedang berjalan"
     * }
     * @response 422 {
     *   "status": "danger",
     *   "message": "The hapus penduduk field is required.",
     *   "errors": {"hapus_penduduk": ["The hapus penduduk field is required."]}
     * }
     */
public function store(PendudukRequest $request)
    {
        // dispatch queue job penduduk
        PendudukQueueJob::dispatch($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Proses sync Data Penduduk OpenSID sedang berjalan',
        ]);
    }

    /**
     * Sinkronisasi data dan foto penduduk via ZIP dari OpenSID.
     *
     * @group OpenSID Integration
     *
     * Kontrak ZIP:
     * - Berisi 1 file `*.xlsx` (data penduduk) dan file foto `*.jpg`/`*.png`.
     * - Kolom XLSX: desa_id, id, nomor_nik, nama, nomor_kk, jenis_kelamin,
     *   tempat_lahir, tanggal_lahir, agama, pendidikan_dlm_kk, pekerjaan,
     *   kawin, hubungan_keluarga, kewarganegaraan, nama_ibu, nama_ayah,
     *   gol_darah, akta_lahir, nik_ayah, nik_ibu, foto, alamat, dusun,
     *   rw, rt, status_dasar, status_rekam.
     *
     * @bodyParam file file required File ZIP (max 5MB) berisi data penduduk + foto. Example: null
     *
     * @response {
     *   "status": "success",
     *   "message": "Data Foto Telah Berhasil di Sinkronkan"
     * }
     * @response 422 {
     *   "status": "danger",
     *   "message": "The file field is required.",
     *   "errors": {"file": ["The file field is required."]}
     * }
     * @response 500 {
     *   "status": "danger",
     *   "message": "Import data penduduk gagal. Periksa log OpenDK untuk detail kesalahan."
     * }
     */
    public function storedata(SinkronPendudukRequest $request)
    {
        $zipPath = null;
        $excellName = '';

        try {
            // Upload file zip temporary using FileUploadService for security
            $file = $request->file('file');

            // Use FileUploadService for secure file upload
            $fileUploadService = new \App\Services\FileUploadService();

            // Define allowed MIME types for zip files
            $allowedMimes = \App\Services\FileUploadService::getAllowedMimes('archive');

            // Upload file securely to temp directory (disk public)
            $zipPath = $fileUploadService->uploadSecure($file, 'temp', $allowedMimes, 5120); // 5MB max

            $extract = storage_path('app/public/penduduk/foto/');
            $zip = new ZipArchive();
            if ($zip->open(Storage::disk('public')->path($zipPath)) !== true) {
                throw new \RuntimeException('Gagal membuka file zip.');
            }

            // Hanya ekstrak xlsx dan foto di root zip; entri lain (subfolder, tipe lain) diabaikan
            $entries = [];
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);

                if ($filename !== basename($filename)) {
                    continue;
                }

                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                if ($extension === 'xlsx' && $excellName === '') {
                    $excellName = $filename;
                    $entries[] = $filename;
                } elseif (in_array($extension, ['jpg', 'jpeg', 'png'], true)) {
                    $entries[] = $filename;
                }
            }

            if ($excellName === '') {
                $zip->close();

                throw new \RuntimeException('File xlsx tidak ditemukan di dalam zip.');
            }

            $zip->extractTo($extract, $entries);
            $zip->close();

            // Impor sinkron agar kegagalan langsung dibalas ke OpenSID
            (new SinkronPenduduk())->import($extract . $excellName);
        } catch (\Throwable $e) {
            Log::error('Penduduk storedata failed', [
                'error' => $e->getMessage(),
            ]);

            // HTTP 500 agar OpenSID (Guzzle) mendeteksi kegagalan; detail exception hanya di log
            return response()->json([
                'status' => 'danger',
                'message' => 'Import data penduduk gagal. Periksa log OpenDK untuk detail kesalahan.',
            ], 500);
        } finally {
            // Hapus zip dan xlsx temp, baik impor berhasil maupun gagal
            if ($zipPath) {
                Storage::disk('public')->delete($zipPath);
            }
            if ($excellName !== '') {
                Storage::disk('public')->delete('penduduk/foto/' . $excellName);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data Foto Telah Berhasil di Sinkronkan',
        ]);
    }

    /**
     * Test endpoint untuk verifikasi API berjalan.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function test()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Penduduk API route is working',
        ]);
    }
}
