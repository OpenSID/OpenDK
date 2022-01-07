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
 * @package	    OpenDK
 * @author	    Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Imports\ImporFasilitasPaud;
use App\Models\FasilitasPAUD;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Yajra\DataTables\Facades\DataTables;

class FasilitasPaudController extends Controller
{
    public function index()
    {
        $page_title       = 'Fasilitas PAUD';
        $page_description = 'Data Fasilitas PAUD';
        return view('data.fasilitas_paud.index', compact('page_title', 'page_description'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getDataFasilitasPAUD()
    {
        return DataTables::of(FasilitasPAUD::with(['desa']))
            ->addColumn('aksi', function ($row) {
                $data['edit_url']   = route('data.fasilitas-paud.edit', $row->id);
                $data['delete_url'] = route('data.fasilitas-paud.destroy', $row->id);

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
        $page_title       = 'Import';
        $page_description = 'Import Data Fasilitas PAUD';
        $years_list       = years_list();
        $months_list      = months_list();

        return view('data.fasilitas_paud.import', compact('page_title', 'page_description', 'years_list', 'months_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function do_import(Request $request)
    {
        $this->validate($request, [
            'desa_id'  => 'required|unique:das_fasilitas_paud,desa_id',
            'file'     => 'required|file|mimes:xls,xlsx,csv|max:5120',
            'tahun'    => 'required|unique:das_fasilitas_paud',
            'semester' => 'required|unique:das_fasilitas_paud',
        ]);

        try {
            (new ImporFasilitasPaud($request->only(['desa_id', 'semester', 'tahun'])))
                ->queue($request->file('file'));
        } catch (Exception $e) {
            report($e);
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
        $fasilitas        = FasilitasPAUD::with(['desa'])->findOrFail($id);
        $page_title       = 'Fasilitas PAUD';
        $page_description = 'Ubah Fasilitas PAUD : Desa ' . $fasilitas->desa->nama;

        return view('data.fasilitas_paud.edit', compact('page_title', 'page_description', 'fasilitas'));
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
            'jumlaah_paud'       => 'required',
            'jumlah_guru_paud'  => 'required',
            'jumlah_siswa_paud' => 'required',
            'semester'          => 'required',
            'tahun'             => 'required',
        ]);

        try {
            FasilitasPAUD::findOrFail($id)->update($request->all());
        } catch (Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Data gagal diubah!' . $e->getMessage());
        }

        return redirect()->route('data.fasilitas-paud.index')->with('success', 'Data berhasil diubah!');
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
            FasilitasPAUD::findOrFail($id)->delete();
        } catch (Exception $e) {
            report($e);
            return redirect()->route('data.fasilitas-paud.index')->with('error', 'Data gagal dihapus!');
        }

        return redirect()->route('data.fasilitas-paud.index')->with('success', 'Data sukses dihapus!');
    }
}
