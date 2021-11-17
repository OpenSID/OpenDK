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
use App\Models\Potensi;
use App\Models\TipePotensi;
use Exception;
use Illuminate\Http\Request;

use Illuminate\Http\Response;

class PotensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title       = 'Potensi';
        $page_description = 'Daftar Potensi';
        $potensis         = Potensi::latest()->paginate(10);

        return view('informasi.potensi.index', compact(['page_title', 'page_description', 'potensis']));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function kategori()
    {
        $page_title       = 'Potensi';

        if ($_GET['id'] != null) {
            $potensis = Potensi::where('kategori_id', $_GET['id'])->latest()->paginate(10);
            $kategori = TipePotensi::findOrFail($_GET['id'])->nama_kategori;
        } else {
            $potensis = Potensi::latest()->paginate(10);
            $kategori = 'Semua';
        }

        $page_description = 'Kategori Potensi : ' . $kategori;

        return view('informasi.potensi.index', compact(['page_title', 'page_description', 'potensis']));
    }

    /**
     * Get datatable
     */
    // public function getDataPotensi()
    // {
    //     return DataTables::of(Potensi::select('id', 'nama_potensi', 'lokasi'))
    //         ->addColumn('action', function($row) {
    //
    //             if(!Sentinel::guest()) {
    //                 $data['edit_url'] = route('informasi.potensi.edit', $row->id);
    //                 $data['delete_url'] = route('informasi.potensi.destroy', $row->id);
    //             }
    //
    //             return view('forms.action', $data);
    //         })->make();
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title = 'Potensi';
        $page_description = 'Tambah Potensi';

        return view('informasi.potensi.create', compact('page_title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'kategori_id'  => 'required',
            'nama_potensi' => 'required',
            'deskripsi'    => 'required',
            'file_gambar'  => 'image|mimes:bmp,jpg,jpeg,gif,png|max:1024',
        ]);

        try {
            $potensi = new Potensi($request->input());

            if ($request->hasFile('file_gambar')) {
                $lampiran = $request->file('file_gambar');
                $fileName = $lampiran->getClientOriginalName();
                $path     = "storage/potensi_kecamatan/";
                $request->file('file_gambar')->move($path, $fileName);
                $potensi->file_gambar = $path . $fileName;
            }

            $potensi->save();
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Simpan Event gagal! ' . $e->getMessage());
        }

        return redirect()->route('informasi.potensi.index')->with('success', 'Potensi berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $potensi          = Potensi::findOrFail($id);
        $page_title       = 'Potensi';
        $page_description = 'Potensi : ' . $potensi->nama_potensi;

        return view('informasi.potensi.show', compact('page_title', 'potensi'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $potensi          = Potensi::findOrFail($id);
        $page_title       = 'Potensi';
        $page_description = 'Ubah Potensi : ' . $potensi->nama_potensi;

        return view('informasi.potensi.edit', compact('page_title', 'page_description', 'potensi'));
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
            'kategori_id'  => 'required',
            'nama_potensi' => 'required',
            'deskripsi'    => 'required',
            'file_gambar'  => 'image|mimes:bmp,jpg,jpeg,gif,png|max:1024',
        ]);

        try {
            $potensi = Potensi::findOrFail($id);
            $potensi->fill($request->all());

            if ($request->hasFile('file_gambar')) {
                $lampiran = $request->file('file_gambar');
                $fileName = $lampiran->getClientOriginalName();
                $path     = "storage/potensi_kecamatan/";
                $request->file('file_gambar')->move($path, $fileName);
                $potensi->file_gambar = $path . $fileName;
            }

            $potensi->save();
        } catch (Exception $e) {
            return back()->with('error', 'Data Potensi gagal disimpan!' . $e->getMessage());
        }

        return redirect()->route('informasi.potensi.index')->with('success', 'Data Potensi berhasil disimpan!');
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
            Potensi::findOrFail($id)->delete();
        } catch (Exception $e) {
            return redirect()->route('informasi.form-dokumen.index')->with('error', 'Potensi gagal dihapus!');
        }

        return redirect()->route('informasi.potensi.index')->with('success', 'Potensi Berhasil dihapus!');
    }
}
