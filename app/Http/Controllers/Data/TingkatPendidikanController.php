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
use App\Imports\ImporTingkatPendidikan;
use App\Models\TingkatPendidikan;
use Exception;
use Illuminate\Http\Request;
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
        return DataTables::of(TingkatPendidikan::with(['desa']))
            ->addColumn('aksi', function ($row) {
                $data['edit_url']   = route('data.tingkat-pendidikan.edit', $row->id);
                $data['delete_url'] = route('data.tingkat-pendidikan.destroy', $row->id);

                return view('forms.action', $data);
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
    public function do_import(Request $request)
    {
        $this->validate($request, [
            'desa_id'  => 'required|unique:das_tingkat_pendidikan,desa_id',
            'file'     => 'required|file|mimes:xls,xlsx,csv|max:5120',
            'tahun'    => 'required|unique:das_tingkat_pendidikan',
            'semester' => 'required|unique:das_tingkat_pendidikan',
        ]);

        try {
            (new ImporTingkatPendidikan($request->only(['desa_id', 'tahun', 'semester'])))
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
        $pendidikan       = TingkatPendidikan::with(['desa'])->findOrFail($id);
        $page_title       = 'Tingkat Pendidikan';
        $page_description = 'Ubah Tingkat Pendidikan : Desa ' .  $pendidikan->desa->nama;

        return view('data.tingkat_pendidikan.edit', compact('page_title', 'page_description', 'pendidikan'));
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
            'tidak_tamat_sekolah'     => 'required',
            'tamat_sd'                => 'required',
            'tamat_smp'               => 'required',
            'tamat_sma'               => 'required',
            'tamat_diploma_sederajat' => 'required',
            'semester'                => 'required',
            'tahun'                   => 'required',
        ]);

        try {
            TingkatPendidikan::findOrFail($id)->update($request->all());
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Data gagal diubah!' . $e->getMessage());
        }

        return redirect()->route('data.tingkat-pendidikan.index')->with('success', 'Data berhasil diubah!');
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
        } catch (Exception $e) {
            return redirect()->route('data.tingkat-pendidikan.index')->with('error', 'Data gagal dihapus!');
        }

        return redirect()->route('data.tingkat-pendidikan.index')->with('success', 'Data sukses dihapus!');
    }
}
