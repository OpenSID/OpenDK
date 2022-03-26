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

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\DataUmum;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DataUmumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data_umum        = DataUmum::first();
        $luas_wilayah     = $data_umum['luas_wilayah_value'];
        $page_title       = 'Data Umum';
        $page_description = 'Ubah Data Umum';

        return view('data.data_umum.edit', compact('page_title', 'page_description', 'data_umum', 'luas_wilayah'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        request()->validate([
            'tipologi'               => 'required',
            'sumber_luas_wilayah'    => 'required',
            'luas_wilayah'           => 'required',
            'bts_wil_utara'          => 'required',
            'bts_wil_timur'          => 'required',
            'bts_wil_selatan'        => 'required',
            'bts_wil_barat'          => 'required',
            'jml_puskesmas'          => 'required',
            'jml_puskesmas_pembantu' => 'required',
            'jml_posyandu'           => 'required',
            'jml_pondok_bersalin'    => 'required',
            'jml_paud'               => 'required',
            'jml_sd'                 => 'required',
            'jml_smp'                => 'required',
            'jml_sma'                => 'required',
            'jml_masjid_besar'       => 'required',
            'jml_mushola'            => 'required',
            'jml_gereja'             => 'required',
            'jml_pasar'              => 'required',
            'jml_balai_pertemuan'    => 'required',
        ]);

        try {
            $data = ($request->sumber_luas_wilayah == 1) ? $request->all() : $request->except('luas_wilayah');
            DataUmum::findOrFail($id)->update($data);
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Update Data Umum gagal!');
        }

        return redirect()->route('data.data-umum.index')->with('success', 'Update Data Umum sukses!');
    }

    public function getDataUmumAjax(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(['data '=> DataUmum::first()]);
        }
    }
}
