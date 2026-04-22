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

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Menu;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $page_title = 'Group Pengguna';
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
        $roles = Role::select('roles.*');

        return DataTables::of($roles)
            ->addColumn('users_count', function ($role) {
                $count = DB::table('model_has_roles')
                    ->where('role_id', $role->id)
                    ->count();
                return $count;
            })
            ->addColumn('aksi', function ($role) {
                $data['edit_url'] = route('setting.role.edit', $role->id);
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
        $menu = Menu::get();
        $page_title = 'Group Pengguna';
        $page_description = 'Tambah Group Pengguna';

        return view('role.create', compact('page_title', 'page_description', 'permissions', 'menu'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(RoleRequest $request)
    {
        try {
            // Create role
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'web',
            ]);

            // Sync permissions - checkbox values are 'on' when checked
            if ($request->has('permissions')) {
                $permissions = [];
                foreach ($request->permissions as $key => $value) {
                    // Checkbox checked sends 'on', unchecked doesn't send anything
                    if ($value == 'on' || $value == 1 || $value === true) {
                        $permissions[] = $key;
                    }
                }
                if (!empty($permissions)) {
                    $role->givePermissionTo($permissions);
                }
            }

            flash()->success(trans('message.role.create-success', [
                'attribute' => trans('island.role'),
                'detail' => '#' . $role->id . ' | ' . $role->slug,
            ]));

            return redirect()->route('setting.role.index');
        } catch (\Exception $e) {
            Log::error('Role creation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
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
        $role = Role::findOrFail($id);
        $permissions = Role::getListPermission();
        $menu = Menu::get();
        $page_title = 'Group Pengguna';
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
            $role = Role::findOrFail($id);
            
            // Update name
            $role->name = $request->name;
            $role->save();

            // Sync permissions
            $permissions = [];
            if ($request->has('permissions')) {
                foreach ($request->permissions as $key => $value) {
                    if ($value == 1 || $value === true) {
                        $permissions[] = $key;
                    }
                }
            }
            
            $role->syncPermissions($permissions);

            flash()->success(trans('message.role.update-success', [
                'attribute' => trans('island.role'),
                'detail' => '#' . $role->id . ' | ' . $role->slug,
            ]));

            return redirect()->route('setting.role.index');
        } catch (\Exception $e) {
            Log::error('Role update failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'role_id' => $id,
            ]);
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
            // Jika menggunakan paket Spatie, periksa apakah ada model yang terkait dengan role ini
            $role = Role::findOrFail($id);
            if ($role->users()->exists()) {
                flash()->error(trans('general.destroy-error', [
                    'attribute' => trans('island.role'),
                ]));

                return back();
            }else {                
                $role->delete();
                flash()->success(trans('general.destroy-success'));

                return redirect()->route('setting.role.index');
            }
        } catch (\Exception $e) {
            Log::error('Role deletion failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'role_id' => $id,
            ]);
            flash()->error(trans('general.destroy-error', [
                'attribute' => trans('island.role'),
            ]));

            return back();
        }
    }

    /**
     * Display list of users by role.
     *
     * @param  int  $id
     * @return Response
     */
    public function users($id)
    {
        $role = Role::findOrFail($id);
        $page_title = 'Group Pengguna';
        $page_description = 'Daftar User: ' . $role->name;

        return view('role.users', compact('page_title', 'page_description', 'role'));
    }

    /**
     * Gets the data for users by role.
     *
     * @param  int  $id
     * @return DataTables
     */
    public function getDataUsersByRole($id)
    {
        $role = Role::findOrFail($id);
        $users = $role->users()->select('users.id', 'users.name', 'users.email', 'users.status');

        return DataTables::of($users)
            ->editColumn('status', function ($user) {
                return $user->status == 1 ? 'Active' : 'Not Active';
            })
            ->addColumn('aksi', function ($user) {
                $data['edit_url'] = route('setting.user.edit', $user->id);

                return view('forms.aksi', $data);
            })
            ->make(true);
    }
}
