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

namespace App\Http\Controllers\Informasi;

use App\Http\Controllers\Controller;
use App\Http\Requests\DokumenRequest;
use App\Models\FormDokumen;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Yajra\DataTables\DataTables;

class FormDokumenController extends Controller
{
    public function index()
    {
        $page_title       = 'Dokumen';
        $page_description = 'Daftar Dokumen';

        return view('informasi.form_dokumen.index', compact('page_title', 'page_description'));
    }

    public function getDataDokumen()
    {
        return DataTables::of(FormDokumen::all())
            ->addColumn('aksi', function ($row) {
                if (! Sentinel::guest()) {
                    $data['edit_url']   = route('informasi.form-dokumen.edit', $row->id);
                    $data['delete_url'] = route('informasi.form-dokumen.destroy', $row->id);
                }

                $data['download_url'] = route('informasi.form-dokumen.download', $row->id);

                return view('forms.aksi', $data);
            })->make();
    }

    public function create()
    {
        $page_title       = 'Dokumen';
        $page_description = 'Tambah Dokumen';

        return view('informasi.form_dokumen.create', compact('page_title', 'page_description'));
    }

    public function store(DokumenRequest $request)
    {
        try {
            $input = $request->input();

            if ($request->hasFile('file_dokumen')) {
                $file     = $request->file('file_dokumen');
                $fileName = $file->getClientOriginalName();
                $path     = "storage/form_dokumen/";
                $file->move($path, $fileName);

                $input['file_dokumen'] = $path . $fileName;
            }

            FormDokumen::create($input);
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Dokumen gagal disimpan!');
        }

        return redirect()->route('informasi.form-dokumen.index')->with('success', 'Dokumen berhasil ditambah!');
    }

    public function edit(FormDokumen $dokumen)
    {
        $page_title       = 'Dokumen';
        $page_description = 'Ubah Dokumen ' . $dokumen->nama_dokumen;

        return view('informasi.form_dokumen.edit', compact('page_title', 'page_description', 'dokumen'));
    }

    public function update(DokumenRequest $request, FormDokumen $dokumen)
    {
        try {
            $input = $request->all();

            if ($request->hasFile('file_dokumen')) {
                $file     = $request->file('file_dokumen');
                $fileName = $file->getClientOriginalName();
                $path     = "storage/form_dokumen/";
                $file->move($path, $fileName);
                unlink(base_path('public/' . $dokumen->file_dokumen));

                $input['file_dokumen'] = $path . $fileName;
            }

            $dokumen->update($input);
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Dokumen gagal diubah!');
        }

        return redirect()->route('informasi.form-dokumen.index')->with('success', 'Dokumen berhasil diubah!');
    }

    public function destroy(FormDokumen $dokumen)
    {
        try {
            if ($dokumen->delete()) {
                unlink(base_path('public/' . $dokumen->file_dokumen));
            }
        } catch (\Exception $e) {
            report($e);
            return redirect()->route('informasi.form-dokumen.index')->with('error', 'Dokumen gagal dihapus!');
        }

        return redirect()->route('informasi.form-dokumen.index')->with('success', 'Dokumen berhasil dihapus!');
    }

    public function download(FormDokumen $dokumen)
    {
        try {
            return response()->download($dokumen->file_dokumen);
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Dokumen tidak ditemukan');
        }
    }
}
