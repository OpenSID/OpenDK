<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\UserActivity;
use Validator;
use Sentinel;
use Activation;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function loginProcess(Request $request)
    {
        try {
            $remember = (bool)$request->input('remember_me');
            if (!Sentinel::authenticate($request->all(), $remember)) {
                flash()->error('Wrong email or password!');
                return redirect()->back()->withInput();
            }

            flash()->success('Login success! Welcome to Bali Tower admin page!');
            return redirect()->route('dashboard.profil');
        } catch (\Exception $e) {
            flash()->error('Error login!' . $e);
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     * @author Yoga <thetaramolor@gmail.com>
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Sentinel::logout();
        return redirect()->route('dashboard.profil');
    }

    /**
     * Display the specified resource.
     * @author Yoga <thetaramolor@gmail.com>
     *
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
       return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function registerProcess(Request $request)
    {
        try {
            $status = !empty($request->status) ? 1 : 1;
            $request->merge(['status' => $status]);
            $user = Sentinel::registerAndActivate($request->all());


            Sentinel::findRoleBySlug('admin')->users()->attach( $user );

            flash()->success(trans('message.user.create-success'));
            return redirect()->route('/');

        } catch (Exception $e) {
            flash()->error(trans('message.user.create-error'));
            return back()->withInput();
        }
    }

}
