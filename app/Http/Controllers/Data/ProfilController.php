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

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfilRequest;
use App\Models\DataUmum;
use App\Models\Profil;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class ProfilController extends Controller
{
    /**
     * Clear cache untuk data profil kecamatan
     *
     * @return void
     */
    private function clearProfilCache()
    {
        Cache::forget('profil');
        Cache::forget('setting');

        // Clear cache dengan tags jika didukung
        try {
            Cache::tags(['profil', 'kecamatan', 'frontend'])->flush();
        } catch (\Exception $e) {
            // Cache tags mungkin tidak didukung pada semua driver cache
            \Log::info('Cache tags not supported by current cache driver');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $profil = $this->profil;
        $page_title = 'Profil';
        $page_description = 'Data Profil';
        $status_pantau = checkWebsiteAccessibility(config('app.server_pantau')) ? 1 : 0;
        $adaDesa = DataUmum::where('profil_id', $profil->id)->exists() && $profil->provinsi_id != null;

        return view('data.profil.edit', compact('page_title', 'page_description', 'profil', 'status_pantau', 'adaDesa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(ProfilRequest $request, $id)
    {
        try {
            $profil = Profil::findOrFail($id);
            $profil->fill($request->all());

            $dataumum = DataUmum::where('profil_id', $id)->first();

            if ($request->file('file_struktur_organisasi') == '') {
                $profil->file_struktur_organisasi = $profil->file_struktur_organisasi;
            } else {
                $file = $request->file('file_struktur_organisasi');
                $fileName = $file->getClientOriginalName();
                $request->file('file_struktur_organisasi')->move('storage/profil/struktur_organisasi/', $fileName);
                $profil->file_struktur_organisasi = 'storage/profil/struktur_organisasi/' . $fileName;
            }

            if ($request->file('file_logo') == '') {
                $profil->file_logo = $profil->file_logo;
            } else {
                $fileLogo = $request->file('file_logo');
                $fileLogoName = $fileLogo->getClientOriginalName();
                $request->file('file_logo')->move('storage/profil/file_logo/', $fileLogoName);
                $profil->file_logo = 'storage/profil/file_logo/' . $fileLogoName;
            }

            $profil->update();
            $dataumum->update();

            // Clear cache setelah update data kecamatan
            $this->clearProfilCache();
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Update Profil gagal!');
        }

        return redirect()->route('data.profil.success', $profil->dataumum->id)->with('success', 'Update Profil sukses!');
    }

    /**
     * Redirect to edit Data Umum if success
     *
     * @param  int  $id
     * @return Response
     */
    public function success($id)
    {
        $page_title = 'Profil';
        $page_description = 'Konfirmasi?';

        return view('data.profil.save_success', compact('id', 'page_title', 'page_description'));
    }
}
