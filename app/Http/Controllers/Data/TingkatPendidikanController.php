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
use App\Http\Requests\TingkatPendidikanRequest;
use App\Imports\ImporTingkatPendidikan;
use App\Models\TingkatPendidikan;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;

class TingkatPendidikanController extends Controller
{
    public function index()
    {
        $page_title       = 'Tingkat Pendidikan';
        $page_description = 'Daftar Tingkat Pendidikan';

        return view('data.tingkat_pendidikan.index', compact('page_title', 'page_description'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getData()
    {
        return DataTables::of(TingkatPendidikan::with(['desa'])->get())
            ->addColumn('aksi', function ($row) {
                $data['delete_url'] = route('data.tingkat-pendidikan.destroy', $row->id);

                return view('forms.aksi', $data);
            })
            ->rawColumns(['aksi'])->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function import()
    {
        $page_title       = 'Tingkat Pendidikan';
        $page_description = 'Import Tingkat Pendidikan';
        $years_list       = years_list();
        $months_list      = months_list();

        return view('data.tingkat_pendidikan.import', compact('page_title', 'page_description', 'years_list', 'months_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function do_import(TingkatPendidikanRequest $request)
    {
        try {
            (new ImporTingkatPendidikan($request->only(['desa_id', 'tahun', 'semester'])))
                ->queue($request->file('file'));
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Data gagal diimpor');
        }

        return redirect()->route('data.tingkat-pendidikan.index')->with('success', 'Data berhasil diimpor!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            TingkatPendidikan::findOrFail($id)->delete();
        } catch (\Exception $e) {
            report($e);
            return redirect()->route('data.tingkat-pendidikan.index')->with('error', 'Data gagal dihapus!');
        }

        return redirect()->route('data.tingkat-pendidikan.index')->with('success', 'Data berhasil dihapus!');
    }
}
