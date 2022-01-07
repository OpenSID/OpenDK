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

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\TipePotensi;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;

class TipePotensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title       = 'Kategori Potensi';
        $page_description = 'Daftar ' . 'Kategori Potensi';

        return view('setting.tipe_potensi.index', compact('page_title', 'page_description'));
    }

    // Get Data Tipe Potensi
    public function getData()
    {
        return DataTables::of(TipePotensi::all())
            ->addColumn('aksi', function ($row) {
                $data['edit_url']   = route('setting.tipe-potensi.edit', $row->id);
                $data['delete_url'] = route('setting.tipe-potensi.destroy', $row->id);

                return view('forms.aksi', $data);
            })
            ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title       = 'Kategori Potensi';
        $page_description = 'Tambah Kategori Potensi';

        return view('setting.tipe_potensi.create', compact('page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'nama_kategori' => 'required',
        ]);

        try {
            $tipe       = new TipePotensi($request->all());
            $tipe->slug = str_slug($tipe->nama_kategori);
            $tipe->save();
        } catch (Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Tipe Potensi gagal dikirim!');
        }

        return redirect()->route('setting.tipe-potensi.index')->with('success', 'Kategori Potensi berhasil dikirim!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $tipe             = TipePotensi::findOrFail($id);
        $page_title       = 'Kategori Potensi';
        $page_description = 'Ubah  Kategori Potensi : ' . $tipe->nama_kategori;

        return view('setting.tipe_potensi.edit', compact('page_title', 'page_description', 'tipe'));
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
            'nama_kategori' => 'required',
        ]);

        try {
            $tipe = TipePotensi::findOrFail($id);
            $tipe->fill($request->all());
            $tipe->slug = str_slug($tipe->nama_kategori);
            $tipe->save();
        } catch (Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Kategori Potensi gagal diupdate!');
        }

        return redirect()->route('setting.tipe-potensi.index')->with('success', 'Kategori Potensi berhasil diupdate!');
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
            TipePotensi::findOrFail($id)->delete();
        } catch (Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Tipe Potensi gagal dihapus!');
        }

        return redirect()->route('setting.tipe-potensi.index')->with('success', 'Kategori Potensi berhasil dihapus!');
    }
}
