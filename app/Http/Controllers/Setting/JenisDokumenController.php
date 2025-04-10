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

use App\Models\JenisDokumen;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\JenisDokumenRequest;
use App\Models\FormDokumen;

class JenisDokumenController extends Controller
{
    public function index()
    {
        $page_title = 'Jenis Dokumen';
        $page_description = 'Daftar '.'Jenis Dokumen';

        return view('setting.jenis_dokumen.index', compact('page_title', 'page_description'));
    }

    // Get Data Jenis Dokumen
    public function getData()
    {
        return DataTables::of(JenisDokumen::all())
            ->addColumn('aksi', function ($row) {
                $data['modal_form'] = $row->id;
                $data['delete_url']  = route('setting.jenis-dokumen.destroy', $row->id);
                return view('forms.aksi', $data);
            })
            ->make();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param JenisDokumenRequest $request
     * 
     * @return Response
     */
    public function store(JenisDokumenRequest $request)
    {
        try {
            JenisDokumen::create($request->validated());

            session()->flash('success', 'Jenis Dokumen berhasil ditambahkan!');

            return response()->json([
                'success' => true,
                'message' => session('success')
            ]);

        } catch (\Exception $e) {
            report($e);

            session()->flash('error', 'Jenis Dokumen gagal ditambahkan!');

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
        $tipe = JenisDokumen::findOrFail($id);

        return response()->json($tipe);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param JenisDokumenRequest $request
     * 
     * @return Response
     */
    public function update(JenisDokumenRequest $request, $id)
    {
        try {
            JenisDokumen::findOrFail($id)->update($request->validated());

            session()->flash('success', 'Jenis Dokumen berhasil diupdate!');

            return response()->json([
                'success' => true,
                'message' => session('success')
            ]);

        } catch (\Exception $e) {
            report($e);
            session()->flash('error', 'Jenis Dokumen gagal diupdate!');

            return response()->json([
                'success' => false,
                'message' => session('error')
            ]);
        }
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
            $jenisDokumen = JenisDokumen::findOrFail($id);

            $isUsed = FormDokumen::query()
                ->where('jenis_dokumen_id', $id)
                ->exists();

            if($isUsed) {
                return back()->with('error', 'Jenis Dokumen tidak bisa dihapus karena sedang digunakan pada Form Dokumen.');
            };

            $jenisDokumen->delete();
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Jenis Dokumen gagal dihapus!');
        }

        return redirect()->route('setting.jenis-dokumen.index')->with('success', 'Jenis Dokumen berhasil dihapus!');
    }
}
