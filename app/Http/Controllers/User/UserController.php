<?php

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

use function back;
use function bcrypt;
use function compact;
use function flash;
use function public_path;
use function redirect;
use function route;
use function trans;
use function view;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title = 'Pengguna';
        return view('user.index', compact('page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title = 'Tambah Pengguna';
        $item       = Role::where('slug', '!=', 'super-admin')->pluck('name', 'slug')->toArray();
        return view('user.create', compact('item', 'page_title'));
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
        $user = User::find($id);
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
        $page_title = 'Ubah Pengguna';
        $user       = User::find($id);
        $title      = ['title' => 'Pengguna'];
        $item       = Role::where('slug', '!=', 'super-admin')->pluck('name', 'slug')->toArray();
        return view('user.edit', compact('page_title', 'user', 'title', 'item'));
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
            $user_find = User::find($id);

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
            $user_find = User::find($id);

            $user = Sentinel::update($user_find, $request->all());
            $user->update([
                'password' => bcrypt($request->password),
            ]);

            flash()->success(trans('message.user.update-success'));
            return redirect()->route('setting.user.index');
        } catch (Exception $e) {
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
        ->addColumn('action', function ($user) {
            if ($user->id != 1) {
                $edit_url = route('setting.user.edit', $user->id);
                if ($user->status == 1) {
                    $suspend_url         = route('setting.user.destroy', $user->id);
                    $data['suspend_url'] = $suspend_url;
                } else {
                    $active_url         = route('setting.user.active', $user->id);
                    $data['active_url'] = $active_url;
                }

                $data['edit_url'] = $edit_url;
            } else {
                $edit_url         = route('setting.user.edit', $user->id);
                $data['edit_url'] = $edit_url;
            }

            return view('forms.action', $data);
        })
        ->make(true);
    }
}
