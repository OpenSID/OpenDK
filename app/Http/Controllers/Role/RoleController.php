<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright	Hak Cipta 2017 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Menu;
use App\Models\Role;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title       = 'Group Pengguna';
        $page_description = 'Daftar Data';

        return view('role.index', compact('page_title', 'page_description'));
    }

    /**
     * Gets the data.
     *
     * @return     <type>  The data.
     */
    public function getData()
    {
        return DataTables::of(Role::datatables())
        ->addColumn('aksi', function ($role) {
            $data['edit_url']   = route('setting.role.edit', $role->id);
            $data['delete_url'] = route('setting.role.destroy', $role->id);

            return view('forms.aksi', $data);
        })
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $permissions = Role::getListPermission();
        $page_title       = 'Group Pengguna';
        $page_description = 'Tambah Group Pengguna';

        return view('role.create', compact('page_title', 'page_description', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(RoleRequest $request)
    {
        try {
            $temp = [];
            if (! empty($request->permissions)) {
                foreach ($request->permissions as $key => $value) {
                    $temp[$key] = $value == 1 ? true : false;
                }
            }

            $request['permissions'] = $temp;
            $role                   = Role::create($request->all());
            flash()->success(trans('message.role.create-success', [
                'attribute' => trans('island.role'),
                'detail'    => '#' . $role->id . ' | ' . $role->slug,
            ]));

            return redirect()->route('setting.role.index');
        } catch (Exception $e) {
            flash()->error(trans('general.destroy-error', [
                'attribute' => trans('island.role'),
            ]));

            return back()->withInput();
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
        $role             = Role::findOrFail($id);
        $permissions      = Role::getListPermission();
        $menu             = Menu::get();
        $page_title       = 'Group Pengguna';
        $page_description = 'Ubah Data';

        return view('role.edit', compact('page_title', 'page_description', 'role', 'permissions', 'menu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {
            if (isset($request->permissions)) {
                foreach ($request->permissions as $key => $value) {
                    $temp[$key] = $value == 1 ? true : false;
                }

                $request['permissions'] = $temp;
                Role::findOrFail($id)->update($request->all());
                $role = Role::findOrFail($id);
                flash()->success(trans('message.role.update-success', [
                    'attribute' => trans('island.role'),
                    'detail'    => '#' . $role->id . ' | ' . $role->slug,
                ]));
            } else {
                Role::findOrFail($id)->update(['name' => $request->name, 'permissions' => []]);
            }
            return redirect()->route('setting.role.index');
        } catch (Exception $e) {
            flash()->error(trans('message.role.update-error', [
                'attribute' => trans('island.role'),
            ]));

            return back()->withInput();
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
            if (RoleUser::where('role_id', $id)->first()) {
                flash()->error(trans('general.destroy-error', [
                    'attribute' => trans('island.role'),
                ]));

                return back();
            } else {
                $role = Role::findOrFail($id);
                $role->delete();
                flash()->success(trans('general.destroy-success'));
                return redirect()->route('setting.role.index');
            }
        } catch (Exception $e) {
            flash()->error(trans('general.destroy-error', [
                'attribute' => trans('island.role'),
            ]));

            return back();
        }
    }
}
