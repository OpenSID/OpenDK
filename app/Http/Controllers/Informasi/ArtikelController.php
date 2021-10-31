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
use App\Http\Requests\ArtikelStoreRequest;
use App\Http\Requests\ArtikelUpdateRequest;
use App\Models\Artikel;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class ArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('informasi.artikel.index');
    }

    public function getDataArtikel(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Artikel::all())
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $data['show_web'] = route('berita.detail', $row->slug);

                    if (! Sentinel::guest()) {
                        $data['edit_url']   = route('informasi.artikel.edit', $row->id);
                        $data['delete_url'] = route('informasi.artikel.destroy', $row->id);
                    }

                    return view('forms.action', $data);
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 0) {
                        return '<span class="label label-danger">Tidak Aktif</span>';
                    } else {
                        return '<span class="label label-success">Aktif</span>';
                    }
                })
                ->rawColumns(['status'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('informasi.artikel.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArtikelStoreRequest $request)
    {
        try {
            $input = $request->all();
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $path = Storage::putFile('public/artikel', $file);

                $input['gambar'] = substr($path, 15) ;
            }

            Artikel::create($input);
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Simpan artikel gagal!');
        }

        return redirect()->route('informasi.artikel.index')->with('success', 'Artikel berhasil disimpan!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('informasi.artikel.edit', [
            'artikel' => Artikel::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArtikelUpdateRequest $request, $id)
    {
        try {
            $model = Artikel::findOrFail($id);

            $input = $request->all();
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $path = Storage::putFile('public/artikel', $file);

                $input['gambar'] = substr($path, 15) ;
            }

            $model->update($input);
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Ubah artikel gagal!');
        }

        return redirect()->route('informasi.artikel.index')->with('success', 'Artikel berhasil diubah!');
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
            Artikel::findOrFail($id)->delete();
        } catch (Exception $e) {
            return redirect()->route('informasi.artikel.index')->with('error', 'Artikel gagal dihapus!');
        }

        return redirect()->route('informasi.artikel.index')->with('success', 'Artikel sukses dihapus!');
    }
}
