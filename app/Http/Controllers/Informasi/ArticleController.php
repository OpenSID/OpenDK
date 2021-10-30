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
use App\Http\Requests\ArticleStoreRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Models\Article;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class ArticleController extends Controller
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
    public function store(ArticleStoreRequest $request)
    {
        try {
            $input = $request->all();
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = Storage::putFile('public/artikel', $file);

                $input['image'] = substr($path, 15) ;
            }

            Article::create($input);

            return redirect()->route('informasi.artikel.index')->with('success', 'Artikel berhasil disimpan!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Simpan artikel gagal!');
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
        $data['article'] = Article::find($id);
        $data['path'] = Storage::url('artikel/'.$data['article']->image);
        return view('informasi.artikel.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['article'] = Article::find($id);
        $data['path'] = Storage::url('artikel/'.$data['article']->image);
        return view('informasi.artikel.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleUpdateRequest $request, $id)
    {
        try {
            $model = Article::find($id);

            $input = $request->all();
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = Storage::putFile('public/artikel', $file);

                $input['image'] = substr($path, 15) ;
            }

            $model->update($input);

            return redirect()->route('informasi.artikel.index')->with('success', 'Artikel berhasil diubah!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Ubah artikel gagal!');
        }
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
            Article::findOrFail($id)->delete();

            return redirect()->route('informasi.artikel.index')->with('success', 'Artikel sukses dihapus!');
        } catch (Exception $e) {
            return redirect()->route('informasi.artikel.index')->with('error', 'Artikel gagal dihapus!');
        }
    }

    public function getDataArtikel(Request $request)
    {
        return DataTables::of(Article::all())
            ->addColumn('action', function ($row) {
                $show_url   = route('informasi.artikel.show', $row->id);
                $edit_url   = route('informasi.artikel.edit', $row->id);
                $delete_url = route('informasi.artikel.destroy', $row->id);

                $data['show_url'] = $show_url;

                if (! Sentinel::guest()) {
                    $data['edit_url']   = $edit_url;
                    $data['delete_url'] = $delete_url;
                }

                return view('forms.action', $data);
            })
            ->editColumn('is_active', function ($row) {
                if ($row->is_active == 0 || $row->is_active == null) {
                    $status = 'Tidak Aktif';
                } else {
                    $status = 'Aktif';
                }
                return $status;
            })->make();
    }
}
