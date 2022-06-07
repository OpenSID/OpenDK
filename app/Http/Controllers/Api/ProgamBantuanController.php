<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProgramBantuanRequest;
use App\Imports\SinkronBantuan;
use App\Imports\SinkronPesertaBantuan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class ProgamBantuanController extends Controller
{
    public function store(ProgramBantuanRequest $request)
    {
        try {
            // Upload file zip temporary.
            $file = $request->file('file');
            $file->storeAs('temp', $name = $file->getClientOriginalName());

            // Temporary path file
            $path = storage_path("app/temp/{$name}");
            $extract = storage_path('app/public/bantuan/');

            // Ekstrak file
            $zip = new ZipArchive();
            $zip->open($path);
            $zip->extractTo($extract);
            $zip->close();

            (new SinkronBantuan())
                ->queue($extract . $csvName = Str::replaceLast('zip', 'csv', $name));
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                "status" => "danger",
                "message" => $e->getMessage(),
            ]);
        }

        // Hapus folder temp ketika sudah selesai
        Storage::deleteDirectory('temp');
        // Hapus file excell temp ketika sudah selesai
        Storage::disk('public')->delete('bantuan' . $csvName);

        return response()->json([
            "message" => "Data Bantuan Sedang di Sinkronkan",
            "status" => "success"
        ]);
    }

    public function storePeserta(ProgramBantuanRequest $request)
    {
        try {
            // Upload file zip temporary.
            $file = $request->file('file');
            $file->storeAs('temp', $name = $file->getClientOriginalName());
            // Temporary path file
            $path = storage_path("app/temp/{$name}");
            $extract = storage_path('app/public/bantuan/');
            // Ekstrak file
            $zip = new ZipArchive();
            $zip->open($path);
            $zip->extractTo($extract);
            $zip->close();
            // Proses impor excell
            (new SinkronPesertaBantuan())
                ->queue($extract . $csvName = Str::replaceLast('zip', 'csv', $name));
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                "status" => "danger",
                "message" => $e->getMessage(),
            ]);
        }
        // Hapus folder temp ketika sudah selesai
        Storage::deleteDirectory('temp');
        // Hapus file excell temp ketika sudah selesai
        Storage::disk('public')->delete('bantuan/' . $csvName);
        return response()->json([
            "status" => "success",
            "message" => "Data Bantuan Sedang di Sinkronkan",
        ]);
    }
}
