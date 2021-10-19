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
use App\Models\FormDokumen;
use function asset;
use function back;
use function base_path;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use function compact;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function redirect;
use function request;
use function route;
use function unlink;
use function view;
use Yajra\DataTables\DataTables;

class FormDokumenController extends Controller
{
    public function index()
    {
        $page_title       = 'Form Dokumen';
        $page_description = 'Upload Form Dokumen';

        return view('informasi.form_dokumen.index', compact('page_title', 'page_description'));
    }

    public function getDataDokumen()
    {
        $query = DB::table('das_form_dokumen')->selectRaw('id, nama_dokumen, file_dokumen');
        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                // $show_url = route('informasi.form-dokumen.show', $row->id);
                $edit_url     = route('informasi.form-dokumen.edit', $row->id);
                $delete_url   = route('informasi.form-dokumen.destroy', $row->id);
                $download_url = asset($row->file_dokumen);

                $data['download_url'] = $download_url;
                // $data['show_url'] = $show_url;
                if (! Sentinel::guest()) {
                    $data['edit_url']   = $edit_url;
                    $data['delete_url'] = $delete_url;
                }

                return view('forms.action', $data);
            })->make();
    }

    public function create()
    {
        $page_title       = 'Tambah';
        $page_description = 'Upload Form Dokumen Baru';

        return view('informasi.form_dokumen.create', compact('page_title', 'page_description'));
    }

    public function store(Request $request)
    {
        request()->validate([
            'nama_dokumen' => 'required',
            'file_dokumen' => 'required|mimes:jpeg,png,jpg,gif,svg,xlsx,xls,doc,docx,pdf,ppt,pptx|max:2048',
        ]);
        $dokumen = new FormDokumen($request->input());

        if ($request->hasFile('file_dokumen')) {
            $file     = $request->file('file_dokumen');
            $fileName = $file->getClientOriginalName();
            $path     = "storage/form_dokumen/";
            $request->file('file_dokumen')->move($path, $fileName);
            $dokumen->file_dokumen = $path . $fileName;
        }

        $dokumen->save();

        return redirect()->route('informasi.form-dokumen.index')->with('success', 'Form Dokumen berhasil ditambah!');
    }

    public function edit($id)
    {
        $dokumen          = FormDokumen::findOrFail($id);
        $page_title       = 'Edit';
        $page_description = 'Edit Form Dokumen ' . $dokumen->nama_dokumen;

        return view('informasi.form_dokumen.edit', compact('page_title', 'page_description', 'dokumen'));
    }

    public function update(Request $request, $id)
    {
        try {
            $dokumen = FormDokumen::findOrFail($id);
            $dokumen->fill($request->all());

            request()->validate([
                'nama_dokumen' => 'required',
                'file_dokumen' => 'required|mimes:jpeg,png,jpg,gif,svg,xlsx,xls,doc,docx,pdf,ppt,pptx|max:2048',
            ]);

            if ($request->hasFile('file_dokumen')) {
                $file     = $request->file('file_dokumen');
                $fileName = $file->getClientOriginalName();
                $path     = "storage/form_dokumen/";
                $request->file('file_dokumen')->move($path, $fileName);
                $dokumen->file_dokumen = $path . $fileName;
            }

            $dokumen->save();

            return redirect()->route('informasi.form-dokumen.index')->with('success', 'Form Dokumen berhasil disimpan!');
        } catch (Exception $e) {
            return back()->with('error', 'Form Dokumen gagal disimpan!' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $dokumen = FormDokumen::findOrFail($id);
            unlink(base_path('public/' . $dokumen->file_dokumen));
            $dokumen->delete();
            return redirect()->route('informasi.form-dokumen.index')->with('success', 'Form Dokumen berhasil dihapus!');
        } catch (Exception $e) {
            return redirect()->route('informasi.form-dokumen.index')->with('error', 'Form Dokumen gagal dihapus!');
        }
    }
}
