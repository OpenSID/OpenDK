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

use App\Http\Controllers\Controller;
use App\Models\Profil;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OtpController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        parent::__construct();
        $this->middleware('auth')->except(['showLoginForm', 'loginWithOtp', 'requestLoginOtp', 'showVerifyLoginForm']);
        $this->otpService = $otpService;
    }

    /**
     * Show OTP activation form
     */
    public function showActivationForm()
    {
        $user = Auth::user();
        
        return view('auth.otp.activate', [
            'page_title' => 'Aktivasi OTP',
            'page_description' => 'Aktifkan autentikasi OTP untuk keamanan tambahan',
            'user' => $user,
        ]);
    }

    /**
     * Request OTP for activation
     */
    public function requestActivation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'channel' => 'required|in:email,telegram',
            'identifier' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $channel = $request->channel;
        $identifier = $request->identifier;

        // Validate identifier based on channel
        if ($channel === 'email') {
            $emailValidator = Validator::make(['email' => $identifier], [
                'email' => 'required|email',
            ]);
            
            if ($emailValidator->fails()) {
                return back()->withErrors(['identifier' => 'Format email tidak valid'])->withInput();
            }
        } elseif ($channel === 'telegram') {
            // Verify Telegram chat ID
            if (!$this->otpService->verifyTelegramChatId($identifier)) {
                return back()->withErrors([
                    'identifier' => 'Chat ID Telegram tidak valid. Pastikan Anda sudah mengirim /start ke bot.'
                ])->withInput();
            }
        }

        // Generate and send OTP
        $result = $this->otpService->generateAndSend($user, $channel, $identifier, 'activation');

        if (!$result['sent']) {
            return back()->with('error', 'Gagal mengirim kode OTP. Silakan coba lagi.')->withInput();
        }

        // Store activation data in session
        session([
            'otp_activation' => [
                'channel' => $channel,
                'identifier' => $identifier,
                'sent_at' => now()->timestamp,
            ]
        ]);

        return redirect()->route('otp.verify-activation')
            ->with('success', 'Kode OTP telah dikirim ke ' . ($channel === 'email' ? 'email' : 'Telegram') . ' Anda.');
    }

    /**
     * Show OTP verification form for activation
     */
    public function showVerifyActivationForm()
    {
        if (!session('otp_activation')) {
            return redirect()->route('otp.activate')
                ->with('error', 'Silakan minta kode OTP terlebih dahulu.');
        }

        $activation = session('otp_activation');
        
        return view('auth.otp.verify-activation', [
            'page_title' => 'Verifikasi OTP',
            'page_description' => 'Masukkan kode OTP untuk mengaktifkan fitur',
            'channel' => $activation['channel'],
            'identifier' => $activation['identifier'],
        ]);
    }

    /**
     * Verify and activate OTP
     */
    public function verifyActivation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|numeric|digits:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (!session('otp_activation')) {
            return redirect()->route('otp.activate')
                ->with('error', 'Sesi aktivasi tidak ditemukan. Silakan mulai lagi.');
        }

        $user = Auth::user();
        $activation = session('otp_activation');
        
        // Verify OTP
        $result = $this->otpService->verify($user, $request->otp, 'activation');

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        // Activate OTP for user
        $user->update([
            'otp_enabled' => true,
            'otp_channel' => $activation['channel'],
            'otp_identifier' => $activation['identifier'],
            'telegram_chat_id' => $activation['channel'] === 'telegram' ? $activation['identifier'] : null,
        ]);

        // Clear session
        session()->forget('otp_activation');

        return redirect()->route('dashboard')
            ->with('success', 'OTP berhasil diaktifkan! Anda sekarang dapat menggunakan OTP untuk login.');
    }

    /**
     * Deactivate OTP
     */
    public function deactivate(Request $request)
    {
        $user = Auth::user();
        
        $user->update([
            'otp_enabled' => false,
            'otp_channel' => null,
            'otp_identifier' => null,
            'telegram_chat_id' => null,
        ]);

        return back()->with('success', 'OTP berhasil dinonaktifkan.');
    }

    /**
     * Show OTP login form
     */
    public function showLoginForm(Request $request)
    {
        $profil = $this->getProfilData();
        $sebutan_wilayah = $this->getSebutanWilayah();
        
        return view('auth.otp.login', compact('profil', 'sebutan_wilayah'));
    }

    /**
     * Request OTP for login
     */
    public function requestLoginOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Find user by email or username
        $user = User::where('email', $request->identifier)
            ->orWhere('name', $request->identifier)
            ->where('status', 1)
            ->first();

        if (!$user) {
            return back()->with('error', 'Pengguna tidak ditemukan atau tidak aktif.')->withInput();
        }

        if (!$user->otp_enabled) {
            return back()->with('error', 'OTP belum diaktifkan untuk pengguna ini. Silakan login dengan password.')->withInput();
        }

        // Generate and send OTP
        $result = $this->otpService->generateAndSend(
            $user,
            $user->otp_channel,
            $user->otp_identifier,
            'login'
        );

        if (!$result['sent']) {
            return back()->with('error', 'Gagal mengirim kode OTP. Silakan coba lagi.')->withInput();
        }

        // Store login attempt in session
        session([
            'otp_login' => [
                'user_id' => $user->id,
                'sent_at' => now()->timestamp,
            ]
        ]);

        return redirect()->route('otp.verify-login')
            ->with('success', 'Kode OTP telah dikirim ke ' . ($user->otp_channel === 'email' ? 'email' : 'Telegram') . ' Anda.');
    }

    /**
     * Show OTP verification form for login
     */
    public function showVerifyLoginForm()
    {
        if (!session('otp_login')) {
            return redirect()->route('otp.login')
                ->with('error', 'Silakan minta kode OTP terlebih dahulu.');
        }

        $profil = $this->getProfilData();
        $sebutan_wilayah = $this->getSebutanWilayah();

        return view('auth.otp.verify-login', compact('profil', 'sebutan_wilayah'));
    }

    /**
     * Verify OTP and login
     */
    public function loginWithOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|numeric|digits:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (!session('otp_login')) {
            return redirect()->route('otp.login')
                ->with('error', 'Sesi login tidak ditemukan. Silakan mulai lagi.');
        }

        $loginData = session('otp_login');
        $user = User::find($loginData['user_id']);

        if (!$user) {
            session()->forget('otp_login');
            return redirect()->route('otp.login')
                ->with('error', 'Pengguna tidak ditemukan.');
        }

        // Verify OTP
        $result = $this->otpService->verify($user, $request->otp, 'login');

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        // Login user
        Auth::login($user, $request->filled('remember'));

        // Update last login
        $user->update(['last_login' => now()]);

        // Clear session
        session()->forget('otp_login');

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Login berhasil!');
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        $purpose = $request->input('purpose', 'login');
        
        if ($purpose === 'activation') {
            if (!session('otp_activation')) {
                return response()->json(['success' => false, 'message' => 'Sesi tidak ditemukan'], 400);
            }
            
            $activation = session('otp_activation');
            $user = Auth::user();
            
            $result = $this->otpService->generateAndSend(
                $user,
                $activation['channel'],
                $activation['identifier'],
                'activation'
            );
            
            if ($result['sent']) {
                session(['otp_activation.sent_at' => now()->timestamp]);
                return response()->json(['success' => true, 'message' => 'Kode OTP baru telah dikirim']);
            }
        } elseif ($purpose === 'login') {
            if (!session('otp_login')) {
                return response()->json(['success' => false, 'message' => 'Sesi tidak ditemukan'], 400);
            }
            
            $loginData = session('otp_login');
            $user = User::find($loginData['user_id']);
            
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Pengguna tidak ditemukan'], 400);
            }
            
            $result = $this->otpService->generateAndSend(
                $user,
                $user->otp_channel,
                $user->otp_identifier,
                'login'
            );
            
            if ($result['sent']) {
                session(['otp_login.sent_at' => now()->timestamp]);
                return response()->json(['success' => true, 'message' => 'Kode OTP baru telah dikirim']);
            }
        }
        
        return response()->json(['success' => false, 'message' => 'Gagal mengirim ulang kode OTP'], 500);
    }

    /**
     * Get profil data for views
     */
    private function getProfilData()
    {
        return \App\Models\Profil::first() ?? (object)[
            'file_logo' => null,
            'nama_kabupaten' => 'Kabupaten',
            'nama_kecamatan' => 'Kecamatan',
        ];
    }

    /**
     * Get sebutan wilayah
     */
    private function getSebutanWilayah()
    {
        return $this->settings['sebutan_desa'] ?? 'Desa';
    }
}
