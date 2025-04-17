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
use App\Http\Requests\JenisDocumentRequest;
use App\Models\JenisDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class JenisDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = JenisDocument::query(); // ✅ gunakan query builder
    
                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('aksi', function ($row) {
                        $data = [];
                        if (! auth()->guest()) {
                            $data['edit_url'] = route('data.jenis-document.edit', $row->id); // ✅ fix param
                            $data['delete_url'] = route('data.jenis-document.destroy', $row->id);
                        }
                        return view('forms.aksi', $data);
                    })
                    ->filter(function ($query) use ($request) {
                        if ($request->has('search') && $request->search['value'] != '') {
                            $search = $request->search['value'];
                            $query->where('nama', 'like', "%{$search}%");
                        }
                    })
                    ->rawColumns(['aksi']) // ✅ pastikan kolom sesuai
                    ->make(true);
            }

            $page_title = 'Jenis Document';
            $page_description = 'Daftar Jenis Document';
            $jenisDocument = JenisDocument::all();
            if ($jenisDocument) {
                return view('data.jenis_document.index', compact('page_title', 'page_description'));
            }else{
                return back()->with('success', 'Gagal simpan jenis document');
            }
        } catch (\Exception $e) {
            report($e);
            Log::channel('daily')->error('index di JenisDocumentController: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);            
            return back()->withInput()->with('error', 'Gagal memuat halaman jenis document!');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Jenis Document';
        $page_description = 'Tambah Jenis Document';
        return view('data.jenis_document.create', compact('page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JenisDocumentRequest $request)
    {
        try {
            $page_title = 'Jenis Document';
            $page_description = 'Daftar Jenis Document';
            $jenisDocument = JenisDocument::updateOrCreate(['id' => $request->post('id')], $request->all());
            if ($jenisDocument) {
                return view('data.jenis_document.index', compact('page_title', 'page_description'))->with('success', 'Berhasil simpan jenis document');
            }else{
                return back()->with('success', 'Gagal simpan jenis document');
            }
        } catch (\Exception $e) {
            report($e);
            Log::channel('daily')->error('store di JenisDocumentController: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);            
            return back()->withInput()->with('error', 'Gagal menyimpan jenis document!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $page_title = 'Jenis Document';
            $page_description = 'Edit Jenis Document';
            $jenis_document = JenisDocument::find($id);
            if ($jenis_document) {
                return view('data.jenis_document.create', compact('page_title', 'page_description', 'jenis_document'))->with('success', 'Berhasil show jenis document');
            }else{
                return redirect()->back()->with('success', 'Gagal show jenis document');
            }
        } catch (\Exception $e) {
            report($e);
            Log::channel('daily')->error('show di JenisDocumentController: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);            
            return back()->withInput()->with('error', 'Gagal show jenis document!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $jenisDocument = JenisDocument::find($id)->delete();
            if ($jenisDocument) {
                return redirect()->back()->with('success', 'Berhasil hapus jenis document');
            }else{
                return back()->with('success', 'Gagal hapus jenis document');
            }
        } catch (\Exception $e) {
            report($e);
            Log::channel('daily')->error('destroy di JenisDocumentController: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);            
            return back()->withInput()->with('error', 'Gagal hapus jenis document!');
        }
    }
}
