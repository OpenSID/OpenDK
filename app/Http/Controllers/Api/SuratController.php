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

use App\Enums\StatusVerifikasiSurat;
use App\Http\Controllers\Controller;
use App\Http\Resources\SuratResource;
use App\Models\DataDesa;
use App\Models\Penduduk;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SuratController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index(Request $request)
    {
        if (! $this->settings['tte']) {
            return response()->json('Kecamatan belum mengaktifkan modul TTE', 400);
        }

        $validator = Validator::make($request->all(), ['desa_id' => 'required']);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! in_array($request->desa_id, Arr::flatten(DataDesa::pluck('desa_id')))) {
            Log::debug("Kode desa {$request->desa_id} tidak terdaftar di kecamatan");

            return response()->json("Kode desa {$request->desa_id} tidak terdaftar di kecamatan", 400);
        }

        $surat = Surat::where('desa_id', $request->desa_id)->get(['nomor', 'file', 'nama', 'nik', 'pengurus_id', 'log_verifikasi', 'keterangan']);

        return new SuratResource(true, 'Daftar Surat', $surat);
    }

    /**
     * store
     *
     * @param  mixed  $request
     * @return void
     */
    public function store(Request $request)
    {
        if (! $this->settings['tte']) {
            return response()->json('Kecamatan belum mengaktifkan modul TTE', 400);
        }

        $validator = Validator::make($request->all(), [
            'desa_id' => 'required',
            'nik' => 'required|integer|digits:16',
            'tanggal' => 'required|date',
            'nomor' => 'required|string|unique:das_log_surat,nomor',
            'nama' => 'required|string',
            'file' => 'required|file|mimes:pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! in_array($request->desa_id, Arr::flatten(DataDesa::pluck('desa_id')))) {
            Log::debug("Kode desa {$request->desa_id} tidak terdaftar di kecamatan");

            return response()->json("Kode desa {$request->desa_id} tidak terdaftar di kecamatan", 400);
        }

        if (! Penduduk::where('nik', $request->nik)->exists()) {
            Log::debug("Penduduk dengan NIK {$request->nik} tidak terdaftar di kecamatan");

            return response()->json("Penduduk dengan NIK {$request->nik} tidak terdaftar di kecamatan", 400);
        }

        $file = $request->file('file');
        $original_name = strtolower(trim($file->getClientOriginalName()));
        $file_name = time().'_'.$original_name;
        Storage::putFileAs('public/surat', $file, $file_name);

        $this->settings['pemeriksaan_camat'] ? StatusVerifikasiSurat::MenungguVerifikasi : StatusVerifikasiSurat::TidakAktif;

        $surat = Surat::create([
            'desa_id' => $request->desa_id,
            'nik' => $request->nik,
            'pengurus_id' => $this->akun_camat->id,
            'tanggal' => $request->tanggal,
            'nomor' => $request->nomor,
            'nama' => $request->nama,
            'file' => $file_name,
            'verifikasi_camat' => StatusVerifikasiSurat::MenungguVerifikasi,
            'verifikasi_sekretaris' => $this->settings['pemeriksaan_sekretaris'] ? StatusVerifikasiSurat::MenungguVerifikasi : StatusVerifikasiSurat::TidakAktif,
        ]);

        return new SuratResource(true, 'Surat Berhasil Dikirim!', $surat);
    }

    /**
     * index
     *
     * @return void
     */
    public function download(Request $request)
    {
        if (! $this->settings['tte']) {
            return response()->json('Kecamatan belum mengaktifkan modul TTE', 400);
        }

        $validator = Validator::make($request->all(), [
            'desa_id' => 'required',
            'nomor' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! in_array($request->desa_id, Arr::flatten(DataDesa::pluck('desa_id')))) {
            Log::debug("Kode desa {$request->desa_id} tidak terdaftar di kecamatan");

            return response()->json("Kode desa {$request->desa_id} tidak terdaftar di kecamatan", 400);
        }

        $surat = Surat::where('desa_id', $request->desa_id)->where('nomor', $request->nomor)->firstOrFail();

        $file = public_path("storage/surat/{$surat->file}");

        return Response::make(file_get_contents($file), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$surat->file.'"',
        ]);
    }
}
