<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use function back;
use function flash;
use function redirect;
use function trans;
use function view;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function loginProcess(Request $request)
    {
        try {
            $remember = (bool) $request->input('remember_me');
            if (! Sentinel::authenticate($request->all(), $remember)) {
                return back()->withInput()->with('error', 'Email atau Password Salah!');
            }
            return redirect()->route('dashboard');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Gagal Masuk!' . $e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function logout()
    {
        Sentinel::logout();
        return redirect()->route('beranda');
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function registerProcess(Request $request)
    {
        try {
            $status = ! empty($request->status) ? 1 : 1;
            $request->merge(['status' => $status]);
            $user = Sentinel::registerAndActivate($request->all());

            Sentinel::findRoleBySlug('admin')->users()->attach($user);

            flash()->success(trans('message.user.create-success'));
            return redirect()->route('/');
        } catch (Exception $e) {
            flash()->error(trans('message.user.create-error'));
            return back()->withInput();
        }
    }
}
