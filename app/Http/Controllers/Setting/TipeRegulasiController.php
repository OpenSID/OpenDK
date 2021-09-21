<?php

/*
 * File ini bagian dari:
 *
 * PBB Desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright	Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\TipeRegulasi;
use function back;
use function compact;
use Exception;

use Illuminate\Http\Request;
use function redirect;
use function request;
use function route;
use function str_slug;
use function view;
use Yajra\DataTables\DataTables;

class TipeRegulasiController extends Controller
{
    public function index()
    {
        $page_title       = 'Tipe Regulasi';
        $page_description = 'Daftar TIpe Regulasi';
        return view('setting.tipe_regulasi.index', compact('page_title', 'page_description'));
    }

    // Get Data Kategori Komplain
    public function getData()
    {
        return DataTables::of(TipeRegulasi::select(['id', 'nama'])->orderBy('id'))
            ->addColumn('action', function ($row) {
                $edit_url   = route('setting.tipe-regulasi.edit', $row->id);
                $delete_url = route('setting.tipe-regulasi.destroy', $row->id);

                $data['edit_url']   = $edit_url;
                $data['delete_url'] = $delete_url;

                return view('forms.action', $data);
            })
            ->make();
    }

    // Create Action
    public function create()
    {
        $page_title       = 'Tambah';
        $page_description = 'Tambah Tipe Regulasi';

        return view('setting.tipe_regulasi.create', compact('page_title', 'page_description'));
    }

    // Store Data
    public function store(Request $request)
    {
        try {
            $tipe       = new TipeRegulasi($request->all());
            $tipe->slug = str_slug($tipe->nama);

            request()->validate([
                'nama' => 'required',
            ]);

            $tipe->save();
            return redirect()->route('setting.tipe-regulasi.index')->with('success', 'Tipe Regulasi berhasil dikirim!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Tipe Regulasi gagal dikirim!');
        }
    }

    public function edit($id)
    {
        $tipe             = TipeRegulasi::findOrFail($id);
        $page_title       = 'Edit';
        $page_description = 'Edit Tipe Regulasi' . $tipe->nama;
        return view('setting.tipe_regulasi.edit', compact('page_title', 'page_description', 'tipe'));
    }

    public function update(Request $request, $id)
    {
        // Save Request
        try {
            $tipe = TipeRegulasi::findOrFail($id);
            $tipe->fill($request->all());

            request()->validate([
                'nama' => 'required',
            ]);

            $tipe->save();
            return redirect()->route('setting.tipe-regulasi.index')->with('success', 'Tipe Regulasi berhasil diupdate!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Tipe Regulasi gagal diupdate!');
        }
    }

    public function destroy($id)
    {
        try {
            TipeRegulasi::findOrFail($id)->delete();

            return redirect()->route('setting.tipe-regulasi.index')->with('success', 'Tipe Regulasi berhasil dihapus!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Tipe Regulasi gagal dihapus!');
        }
    }
}
