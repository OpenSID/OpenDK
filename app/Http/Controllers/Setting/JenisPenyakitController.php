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
use App\Models\JenisPenyakit;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JenisPenyakitController extends Controller
{
    public function index()
    {
        $page_title       = 'Jenis Penyakit';
        $page_description = 'Daftar Jenis Penyakit';

        return view('setting.jenis_penyakit.index', compact('page_title', 'page_description'));
    }

    // Get Data Kategori Komplain
    public function getData()
    {
        return DataTables::of(JenisPenyakit::all())
            ->addColumn('aksi', function ($row) {
                $data['edit_url']   = route('setting.jenis-penyakit.edit', $row->id);
                $data['delete_url'] = route('setting.jenis-penyakit.destroy', $row->id);

                return view('forms.aksi', $data);
            })
            ->make();
    }

    // Create Action
    public function create()
    {
        $page_title       = 'Jenis Penyakit';
        $page_description = 'Tambah Jenis Penyakit';

        return view('setting.jenis_penyakit.create', compact('page_title', 'page_description'));
    }

    // Store Data
    public function store(Request $request)
    {
        request()->validate([
            'nama' => 'required',
        ]);

        try {
            $penyakit = new JenisPenyakit($request->all());
            $penyakit->save();
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Data gagal disimpan!');
        }

        return redirect()->route('setting.jenis-penyakit.index')->with('success', 'Data berhasil disimpan!');
    }

    public function edit($id)
    {
        $penyakit         = JenisPenyakit::findOrFail($id);
        $page_title       = 'Jenis Penyakit';
        $page_description = 'Ubah Jenis Penyakit : ' . $penyakit->nama;

        return view('setting.jenis_penyakit.edit', compact('page_title', 'page_description', 'penyakit'));
    }

    public function update(Request $request, $id)
    {
        request()->validate([
            'nama' => 'required',
        ]);

        try {
            $penyakit = JenisPenyakit::findOrFail($id);
            $penyakit->fill($request->all());
            $penyakit->save();
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Data gagal diupdate!');
        }

        return redirect()->route('setting.jenis-penyakit.index')->with('success', 'Data berhasil diupdate!');
    }

    public function destroy($id)
    {
        try {
            JenisPenyakit::findOrFail($id)->delete();
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Data gagal dihapus!');
        }

        return redirect()->route('setting.jenis-penyakit.index')->with('success', 'Data berhasil dihapus!');
    }
}
