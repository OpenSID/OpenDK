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

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Coa;
use App\Models\SubCoa;
use App\Models\SubSubCoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class COAController extends Controller
{
    public function index()
    {
        $page_title       = 'COA';
        $page_description = 'Daftar COA';

        return view('setting.coa.index', compact('page_title', 'page_description'));
    }

    public function create()
    {
        $page_title       = "COA";
        $page_description = 'Tambah COA';

        return view('setting.coa.create', compact('page_title', 'page_description'));
    }

    public function store(Request $request)
    {
        request()->validate([
            'type_id'    => 'required',
            'sub_id'     => 'required',
            'sub_sub_id' => 'required',
            'coa_name'   => 'required',
            'id'         => 'required',
        ]);

        try {
            $data = [
                'type_id'    => $request->input('type_id'),
                'sub_id'     => $request->input('sub_id'),
                'sub_sub_id' => $request->input('sub_sub_id'),
                'coa_name'   => $request->input('coa_name'),
                'id'         => $request->input('id'),
            ];
            DB::table('ref_coa')->insert($data);
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Akun COA gagal disimpan!');
        }

        return redirect()->route('setting.coa.index')->with('success', 'Akun COA berhasil disimpan!');
    }

    public function get_sub_coa($type_id)
    {
        return SubCoa::where('type_id', $type_id)->get();
    }

    public function get_sub_sub_coa($type_id, $sub_id)
    {
        return SubSubCoa::where('type_id', $type_id)->where('sub_id', $sub_id)->get();
    }

    public function generate_id($type_id, $sub_id, $sub_sub_id)
    {
        $last_id = Coa::select('id')
            ->where('type_id', $type_id)
            ->where('sub_id', $sub_id)
            ->where('sub_sub_id', $sub_sub_id)
            ->orderBy('id', 'desc')->take(2)->get();

        return str_pad(intval($last_id[1]['id']) + 1, 2, '0', STR_PAD_LEFT);
    }
}
