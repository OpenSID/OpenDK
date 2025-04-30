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

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Models\Document;
use App\Models\JenisDokumen;
use App\Models\Penduduk;
use App\Models\Pengurus;
use App\Traits\HandlesFileUpload;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ArsipController extends Controller
{
    use HandlesFileUpload;

    public function arsip(Request $request)
    {   
        try {
            $pengurus_id = $request->get('pengurus_id');

            if ($request->ajax()) {
                $document = Document::with('penduduk:id,nama', 'pengurus:id,nama,gelar_depan,gelar_belakang', 'jenis_documen:id,nama')->where('pengurus_id', $pengurus_id)->get();
                
                return DataTables::of($document)
                    ->addIndexColumn()
                    ->addColumn('aksi', function ($row) use ($pengurus_id){
                        if (! auth()->guest()) {
                            $data['edit_url'] = route('data.pengurus.edit.document', ['penduduk_id' => $row->id, 'pengurus_id' => $pengurus_id]);
                            $data['delete_url'] = route('data.pengurus.delete.document', $row->id);
                        }
    
                        return view('forms.aksi', $data);
                    })
                    ->editColumn('path_document', function ($row) {
                        if ($row->path_document && file_exists(public_path($row->path_document))) {
                            $url = route('data.pengurus.edit.download.arsip', $row->id);
                            return '<a href="' . $url . '" class="btn btn-sm btn-primary">Download</a>';
                        }
                        return '<span class="text-muted">Tidak ada file</span>';
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
                    ->rawColumns(['path_document', 'aksi'])
                    ->make(true);
            }
            $count_arsip = Document::where('pengurus_id', $pengurus_id)->count();
            $page_title = 'Arsip';
            $page_description = '';
            return view('data.pengurus.arsip', compact('page_title', 'page_description', 'pengurus_id', 'count_arsip'));
        } catch (\Exception $e) {
            report($e);
            Log::channel('daily')->error('arsip di ArsipController: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);            
            return back()->withInput()->with('error', 'Gagal memuat halaman arsip!');
        }
    }

    public function pendudukArsip($id)
    {
        try {
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
        } catch (\Exception $e) {
            report($e);
            Log::channel('daily')->error('penduduk_arsip di ArsipController: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);            
            return back()->withInput()->with('error', 'Gagal mendapatkan penduduk arsip!');
        }
        
    }

    public function create_arsip($penduduk_id, $pengurus_id)
    {
        try {
            $page_title = 'Daftar Dokumen';
            $page_description = 'Tambah Data';
            $data_penduduk = Penduduk::find($penduduk_id);;
            $document = Document::where('das_penduduk_id', $penduduk_id)->first();
            $jenis_document = JenisDokumen::all();
            return view('data.pengurus.create_arsip', compact('page_title', 'page_description', 'document', 'data_penduduk', 'pengurus_id', 'jenis_document'));
        } catch (\Exception $e) {
            report($e);
            Log::channel('daily')->error('create_arsip di ArsipController: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);            
            return back()->withInput()->with('error', 'Gagal create arsip!');
        }
    }

    public function editArsip($penduduk_id, $pengurus_id)
    {
        try {
            $page_title = 'Daftar Dokumen';
            $page_description = 'Edit Data';
            $penduduk = Penduduk::with(['desa:id,profil_id,desa_id,nama,sebutan_desa'])
            ->join('ref_warganegara', 'ref_warganegara.id', '=', 'das_penduduk.warga_negara_id')
            ->join('das_data_desa', 'das_data_desa.desa_id', '=', 'das_penduduk.desa_id')
            ->join('ref_agama', 'ref_agama.id', '=', 'das_penduduk.agama_id')
            ->join('das_keluarga', 'das_keluarga.no_kk', '=', 'das_penduduk.no_kk')
            ->join('ref_pendidikan_kk', 'ref_pendidikan_kk.id', '=', 'das_penduduk.pendidikan_id')
            ->join('documents', 'documents.das_penduduk_id', '=', 'das_penduduk.id')
            ->where('documents.id', $penduduk_id)    
            ->first([
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
            $jenis_document = JenisDokumen::all();
            return view('data.pengurus.edit_arsip', compact('page_title', 'page_description', 'penduduk', 'pengurus_id', 'jenis_document'));
        } catch (\Exception $e) {
            report($e);
            Log::channel('daily')->error('edit_arsip di ArsipController: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);            
            return back()->withInput()->with('error', 'Gagal edit arsip!');
        }
    }

    public function storeArsip(DocumentRequest $request)
    {
        try {
            $input = $request->input();
            if ($request->hasFile('path_document')) {
                if ($request->hasFile('path_document')) {
                    $file = $request->file('path_document');
                    $mimeType = mime_content_type($file->getRealPath());
                    $originalName = $file->getClientOriginalName();
                    if (in_array($mimeType, ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/x-ole-storage', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheetapplication/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) {
                        $this->handleFileUpload($request, $input, 'path_document', "arsip/documents");
                    } else {
                        return redirect()->back()->withErrors(['path_document' => 'Isian path document harus dokumen berjenis : pdf, doc, docx, xls, xlsx.']);
                    }
                }
            }else{
                $document = Document::find($request->post('document_id'));
                $path_document = $document->path_document;
                $input['path_document'] = $path_document;
                $originalName = $document->nama_document;
            }
            
            if (Document::where('pengurus_id', $request->post('pengurus_id'))->orderBy('id', 'DESC')->exists()) {
                $data_document = Document::where('pengurus_id', $request->post('pengurus_id'))->orderBy('id', 'DESC')->first();
                $no_urut = $data_document->no_urut ?? 0; 
            }else{
                $no_urut = 0;
            }
            
            $input['kode_surat'] = "SK-" . ($no_urut + 1); 
            $input['ditandatangani'] =  Auth::user()->name; 
            $input['no_urut'] = $request->post('document_id') ? $no_urut : $no_urut + 1;
            $input['nama_document'] = $originalName;
            $insert = Document::updateOrcreate(['id' => $request->post('document_id')], $input);
            if ($insert) {
                return redirect()->route('data.pengurus.arsip', ['pengurus_id' => $request->post('pengurus_id')])->with('success', 'Berhasil Simpan Document');
            }else{
                return back()->with('success', 'Gagal Simpan Document');
            }
        } catch (\Exception $e) {
            report($e);
            Log::channel('daily')->error('store_arsip di ArsipController: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);            
            return back()->withInput()->with('error', 'gagal tambah arsip!');
        }
    }

    public function deleteDocument($id)
    {
        try {
            $document = Document::find($id);
            if ($document) {
                if ($document->delete()) { 
                    return back()->with('success', 'Document Berhasil Dihapus');
                } else {
                    return back()->with('error', 'Document Gagal Dihapus');
                }
            } else {
                return back()->with('error', 'Document tidak ditemukan');
            }
        } catch (Exception $e) {
            report($e);
            Log::channel('daily')->error('deleteDocument di ArsipController: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);            
            return back()->withInput()->with('error', 'Gagal Delete Dokumen!');
        }
    }

    public function downloadArsipZip($pengurus_id)
    {
        try{
            $pengurus = Pengurus::where('id', $pengurus_id)->first('nama');
            $zipFileName = $pengurus->nama . '-dokumen-arsip.zip';
            $zipPath = storage_path($zipFileName);
        
            $zip = new \ZipArchive;
        
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {

                $files = Document::where('pengurus_id', $pengurus_id)->get();
        
                foreach ($files as $file) {
                    if (file_exists($file->path_document)) {
                        $zip->addFile($file->path_document, basename($file->nama_document));
                    }
                }
        
                $zip->close();
        
                return response()->download($zipPath)->deleteFileAfterSend(true);
            } else {
                return response()->json(['error' => 'Gagal membuat file ZIP'], 500);
            }
        } catch (Exception $e) {
            report($e);
            Log::channel('daily')->error('downloadArsipZip di ArsipController: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);            
            return back()->withInput()->with('error', 'Gagal Download Arsip');
        }
    }

    public function downloadArsip($document_id)
    {
        try{
            $dokumen = Document::findOrFail($document_id);
            $filePath = $dokumen->path_document;
            if (!file_exists($filePath)) {
                return response()->json(['error' => 'Gagal membuat file ZIP' . $filePath], 500);
            }
            return response()->download($filePath, basename($dokumen->nama_document));
        } catch (Exception $e) {
            report($e);
            Log::channel('daily')->error('downloadArsip di ArsipController: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);            
            return back()->withInput()->with('error', 'Gagal Download Arsip');
        }
    }

    public function pendudukSelect2(Request $request)
    {
        try {
            $search = $request->get('search');
            $penduduk = Penduduk::where('nama', 'like', "%{$search}%")
                        ->limit(5)
                        ->get(['id', 'nama', 'nik']);
        
            return response()->json($penduduk);
        } catch (Exception $e) {
            report($e);
            Log::channel('daily')->error('pendudukSelect2 di ArsipController: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);            
            return back()->withInput()->with('error', 'Gagal Download Arsip');
        }
    }
}
