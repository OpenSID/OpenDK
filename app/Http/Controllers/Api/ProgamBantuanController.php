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
use App\Http\Requests\ProgramBantuanRequest;
use App\Imports\SinkronBantuan;
use App\Imports\SinkronPesertaBantuan;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ProgamBantuanController extends Controller
{
    /**
     * Ekstensi file data yang didukung di dalam zip sinkronisasi.
     * Daftar ini sengaja tidak dibatasi satu format saja, karena
     * format yang dikirim OpenSID bisa berbeda antar versi/modul.
     */
    private const SUPPORTED_EXTENSIONS = ['csv', 'xlsx'];

    public function store(ProgramBantuanRequest $request)
    {
        $fileName = null;

        try {
            // Upload file zip temporary using FileUploadService for security
            $file = $request->file('file');

            // Use FileUploadService for secure file upload
            $fileUploadService = new FileUploadService;

            // Define allowed MIME types for zip files
            $allowedMimes = FileUploadService::getAllowedMimes('archive');

            // Upload file securely to temp directory
            $path = $fileUploadService->uploadSecure($file, 'temp', $allowedMimes, 51200); // 50MB max

            // Extract filename from path
            $name = basename($path);

            // FIX: FileUploadService::uploadSecure() menyimpan ke disk 'public'
            // (lihat $file->storeAs($directory, $safeFileName, 'public')),
            // sehingga lokasi file sebenarnya ada di app/public/temp/, bukan app/temp/
            $path = storage_path("app/public/temp/{$name}");
            $extract = storage_path('app/public/bantuan/');

            // Ekstrak file
            $zip = new ZipArchive;
            $openResult = $zip->open($path);

            // FIX: jangan lanjut memakai object zip jika open() gagal —
            // mencegah ValueError "Invalid or uninitialized Zip object"
            if ($openResult !== true) {
                throw new \RuntimeException(
                    "Gagal membuka file zip (kode error ZipArchive: {$openResult}). Path: {$path}"
                );
            }

            // FIX: cari nama file data yang SEBENARNYA ada di dalam zip,
            // jangan tebak dari nama file zip. Nama file zip sudah diacak oleh
            // FileUploadService::generateSafeFileName() dan tidak ada hubungan
            // apa pun dengan nama file asli di dalamnya.
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entryName = $zip->getNameIndex($i);
                $extension = strtolower(pathinfo($entryName, PATHINFO_EXTENSION));

                if (in_array($extension, self::SUPPORTED_EXTENSIONS, true)) {
                    $fileName = $entryName;
                    break;
                }
            }

            $zip->extractTo($extract);
            $zip->close();

            if (! $fileName) {
                throw new \RuntimeException('File data (csv/xlsx) tidak ditemukan di dalam zip yang diupload.');
            }

            // Proses impor data
            (new SinkronBantuan)->queue($extract.$fileName);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'status' => 'danger',
                'message' => $e->getMessage(),
            ]);
        } finally {
            // FIX: dipindah ke finally agar folder temp selalu dibersihkan,
            // baik proses berhasil maupun gagal di tengah jalan
            Storage::deleteDirectory('temp');
        }

        // FIX: tambahkan separator '/' sebelum nama file
        if ($fileName) {
            Storage::disk('public')->delete('bantuan/'.$fileName);
        }

        return response()->json([
            'message' => 'Data Bantuan Sedang di Sinkronkan',
            'status' => 'success',
        ]);
    }

    public function storePeserta(ProgramBantuanRequest $request)
    {
        $fileName = null;

        try {
            // Upload file zip temporary using FileUploadService for security
            $file = $request->file('file');

            // Use FileUploadService for secure file upload
            $fileUploadService = new FileUploadService;

            // Define allowed MIME types for zip files
            $allowedMimes = FileUploadService::getAllowedMimes('archive');

            // Upload file securely to temp directory
            $path = $fileUploadService->uploadSecure($file, 'temp', $allowedMimes, 51200); // 50MB max

            // Extract filename from path
            $name = basename($path);

            // FIX: sesuaikan dengan disk 'public' yang dipakai FileUploadService
            $path = storage_path("app/public/temp/{$name}");
            $extract = storage_path('app/public/bantuan/');

            // Ekstrak file
            $zip = new ZipArchive;
            $openResult = $zip->open($path);

            if ($openResult !== true) {
                throw new \RuntimeException(
                    "Gagal membuka file zip (kode error ZipArchive: {$openResult}). Path: {$path}"
                );
            }

            // FIX: cari nama file data yang sebenarnya di dalam zip
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entryName = $zip->getNameIndex($i);
                $extension = strtolower(pathinfo($entryName, PATHINFO_EXTENSION));

                if (in_array($extension, self::SUPPORTED_EXTENSIONS, true)) {
                    $fileName = $entryName;
                    break;
                }
            }

            $zip->extractTo($extract);
            $zip->close();

            if (! $fileName) {
                throw new \RuntimeException('File data (csv/xlsx) tidak ditemukan di dalam zip yang diupload.');
            }

            // Proses impor data
            (new SinkronPesertaBantuan)->queue($extract.$fileName);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'status' => 'danger',
                'message' => $e->getMessage(),
            ]);
        } finally {
            Storage::deleteDirectory('temp');
        }

        if ($fileName) {
            Storage::disk('public')->delete('bantuan/'.$fileName);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data Bantuan Sedang di Sinkronkan',
        ]);
    }
}
