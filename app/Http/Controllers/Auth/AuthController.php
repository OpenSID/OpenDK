<?php

/*
 * File ini bagian dari:
 *
 * PBB Desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright	Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use function back;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Exception;
use function flash;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
