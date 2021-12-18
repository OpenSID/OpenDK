<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @package	    OpenDK
 * @author	    Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2017 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Imports\ImporAKIAKB;
use App\Models\AkiAkb;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Yajra\DataTables\Facades\DataTables;

class AKIAKBController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title       = 'AKI & AKB';
        $page_description = 'Daftar Kematian Ibu & Bayi';

        return view('data.aki_akb.index', compact('page_title', 'page_description'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getDataAKIAKB()
    {
        return DataTables::of(AkiAkb::with(['desa']))
            ->addColumn('aksi', function ($row) {
                $data['edit_url']   = route('data.aki-akb.edit', $row->id);
                $data['delete_url'] = route('data.aki-akb.destroy', $row->id);

                return view('forms.aksi', $data);
            })
            ->editColumn('bulan', function ($row) {
                return months_list()[$row->bulan];
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
        $page_title       = 'AKI & AKB';
        $page_description = 'Import AKI & AKB';
        $years_list       = years_list();
        $months_list      = months_list();

        return view('data.aki_akb.import', compact('page_title', 'page_description', 'years_list', 'months_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function do_import(Request $request)
    {
        $this->validate($request, [
            'file'  => 'required|file|mimes:xls,xlsx,csv|max:5120',
            'bulan' => 'required|unique:das_akib',
            'tahun' => 'required|unique:das_akib',
        ]);

        try {
            (new ImporAKIAKB($request->only(['bulan', 'tahun'])))
                ->queue($request->file('file'));
        } catch (Exception $e) {
            return back()->with('error', 'Import data gagal. ' . $e->getMessage());
        }

        return back()->with('success', 'Import data sukses.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $akib             = AkiAkb::findOrFail($id);
        $page_title       = 'AKI & AKB';
        $page_description = 'Ubah AKI & AKB : ' . $akib->id;

        return view('data.aki_akb.edit', compact('page_title', 'page_description', 'akib'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        request()->validate([
            'aki' => 'required',
            'akb' => 'required',
        ]);

        try {
            AkiAkb::findOrFail($id)->update($request->all());
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Data gagal disimpan!');
        }

        return redirect()->route('data.aki-akb.index')->with('success', 'Data berhasil disimpan!');
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
            AkiAkb::findOrFail($id)->delete();
        } catch (Exception $e) {
            return redirect()->route('data.aki-akb.index')->with('error', 'Data gagal dihapus!');
        }

        return redirect()->route('data.aki-akb.index')->with('success', 'Data sukses dihapus!');
    }
}
