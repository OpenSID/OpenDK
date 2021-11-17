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
use App\Models\Regulasi;
use Exception;
use Illuminate\Http\Request;

use Illuminate\Http\Response;

class RegulasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title       = 'Regulasi';
        $page_description = 'Daftar Regulasi';
        $regulasi         = Regulasi::latest()->paginate(10); // TODO : Gunakan datatable

        return view('informasi.regulasi.index', compact('page_title', 'page_description', 'regulasi'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title       = 'Regulasi';
        $page_description = 'Tambah Regulasi';

        return view('informasi.regulasi.create', compact('page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'tipe_regulasi' => 'required',
            'judul'         => 'required',
            'deskripsi'     => 'required',
            'file_regulasi' => 'required|file|mimes:jpg,jpeg,png,gif,pdf|max:2048',
        ]);

        try {
            $regulasi               = new Regulasi($request->input());
            $regulasi->profil_id    = $this->profil->id;

            if ($request->hasFile('file_regulasi')) {
                $lampiran1 = $request->file('file_regulasi');
                $fileName1 = $lampiran1->getClientOriginalName();
                $path      = "storage/regulasi/";
                $request->file('file_regulasi')->move($path, $fileName1);
                $regulasi->file_regulasi = $path . $fileName1;
                $regulasi->mime_type     = $lampiran1->getClientOriginalExtension();
            }

            $regulasi->save();
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Regulasi gagal disimpan!!');
        }

        return redirect()->route('informasi.regulasi.index')->with('success', 'Regulasi berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $regulasi         = Regulasi::findOrFail($id);
        $page_title       = "Regulasi";
        $page_description = "Detail Regulasi : " . $page_title;

        return view('informasi.regulasi.show', compact('page_title', 'page_description', 'regulasi'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $regulasi         = Regulasi::findOrFail($id);
        $page_title       = 'Regulasi';
        $page_description = 'Ubah Regulasi : ' . $regulasi->judul;

        return view('informasi.regulasi.edit', compact('page_title', 'page_description', 'regulasi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        request()->validate([
            'tipe_regulasi' => 'required',
            'judul'         => 'required',
            'deskripsi'     => 'required',
            'file_regulasi' => 'file|mimes:jpg,jpeg,png,gif,pdf|max:2048',
        ]);

        try {
            $regulasi = Regulasi::findOrFail($id);
            $regulasi->fill($request->all());
            $regulasi->profil_id    = $this->profil->id;

            if ($request->hasFile('file_regulasi')) {
                $lampiran1 = $request->file('file_regulasi');
                $fileName1 = $lampiran1->getClientOriginalName();
                $path      = "storage/regulasi/";
                $request->file('file_regulasi')->move($path, $fileName1);
                $regulasi->file_regulasi = $path . $fileName1;
                $regulasi->mime_type     = $lampiran1->getClientOriginalExtension();
            }

            $regulasi->save();
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Regulasi gagal disimpan!!');
        }

        return redirect()->route('informasi.regulasi.show', $id)->with('success', 'Regulasi berhasil disimpan!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            Regulasi::findOrFail($id)->delete();
        } catch (Exception $e) {
            return redirect()->route('informasi.regulasi.index')->with('error', 'Regulasi gagal dihapus!');
        }

        return redirect()->route('informasi.regulasi.index')->with('success', 'Regulasi sukses dihapus!');
    }
}
