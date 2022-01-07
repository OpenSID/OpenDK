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

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Role;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title       = 'Pengguna';
        $page_description = 'Daftar Data';

        return view('user.index', compact('page_title', 'page_description'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title       = 'Pengguna';
        $page_description = 'Tambah Data';
        $item             = Role::where('slug', '!=', 'super-admin')->pluck('name', 'slug')->toArray();

        return view('user.create', compact('page_title', 'page_description', 'item'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(UserRequest $request)
    {
        try {
            $status = ! empty($request->status) ? 1 : 1;
            $request->merge(['status' => $status]);
            $user = Sentinel::registerAndActivate($request->all());
            if ($request->hasFile('image')) {
                $user->uploadImage($request->image);
            }

            Sentinel::findRoleBySlug($request->role)->users()->attach($user);

            flash()->success(trans('message.user.create-success'));
            return redirect()->route('setting.user.index');
        } catch (Exception $e) {
            report($e);
            flash()->error(trans('message.user.create-error'));
            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $page_title       = 'Pengguna';
        $page_description = 'Ubah Data';
        $user             = User::findOrFail($id);
        $item             = Role::where('slug', '!=', 'super-admin')->pluck('name', 'slug')->toArray();

        return view('user.edit', compact('page_title', 'page_description', 'user', 'item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        try {
            $user_find = User::findOrFail($id);

            $user = Sentinel::update($user_find, $request->all());
            if ($request->hasFile('image')) {
                $path = public_path('uploads/user/');
                File::delete($path . $user_find->image);
                $user->uploadImage($request->image);
            }
            if (! empty($request->role)) {
                Sentinel::findRoleBySlug($user_find->roles()->first()->slug)->users()->detach($user);
                Sentinel::findRoleBySlug($request->role)->users()->attach($user);
            }

            flash()->success(trans('message.user.update-success'));
            return redirect()->route('setting.user.index');
        } catch (Exception $e) {
            report($e);
            flash()->error(trans('message.user.update-error'));
            return back()->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function updatePassword(UserUpdateRequest $request, $id)
    {
        // dd($request->all());
        try {
            $user_find = User::findOrFail($id);

            $user = Sentinel::update($user_find, $request->all());
            $user->update([
                'password' => bcrypt($request->password),
            ]);

            flash()->success(trans('message.user.update-success'));
            return redirect()->route('setting.user.index');
        } catch (Exception $e) {
            report($e);
            flash()->error(trans('message.user.update-error'));
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
            $user         = User::findOrFail($id);
            $user->status = 0;
            $user->save();

            flash()->success(trans('general.suspend-success'));
            return redirect()->route('setting.user.index');
        } catch (Exception $e) {
            report($e);
            flash()->success(trans('general.suspend-error'));
            return redirect()->route('setting.user.index');
        }
    }

    /**
     * Active User
     *
     * @param  int  $id
     * @return Response
     */
    public function active($id)
    {
        try {
            $user         = User::findOrFail($id);
            $user->status = 1;
            $user->save();

            flash()->success(trans('general.active-success'));
            return redirect()->route('setting.user.index');
        } catch (Exception $e) {
            report($e);
            flash()->success(trans('general.active-error'));
            return redirect()->route('setting.user.index');
        }
    }

    /**
     * Gets the data.
     */
    public function getDataUser()
    {
        return DataTables::of(User::datatables())
        ->editColumn('status', function ($user) {
            return $user->status == 1 ? 'Active' : 'Not Active';
        })
        ->addColumn('aksi', function ($user) {
            if ($user->id != 1) {
                if ($user->status == 1) {
                    $data['suspend_url'] = route('setting.user.destroy', $user->id);
                } else {
                    $data['active_url'] = route('setting.user.active', $user->id);
                }

                $data['edit_url'] = route('setting.user.edit', $user->id);
            } else {
                $data['edit_url'] = route('setting.user.edit', $user->id);
            }

            return view('forms.aksi', $data);
        })
        ->make(true);
    }
}
