<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Coa;
use App\Models\SubCoa;
use App\Models\SubSubCoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function back;
use function compact;
use function intval;
use function redirect;
use function request;
use function str_pad;
use function view;

use const STR_PAD_LEFT;

class COAController extends Controller
{
    public function index()
    {
        $page_title       = 'Daftar Akun';
        $page_description = 'Daftar Akun COA';

        return view('setting.coa.index', compact('page_title', 'page_description'));
    }

    public function create()
    {
        $page_title       = "Tambah";
        $page_description = 'Tambah COA Baru';

        return view('setting.coa.create', compact('page_title', 'page_description'));
    }

    public function store(Request $request)
    {
        try {
            request()->validate([
                'type_id'    => 'required',
                'sub_id'     => 'required',
                'sub_sub_id' => 'required',
                'coa_name'   => 'required',
                'id'         => 'required',
            ]);

            $data = [
                'type_id'    => $request->input('type_id'),
                'sub_id'     => $request->input('sub_id'),
                'sub_sub_id' => $request->input('sub_sub_id'),
                'coa_name'   => $request->input('coa_name'),
                'id'         => $request->input('id'),
            ];
            DB::table('ref_coa')->insert(
                $data
            );

            return redirect()->route('setting.coa.index')->with('success', 'Akun COA berhasil disimpan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Akun COA gagal disimpan!');
        }
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
