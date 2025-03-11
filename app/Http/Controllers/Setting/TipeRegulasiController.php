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

use App\Models\TipeRegulasi;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\TipeRegulasiRequest;

class TipeRegulasiController extends Controller
{
    public function index()
    {
        $page_title = 'Tipe Regulasi';
        $page_description = 'Daftar TIpe Regulasi';

        return view('setting.tipe_regulasi.index', compact('page_title', 'page_description'));
    }

    // Get Data Kategori Komplain
    public function getData()
    {
        return DataTables::of(TipeRegulasi::all())
            ->addColumn('aksi', function ($row) {
                $data['modal_form'] = $row->id;
                $data['delete_url'] = route('setting.tipe-regulasi.destroy', $row->id);

                return view('forms.aksi', $data);
            })
            ->make();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TipeRegulasiRequest $request
     * 
     * @return Response
     */
    public function store(TipeRegulasiRequest $request)
    {
        try {
            TipeRegulasi::create($request->validated());
            session()->flash('success', 'Tipe Regulasi berhasil ditambahkan!');

            return response()->json([
                'success' => true,
                'message' => session('success')
            ]);
        } catch (\Exception $e) {
            report($e);
            session()->flash('error', 'Tipe Regulasi gagal ditambahkan!');

            return response()->json([
                'success' => false,
                'message' => session('error')
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $tipe = TipeRegulasi::findOrFail($id);

        return response()->json($tipe);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param TipeRegulasiRequest $request
     * 
     * @return Response
     */
    public function update(TipeRegulasiRequest $request, $id)
    {
        try {
            TipeRegulasi::findOrFail($id)->update($request->validated());
            session()->flash('success', 'Tipe Regulasi berhasil diupdate!');

            return response()->json([
                'success' => true,
                'message' => session('success')
            ]);
        } catch (\Exception $e) {
            report($e);
            session()->flash('error', 'Tipe Regulasi gagal diupdate!');

            return response()->json([
                'success' => false,
                'message' => session('error')
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            TipeRegulasi::findOrFail($id)->delete();
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Tipe Regulasi gagal dihapus!');
        }

        return redirect()->route('setting.tipe-regulasi.index')->with('success', 'Tipe Regulasi berhasil dihapus!');
    }
}
