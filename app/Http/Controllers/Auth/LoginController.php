<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Notifications\SendToken2FA;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use App\Services\ActivityLogger;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        sendFailedLoginResponse as traitSendFailedLoginResponse;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('guest')->except('logout');
        $captchaView = $this->settings['google_recaptcha'] ? 'auth.google-captcha' : 'auth.captcha';
        View::share('captchaView', $captchaView);
    }

    public function redirectTo()
    {
        // check password
        $cek_password = Hash::check('password', auth()->user()->password);
        if ($cek_password && (bool) env('APP_DEMO') == false) {
            $this->redirectTo = 'changedefault';
        }

        switch (auth()->user()->roles()->first()?->name) {
            case 'kontributor-artikel':
                $this->redirectTo = 'informasi/artikel';
                break;

            default:
                $this->redirectTo;
                break;
        }

        return $this->redirectTo;
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $validation = [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ];
        if ($this->settings['google_recaptcha']) {
            $validation['g-recaptcha-response'] = 'required|recaptchav3:login,0.5';
        } else {
            $validation['captcha'] = 'required|captcha';
        }
        $customMessages = [
            'captcha.required' => 'Captcha code diperlukan.',
            'captcha.captcha' => 'Invalid captcha code.',
            'g-recaptcha-response' => [
                'recaptchav3' => 'Captcha error message',
            ],
        ];

        // nm
        if (app()->environment('testing')) {
            // Skip captcha validation in testing environment
            unset($validation['g-recaptcha-response'], $validation['captcha']);
        }

        $request->validate($validation, $customMessages);
    }
    protected function authenticated(Request $request, $user)
    {
        // Log successful login
        ActivityLogger::log(
            category: 'login',
            event: 'success',
            message: 'Pengguna berhasil masuk ke sistem',
            subject: $user,
            causer: $user,
            additionalProperties: [
                'user_id' => $user->id,
                'email' => $user->email,
                'status' => 'authenticated',
            ]
        );
        
        if (($this->settings['login_2fa'] ?? false)) {
            return $this->startTwoFactorAuthProcess($request, $user);
        }

        return redirect()->intended($this->redirectPath());
    }

    /**
     * Handle a failed authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        // Log failed login attempt
        ActivityLogger::log(
            category: 'login',
            event: 'failed',
            message: 'Percobaan login gagal',
            subject: null,
            causer: null,
            additionalProperties: [
                'email' => $request->input($this->username()),
                'status' => 'invalid_credentials',
            ]
        );

        return $this->traitSendFailedLoginResponse($request);
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function logout(Request $request)
    {
        // Log the logout event before logging out
        if (auth()->check()) {
            ActivityLogger::log(
                category: 'login',
                event: 'logout',
                message: 'Pengguna keluar dari sistem',
                subject: auth()->user(),
                causer: auth()->user(),
                additionalProperties: [
                    'user_id' => auth()->id(),
                    'email' => auth()->user()->email ?? null,
                    'status' => 'logged_out',
                ]
            );
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect('/');
    }

    protected function loggedOut(Request $request)
    {
        // This method can be used for additional logic after logout
        // For example, flashing a session message.
    }

    /**
     * Log out the user and start the two factor authentication state.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    private function startTwoFactorAuthProcess(Request $request, $user)
    {
        // Logout user, but remember user id
        auth()->logout();
        $request->session()->put(
            'two-factor:auth',
            array_merge(['id' => $user->id], $request->only('email', 'remember'))
        );

        $this->registerUserAndSendToken($user);

        return redirect()->route('auth.token');
    }

    private function registerUserAndSendToken(User $user)
    {
        $token = rand(100000, 999999);
        $user->setTwoFactorAuthIdExpired($token);
        try {
            $user->notify(new SendToken2FA($token));
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal mengirim email token 2FA.'. $e->getMessage());
        }
    }
}
