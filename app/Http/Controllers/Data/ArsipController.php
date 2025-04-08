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

// use App\Enums\Status;
// use App\Models\Agama;
// use App\Models\Jabatan;
// use App\Models\Pengurus;
// use App\Enums\JenisJabatan;
// use App\Models\PendidikanKK;
use Illuminate\Http\Request;
// use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
// use App\Traits\HandlesFileUpload;
use App\Http\Controllers\Controller;
// use App\Http\Requests\PengurusRequest;
use App\Models\Document;
use App\Models\Penduduk;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArsipController extends Controller
{
    // use HandlesFileUpload;

    public function arsip()
    {
        $page_title = 'Rekam Surat Perseorangan';
        $page_description = 'Daftar Data Arsip';
        $penduduk = Penduduk::with(['desa:id,profil_id,desa_id,nama,sebutan_desa'])
        ->join('ref_warganegara', 'ref_warganegara.id', '=', 'das_penduduk.warga_negara_id')
        ->join('das_data_desa', 'das_data_desa.desa_id', '=', 'das_penduduk.desa_id')
        ->join('ref_agama', 'ref_agama.id', '=', 'das_penduduk.agama_id')
        ->join('das_keluarga', 'das_keluarga.no_kk', '=', 'das_penduduk.no_kk')
        ->join('ref_pendidikan_kk', 'ref_pendidikan_kk.id', '=', 'das_penduduk.pendidikan_id')
        ->get([
            'das_penduduk.id',
            'das_penduduk.nik',
            'das_penduduk.nama',
            'ref_warganegara.nama as warga_negara',
            'ref_agama.nama as agama',
            'tempat_lahir',
            'tanggal_lahir',
            'das_penduduk.alamat',
            'das_penduduk.dusun',
            'das_penduduk.rt',
            'das_penduduk.rw',
            'das_penduduk.desa_id',
            'warga_negara_id',
            'ref_pendidikan_kk.nama as pendidikan'
        ]);
        return view('data.pengurus.arsip', compact('page_title', 'page_description'), [
            'penduduk' => $penduduk,
        ]);
    }

    public function penduduk_arsip($id)
    {
        $penduduk = Penduduk::with(['desa:id,profil_id,desa_id,nama,sebutan_desa'])
        ->join('ref_warganegara', 'ref_warganegara.id', '=', 'das_penduduk.warga_negara_id')
        ->join('das_data_desa', 'das_data_desa.desa_id', '=', 'das_penduduk.desa_id')
        ->join('ref_agama', 'ref_agama.id', '=', 'das_penduduk.agama_id')
        ->join('das_keluarga', 'das_keluarga.no_kk', '=', 'das_penduduk.no_kk')
        ->join('ref_pendidikan_kk', 'ref_pendidikan_kk.id', '=', 'das_penduduk.pendidikan_id')
        ->where('das_penduduk.id', $id)
        ->first([
            'das_penduduk.id',
            'das_penduduk.nik',
            'das_penduduk.nama',
            'ref_warganegara.nama as warga_negara',
            'ref_agama.nama as agama',
            'tempat_lahir',
            'tanggal_lahir',
            'das_penduduk.alamat',
            'das_penduduk.dusun',
            'das_penduduk.rt',
            'das_penduduk.rw',
            'das_penduduk.desa_id',
            'warga_negara_id',
            'ref_pendidikan_kk.nama as pendidikan'
        ]);
        return response()->json($penduduk);
    }

    public function create_arsip($id)
    {
        $page_title = 'Daftar Dokumen';
        $page_description = 'Tambah Data';
        // return $id;
        $data_penduduk = Penduduk::find($id);
        // $penduduk = Penduduk::with(['desa:id,profil_id,desa_id,nama,sebutan_desa'])
        // ->join('ref_warganegara', 'ref_warganegara.id', '=', 'das_penduduk.warga_negara_id')
        // ->join('das_data_desa', 'das_data_desa.desa_id', '=', 'das_penduduk.desa_id')
        // ->join('ref_agama', 'ref_agama.id', '=', 'das_penduduk.agama_id')
        // ->join('das_keluarga', 'das_keluarga.no_kk', '=', 'das_penduduk.no_kk')
        // ->join('ref_pendidikan_kk', 'ref_pendidikan_kk.id', '=', 'das_penduduk.pendidikan_id')
        // ->leftJoin('documents', 'documents.das_penduduk_id', '=', 'das_penduduk.id')
        // ->where('documents.id', $id)    
        // ->first([
        //     'das_penduduk.id as das_penduduk_id',
        //     'das_penduduk.nik',
        //     'das_penduduk.nama',
        //     'ref_warganegara.nama as warga_negara',
        //     'ref_agama.nama as agama',
        //     'tempat_lahir',
        //     'tanggal_lahir',
        //     'das_penduduk.alamat',
        //     'das_penduduk.dusun',
        //     'das_penduduk.rt',
        //     'das_penduduk.rw',
        //     'das_penduduk.desa_id',
        //     'warga_negara_id',
        //     'ref_pendidikan_kk.nama as pendidikan',
        //     'documents.id as document_id'
        // ]);
        $document = Document::where('das_penduduk_id', $id)->first();
        return view('data.pengurus.create_arsip', compact('page_title', 'page_description', 'document', 'data_penduduk'));
    }

    public function edit_arsip($id)
    {
        $page_title = 'Daftar Dokumen';
        $page_description = 'Edit Data';
        // return $id;
        $data_penduduk = Penduduk::find($id);
        $penduduk = Penduduk::with(['desa:id,profil_id,desa_id,nama,sebutan_desa'])
        ->join('ref_warganegara', 'ref_warganegara.id', '=', 'das_penduduk.warga_negara_id')
        ->join('das_data_desa', 'das_data_desa.desa_id', '=', 'das_penduduk.desa_id')
        ->join('ref_agama', 'ref_agama.id', '=', 'das_penduduk.agama_id')
        ->join('das_keluarga', 'das_keluarga.no_kk', '=', 'das_penduduk.no_kk')
        ->join('ref_pendidikan_kk', 'ref_pendidikan_kk.id', '=', 'das_penduduk.pendidikan_id')
        ->join('documents', 'documents.das_penduduk_id', '=', 'das_penduduk.id')
        ->where('documents.id', $id)    
        ->first([
            // 'das_penduduk.id as das_penduduk_id',
            'das_penduduk.nik',
            'das_penduduk.nama',
            'ref_warganegara.nama as warga_negara',
            'ref_agama.nama as agama',
            'tempat_lahir',
            'tanggal_lahir',
            'das_penduduk.alamat',
            'das_penduduk.dusun',
            'das_penduduk.rt',
            'das_penduduk.rw',
            'das_penduduk.desa_id',
            'warga_negara_id',
            'ref_pendidikan_kk.nama as pendidikan',
            'documents.*'
        ]);
        $document = Document::where('das_penduduk_id', $id)->first();
        return view('data.pengurus.edit_arsip', compact('page_title', 'page_description', 'penduduk', 'document', 'data_penduduk'));
    }

    public function store_arsip(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'jenis_document' => 'required|integer',
                'jenis_document' => 'required|string|max:255',
                'das_penduduk_id' => 'required|string|max:255',
                'keterangan' => 'required|string|max:100000',
                'document' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:80240',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            
            // $input = $request->all();
            // $this->handleFileUpload($request, $input, 'document', 'pengurus/document', true);
            $data_document = Document::orderBy('id', 'DESC')->first();
            $no_urut = $data_document->no_urut ?? 0; 

            

            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('documents', $fileName, 'public');
            }else{
                if ($request->post('document_id')) {
                    $data_documents = Document::find($request->post('document_id'));
                    if ($data_documents) {
                        $path_document = $data_documents->path_document;
                        $path = $path_document;
                    }
                }
            }
            
            $data = [
                'das_penduduk_id' => $request->post('das_penduduk_id'),
                'judul_document' => $request->post('judul_document'),
                'path_document' => $path,
                'user_id' => Auth::user()->id,
                'kode_surat' => "SK-" . $no_urut + 1,
                'no_urut' => $no_urut + 1,
                'jenis_surat' => $request->post('jenis_document'),
                'keterangan' => $request->post('keterangan'),
                'ditandatangani' => Auth::user()->name,
                'tanggal' => now(),
            ];

            $insert = Document::updateOrCreate([
                'id' => $request->post('document_id')
            ], $data);
            if ($insert) {
                return redirect()->route('data.pengurus.arsip')->with('success', 'Berhasil Simpan Document');
            }else{
                return back()->with('success', 'Gagal Simpan Document');
            }
        } catch (\Exception $e) {
            report($e);
            Log::channel('daily')->error('Error di PengurusController: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);            
            return back()->withInput()->with('error', 'Documet gagal ditambah!');
        }
    }

    public function document(Request $request)
    {
        $document = Document::with('user:id,name,email');
        if ($request->ajax()) {

            return DataTables::of($document)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    if (! auth()->guest()) {
                        $data['edit_url'] = route('data.pengurus.edit.document', $row->id);
                        $data['delete_url'] = route('data.pengurus.delete.document', $row->id);
                    }

                    return view('forms.aksi', $data);
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && $request->search['value'] != '') {
                        $search = $request->search['value'];
                        $query->where('jenis_surat', 'like', "%{$search}%")
                              ->orWhere('keterangan', 'like', "%{$search}%")
                              ->orWhereHas('user', function ($q) use ($search) {
                                  $q->where('name', 'like', "%{$search}%");
                              });
                    }
                })
                ->rawColumns(['path_document', 'aksi']) // ⬅️ tambahkan ini!
                ->make(true);
        }
        return response()->json($document);
    }

    public function delete_document($id)
    {
        try {
            $document = Document::find($id);
            $path_document = $document->path_document ?? null;
            
            if ($path_document && Storage::disk('public')->exists($path_document)) {
                Storage::disk('public')->delete($path_document);
            }
            
            $document->delete();
            if ($document->delete()) {
                return back()->with('success', 'Document Berhasil Dihapus');
            }else{
                return back()->with('success', 'Document Gagal Dihapus');
            }
        } catch (Exception $e) {
            report($e);
            Log::channel('daily')->error('error deleteDocument di PengurusController: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);            
            return back()->withInput()->with('error', 'Gagal Delete Documet!');
        }
    }
}
