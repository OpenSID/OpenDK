<?php

namespace App\Http\Controllers\Role;

use App\Models\RoleUser;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\RoleRequest;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use App\Models\Role;
use App\Models\Menu;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = 'Grup Pengguna';
        return view('role.index', compact('page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Role::getListPermission();
        return view('role.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     * @author Yoga <yoga.h@smooets.com>
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        try {
            $temp = array();
            if (!empty($request->permissions)) {
                foreach ($request->permissions as $key => $value) {
                    $temp[$key] = ($value == 1 ? TRUE:FALSE);
                }
            }
        
            $request['permissions'] = $temp;
            $role = Role::create($request->all());
            flash()->success( trans( 'message.role.create-success', [
                'attribute' => trans( 'island.role' ),
                'detail' => '#' . $role->id . ' | ' . $role->slug
                ] ) );

            return redirect()->route( 'setting.role.index' );
        } catch (Exception $e) {
            flash()->error( trans( 'general.destroy-error', [
                'attribute' => trans( 'island.role' ),
                ] ) );

            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     * @author Yoga <yoga.h@smooets.com>
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @author Yoga <yoga.h@smooets.com>
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permissions = Role::getListPermission();
        $menu = Menu::get();
        return view('role.edit', compact('role','permissions','menu'));
    }

    /**
     * Update the specified resource in storage.
     * @author Yoga <yoga.h@smooets.com>
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
          if (isset($request->permissions)) {
            foreach ($request->permissions as $key => $value) {
                $temp[$key] = ($value == 1 ? TRUE:FALSE);
            }

            $request['permissions'] = $temp;
            Role::find($id)->update( $request->all() );
            $role = Role::find($id);
            flash()->success( trans( 'message.role.update-success', [
                'attribute' => trans( 'island.role' ),
                'detail' => '#' . $role->id . ' | ' . $role->slug
                ] ) );
        } else{
           Role::find($id)->update(['name' => $request->name,'permissions' => []]);
       }
       return redirect()->route( 'setting.role.index' );
   } catch (Exception $e) {
        flash()->error( trans( 'message.role.update-error', [
        'attribute' => trans( 'island.role' ),
        ] ) );

    return back()->withInput();
}
}

    /**
     * Remove the specified resource from storage.
     * @author Yoga <yoga.h@smooets.com>
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if(RoleUser::where('role_id', $id)->first()) {
                flash()->error( trans( 'general.destroy-error', [
                    'attribute' => trans( 'island.role' ),
                    ] ) );

                return back();
            } else {
                $role = Role::findOrFail($id);
                $role->delete();
                flash()->success( trans( 'general.destroy-success' ) );
                return redirect()->route( 'setting.role.index' );
            }
        } catch (Exception $e) {
            flash()->error( trans( 'general.destroy-error', [
                'attribute' => trans( 'island.role' ),
                ] ) );

            return back();
        }
    }
    /**
     * Gets the data.
     *
     * @return     <type>  The data.
     */
    public function getData()
    {
        return DataTables::of(Role::datatables())
        ->addColumn( 'action', function ( $role ) {
            $edit_url = route('setting.role.edit', $role->id );
            $delete_url = route('setting.role.destroy', $role->id);

            $data['edit_url']   = $edit_url;
            $data['delete_url'] = $delete_url;

            return view('forms.action', $data);
        })
        ->make(true);
    }
}
