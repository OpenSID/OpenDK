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
use App\Models\KategoriKomplain;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class KategoriKomplainController extends Controller
{
    public function index()
    {
        $page_title       = 'Kategori Komplain';
        $page_description = 'Daftar Kategori Komplain';

        return view('setting.komplain_kategori.index', compact('page_title', 'page_description'));
    }

    // Get Data Kategori Komplain
    public function getData()
    {
        return DataTables::of(KategoriKomplain::all())
            ->addColumn('aksi', function ($row) {
                $data['edit_url']   = route('setting.komplain-kategori.edit', $row->id);
                $data['delete_url'] = route('setting.komplain-kategori.destroy', $row->id);

                return view('forms.aksi', $data);
            })
            ->make();
    }

    // Create Action
    public function create()
    {
        $page_title       = 'Kategori Komplain';
        $page_description = 'Tambah Kategori Komplain';

        return view('setting.komplain_kategori.create', compact('page_title', 'page_description'));
    }

    // Store Data
    public function store(Request $request)
    {
        request()->validate([
            'nama' => 'required',
        ]);

        try {
            $kategori       = new KategoriKomplain($request->all());
            $kategori->slug = str_slug($kategori->nama);
            $kategori->save();
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Kategori Komplain gagal dikirim!');
        }

        return redirect()->route('setting.komplain-kategori.index')->with('success', 'Kategori Komplain berhasil dikirim!');
    }

    public function edit($id)
    {
        $kategori         = KategoriKomplain::findOrFail($id);
        $page_title       = 'Kategori Komplain';
        $page_description = 'Ubah Kategori Komplain : ' . $kategori->nama;

        return view('setting.komplain_kategori.edit', compact('page_title', 'page_description', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        request()->validate([
            'nama' => 'required',
        ]);

        try {
            $kategori = KategoriKomplain::findOrFail($id);
            $kategori->fill($request->all());
            $kategori->save();
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Kategori Komplain gagal diupdate!');
        }

        return redirect()->route('setting.komplain-kategori.index')->with('success', 'Kategori Komplain berhasil diupdate!');
    }

    public function destroy($id)
    {
        try {
            KategoriKomplain::findOrFail($id)->delete();
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Kategori Komplain gagal dihapus!');
        }

        return redirect()->route('setting.komplain-kategori.index')->with('success', 'Kategori Komplain berhasil dihapus!');
    }
}
