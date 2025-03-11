<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Setting;

use App\Models\JenisPenyakit;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\JenisPenyakitRequest;

class JenisPenyakitController extends Controller
{
    public function index()
    {
        $page_title = 'Jenis Penyakit';
        $page_description = 'Daftar Jenis Penyakit';

        return view('setting.jenis_penyakit.index', compact('page_title', 'page_description'));
    }

    // Get Data Jenis Penyakit
    public function getData()
    {
        return DataTables::of(JenisPenyakit::all())
            ->addColumn('aksi', function ($row) {
                $data['modal_form'] = $row->id;
                $data['delete_url'] = route('setting.jenis-penyakit.destroy', $row->id);

                return view('forms.aksi', $data);
            })
            ->make();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param JenisPenyakitRequest $request
     * 
     * @return Response
     */
    public function store(JenisPenyakitRequest $request)
    {
        try {
            JenisPenyakit::create($request->validated());
            session()->flash('success', 'Jenis Penyakit berhasil ditambahkan!');

            return response()->json([
                'success' => true,
                'message' => session('success')
            ]);
        } catch (\Exception $e) {
            report($e);
            session()->flash('error', 'Jenis Penyakit gagal ditambahkan!');

            return response()->json([
                'success' => false,
                'message' => session('error')
            ]);
        }
    }

    public function edit($id)
    {
        $tipe = JenisPenyakit::findOrFail($id);

        return response()->json($tipe);
    }

    public function update(JenisPenyakitRequest $request, $id)
    {
        try {
            JenisPenyakit::findOrFail($id)->update($request->validated());
            session()->flash('success', 'Jenis Penyakit berhasil diupdate!');

            return response()->json([
                'success' => true,
                'message' => session('success')
            ]);
        } catch (\Exception $e) {
            report($e);
            session()->flash('error', 'Jenis Penyakit gagal diupdate!');

            return response()->json([
                'success' => false,
                'message' => session('error')
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            JenisPenyakit::findOrFail($id)->delete();
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Data gagal dihapus!');
        }

        return redirect()->route('setting.jenis-penyakit.index')->with('success', 'Data berhasil dihapus!');
    }
}
