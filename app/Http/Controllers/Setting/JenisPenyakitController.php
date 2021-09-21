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
use App\Models\JenisPenyakit;
use function back;
use function compact;
use Exception;

use Illuminate\Http\Request;
use function redirect;
use function request;
use function route;
use function view;
use Yajra\DataTables\DataTables;

class JenisPenyakitController extends Controller
{
    public function index()
    {
        $page_title       = 'Jenis Penyakit';
        $page_description = 'Daftar Jenis Epidemi Penyakit';
        return view('setting.jenis_penyakit.index', compact('page_title', 'page_description'));
    }

    // Get Data Kategori Komplain
    public function getData()
    {
        return DataTables::of(JenisPenyakit::select(['id', 'nama'])->orderBy('id'))
            ->addColumn('action', function ($row) {
                $edit_url   = route('setting.jenis-penyakit.edit', $row->id);
                $delete_url = route('setting.jenis-penyakit.destroy', $row->id);

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
        $page_description = 'Tambah Jenis Penyakit';

        return view('setting.jenis_penyakit.create', compact('page_title', 'page_description'));
    }

    // Store Data
    public function store(Request $request)
    {
        try {
            $penyakit = new JenisPenyakit($request->all());

            request()->validate([
                'nama' => 'required',
            ]);

            $penyakit->save();
            return redirect()->route('setting.jenis-penyakit.index')->with('success', 'Data berhasil disimpan!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Data gagal disimpan!');
        }
    }

    public function edit($id)
    {
        $penyakit         = JenisPenyakit::findOrFail($id);
        $page_title       = 'Edit Jenis Penyakit';
        $page_description = 'Edit Jenis Penyakit ' . $penyakit->nama;
        return view('setting.jenis_penyakit.edit', compact('page_title', 'page_description', 'penyakit'));
    }

    public function update(Request $request, $id)
    {
        // Save Request
        try {
            $penyakit = JenisPenyakit::findOrFail($id);
            $penyakit->fill($request->all());

            request()->validate([
                'nama' => 'required',
            ]);

            $penyakit->save();
            return redirect()->route('setting.jenis-penyakit.index')->with('success', 'Data berhasil diupdate!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Data gagal diupdate!');
        }
    }

    public function destroy($id)
    {
        try {
            JenisPenyakit::findOrFail($id)->delete();

            return redirect()->route('setting.jenis-penyakit.index')->with('success', 'Data berhasil dihapus!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Data gagal dihapus!');
        }
    }
}
