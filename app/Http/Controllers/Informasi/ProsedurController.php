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

namespace App\Http\Controllers\Informasi;

use App\Http\Controllers\Controller;
use App\Models\Prosedur;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;

class ProsedurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title       = 'Prosedur';
        $page_description = 'Daftar Prosedur';
        $prosedurs        = Prosedur::all();

        return view('informasi.prosedur.index', compact('page_title', 'page_description', 'prosedurs'));
    }

    /**
     * Get datatable
     */
    public function getDataProsedur()
    {
        return DataTables::of(Prosedur::select('id', 'judul_prosedur'))
            ->addColumn('aksi', function ($row) {
                $data['show_url'] = route('informasi.prosedur.show', $row->id);

                if (! Sentinel::guest()) {
                    $data['edit_url']   = route('informasi.prosedur.edit', $row->id);
                    $data['delete_url'] = route('informasi.prosedur.destroy', $row->id);
                }

                $data['download_url'] = route('informasi.prosedur.download', $row->id);

                return view('forms.aksi', $data);
            })
            ->editColumn('judul_prosedur', function ($row) {
                return $row->judul_prosedur;
            })->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title       = 'Prosedur';
        $page_description = 'Tambah Prosedur';

        return view('informasi.prosedur.create', compact('page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'judul_prosedur' => 'required',
            'file_prosedur'  => 'required|file|mimes:jpg,jpeg,png,gif,pdf|max:2048',
        ]);

        try {
            $prosedur = new Prosedur($request->input());

            if ($request->hasFile('file_prosedur')) {
                $file     = $request->file('file_prosedur');
                $fileName = $file->getClientOriginalName();
                $path     = "storage/regulasi/";
                $request->file('file_prosedur')->move($path, $fileName);
                $prosedur->file_prosedur = $path . $fileName;
                $prosedur->mime_type     = $file->getClientOriginalExtension();
            }

            $prosedur->save();
        } catch (\Exception $e) {
            return back()->with('error', 'Prosedur gagal disimpan!' . $e->getMessage());
        }

        return redirect()->route('informasi.prosedur.index')->with('success', 'Prosedur berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $prosedur         = Prosedur::findOrFail($id);
        $page_title       = 'Prosedur';
        $page_description = 'Detail Prosedur : ' . $prosedur->judul_prosedur;

        return view('informasi.prosedur.show', compact('page_title', 'prosedur', 'page_description'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $prosedur         = Prosedur::findOrFail($id);
        $page_title       = 'Prosedur';
        $page_description = 'Ubah Prosedur : ' . $prosedur->judul_prosedur;

        return view('informasi.prosedur.edit', compact('page_title', 'page_description', 'prosedur'));
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
            'judul_prosedur' => 'required',
            'file_prosedur'  => 'file|mimes:jpg,jpeg,png,gif,pdf|max:2048',
        ]);

        try {
            $prosedur = Prosedur::findOrFail($id);
            $prosedur->fill($request->all());

            if ($request->hasFile('file_prosedur')) {
                $file     = $request->file('file_prosedur');
                $fileName = $file->getClientOriginalName();
                $path     = "storage/regulasi/";
                $request->file('file_prosedur')->move($path, $fileName);
                $prosedur->file_prosedur = $path . $fileName;
                $prosedur->mime_type     = $file->getClientOriginalExtension();
            }

            $prosedur->save();
        } catch (\Exception $e) {
            return back()->with('error', 'Prosedur gagal disimpan!' . $e->getMessage());
        }

        return redirect()->route('informasi.prosedur.index')->with('success', 'Prosedur berhasil disimpan!');
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
            Prosedur::findOrFail($id)->delete();
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Prosedur gagal dihapus!');
        }

        return redirect()->route('setting.komplain-kategori.index')->with('success', 'Prosedur berhasil dihapus!');
    }

    /**
     * Download the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function download($id)
    {
        try {

            $getFile = Prosedur::findOrFail($id);

            return response()->download($getFile->file_prosedur);

        } catch (\Exception $e) {
            return back()->with('error', 'Dokumen tidak ditemukan');
        }
    }
}
