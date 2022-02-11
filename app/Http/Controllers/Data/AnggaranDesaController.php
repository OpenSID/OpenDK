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
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnggaranDesaRequest;
use App\Imports\ImporAPBDesa;
use App\Models\AnggaranDesa;
use App\Models\DataDesa;
use Yajra\DataTables\Facades\DataTables;

class AnggaranDesaController extends Controller
{
    public function index()
    {
        $page_title       = 'APBDes';
        $page_description = 'Data APBDes';

        return view('data.anggaran_desa.index', compact('page_title', 'page_description'));
    }

    public function getDataAnggaran()
    {
        return DataTables::of(AnggaranDesa::with('desa'))
            ->addColumn('aksi', function ($row) {
                $data['delete_url'] = route('data.anggaran-desa.destroy', $row->id);

                return view('forms.aksi', $data);
            })->editColumn('bulan', function ($row) {
                return months_list()[$row->bulan];
            })
            ->editColumn('jumlah', function ($row) {
                return number_format($row->jumlah, 2);
            })
            ->rawColumns(['aksi'])->make();
    }

    public function import()
    {
        $page_title       = 'APBDes';
        $page_description = 'Import APBDes';
        $years_list       = years_list();
        $months_list      = months_list();
        $list_desa        = DataDesa::all();

        return view('data.anggaran_desa.import', compact('page_title', 'page_description', 'years_list', 'months_list', 'list_desa'));
    }

    public function do_import(AnggaranDesaRequest $request)
    {
        try {
            (new ImporAPBDesa($request->only(['bulan', 'tahun', 'desa'])))
                ->queue($request->file('file'));
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Import data gagal.');
        }

        return back()->with('success', 'Import data sukses.');
    }

    public function destroy($id)
    {
        try {
            AnggaranDesa::findOrFail($id)->delete();
        } catch (\Exception $e) {
            report($e);
            return redirect()->route('data.anggaran-desa.index')->with('error', 'Data gagal dihapus!');
        }

        return redirect()->route('data.anggaran-desa.index')->with('success', 'Data sukses dihapus!');
    }
}
