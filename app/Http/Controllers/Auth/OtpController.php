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
     * Combined OTP & 2FA management page
     */
    public function index()
    {
        $user = Auth::user();
        $needsSetup = false; // No longer need setup, use email and telegram_id from user
        $otpEnabled = $user->otp_enabled;
        $twoFaEnabled = $user->two_fa_enabled;

        return view('auth.otp2fa.index', [
            'page_title' => 'OTP & 2FA',
            'page_description' => 'Pengaturan dan status OTP serta Two-Factor Authentication',
            'user' => $user,
            'needsSetup' => $needsSetup,
            'otpEnabled' => $otpEnabled,
            'twoFaEnabled' => $twoFaEnabled,
        ]);
    }

    /**
     * Show settings form
     */
    public function showSettingsForm()
    {
        $user = Auth::user();

        return view('auth.otp2fa.settings', [
            'page_title' => 'Pengaturan OTP & 2FA',
            'page_description' => 'Konfigurasi OTP dan Two-Factor Authentication',
            'user' => $user,
        ]);
    }

     /**
     * Save 2FA settings (email/telegram contact) and send verification code
     */
    public function saveSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'channel' => 'required|in:email,telegram',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $channel = $request->channel;
        
        // Check if channel changed, reset verified status
        $channelChanged = $user->otp_channel !== $channel;
        
        // Validate that the required identifier exists
        if ($channel === 'email') {
            if (empty($user->email)) {
                return back()->with('error', 'Email belum diatur. Silakan perbarui email Anda di menu User terlebih dahulu.');
            }
            $identifier = $user->email;
            
            // Validate email format
            $emailValidator = Validator::make(['email' => $identifier], [
                'email' => 'required|email',
            ]);
            
            if ($emailValidator->fails()) {
                return back()->with('error', 'Format email tidak valid. Silakan perbarui email Anda di menu User.');
            }
        } elseif ($channel === 'telegram') {
            if (empty($user->telegram_id)) {
                return back()->with('error', 'Telegram ID belum diatur. Silakan perbarui Telegram ID Anda di menu User terlebih dahulu.');
            }
            $identifier = $user->telegram_id;
            
            // Verify telegram chat ID
            if (!$this->otpService->verifyTelegramChatId($identifier)) {
                return back()->with('error', 'Telegram ID tidak valid. Silakan periksa kembali Telegram ID Anda di menu User.');
            }
        }

        // Update user channel and reset verified if changed
        $updateData = [
            'otp_channel' => $channel,
        ];
        
        if ($channelChanged) {
            $updateData['otp_verified'] = false;
            $updateData['otp_enabled'] = false;
            $updateData['two_fa_enabled'] = false;
        }
        
        $user->update($updateData);

        // Send verification code to confirm the identifier works
        $result = $this->otpService->generateAndSend($user, $channel, $identifier, 'settings_verification');

        if (!$result['sent']) {
            return back()->with('error', 'Gagal mengirim kode verifikasi. Silakan periksa ' . ($channel === 'email' ? 'email' : 'Telegram ID') . ' Anda dan coba lagi.');
        }

        // Store verification session
        session([
            'settings_verification' => [
                'channel' => $channel,
                'identifier' => $identifier,
                'sent_at' => now()->timestamp,
            ]
        ]);

        return redirect()->route('otp2fa.verify-settings')
            ->with('success', 'Kode verifikasi telah dikirim ke ' . ($channel === 'email' ? 'email' : 'Telegram') . ' Anda. Silakan masukkan kode untuk mengonfirmasi pengaturan.');
    }

    /**
     * Show settings verification form
     */
    public function showVerifySettingsForm()
    {
        if (!session('settings_verification')) {
            return redirect()->route('otp2fa.settings')
                ->with('error', 'Silakan simpan pengaturan terlebih dahulu.');
        }

        $verification = session('settings_verification');
        
        return view('auth.otp2fa.verify-settings', [
            'page_title' => 'Verifikasi Pengaturan OTP & 2FA',
            'page_description' => 'Masukkan kode verifikasi untuk mengonfirmasi pengaturan',
            'channel' => $verification['channel'],
            'identifier' => $verification['identifier'],
        ]);
    }

    /**
     * Verify settings with OTP code
     */
    public function verifySettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|numeric|digits:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (!session('settings_verification')) {
            return redirect()->route('otp2fa.settings')
                ->with('error', 'Sesi verifikasi tidak ditemukan. Silakan simpan pengaturan lagi.');
        }

        $user = Auth::user();
        
        // Verify OTP
        $result = $this->otpService->verify($user, $request->otp, 'settings_verification');

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        // Mark user as verified
        $user->update([
            'otp_verified' => true,
        ]);

        // Clear session
        session()->forget('settings_verification');

        return redirect()->route('otp2fa.index')
            ->with('success', 'Pengaturan berhasil dikonfirmasi! Sekarang Anda dapat mengaktifkan OTP atau 2FA.');
    }

    /**
     * Show OTP activation form
     */
    public function showActivationForm()
    {
        $user = Auth::user();
        // No longer need setup check
        $needsSetup = false;

        return view('auth.otp.activate', [
            'page_title' => 'Aktivasi OTP',
            'page_description' => 'Aktifkan autentikasi OTP untuk keamanan tambahan',
            'user' => $user,
            'needsSetup' => $needsSetup,
        ]);
    }

    /**
     * Request OTP for activation (directly activate without verification)
     */
    public function requestActivation(Request $request)
    {
        // Use user's configured contact (email or telegram_id)
        $user = Auth::user();

        // Check if user has verified their channel
        if (!$user->otp_verified) {
            return back()->with('error', 'Anda belum memverifikasi channel verifikasi. Silakan verifikasi di halaman pengaturan terlebih dahulu.');
        }

        if (empty($user->otp_channel)) {
            return back()->with('error', 'Silakan pilih metode verifikasi terlebih dahulu di halaman pengaturan sebelum mengaktifkan OTP.')->withInput();
        }

        // Activate OTP for user directly
        $user->update([
            'otp_enabled' => true,
        ]);

        return redirect()->route('otp2fa.index')
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

        // Get identifier based on channel
        $channel = $user->otp_channel;
        if ($channel === 'email') {
            $identifier = $user->email;
        } elseif ($channel === 'telegram') {
            $identifier = $user->telegram_id;
        } else {
            return back()->with('error', 'Metode verifikasi belum diatur.')->withInput();
        }

        // Generate and send OTP
        $result = $this->otpService->generateAndSend(
            $user,
            $channel,
            $identifier,
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
        
        if ($purpose === 'settings_verification') {
            if (!session('settings_verification')) {
                return response()->json(['success' => false, 'message' => 'Sesi tidak ditemukan'], 400);
            }
            
            $verification = session('settings_verification');
            $user = Auth::user();
            
            $result = $this->otpService->generateAndSend(
                $user,
                $verification['channel'],
                $verification['identifier'],
                'settings_verification'
            );
            
            if ($result['sent']) {
                session(['settings_verification.sent_at' => now()->timestamp]);
                return response()->json(['success' => true, 'message' => 'Kode verifikasi baru telah dikirim']);
            }
        } elseif ($purpose === 'activation') {
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
            
            // Get identifier based on channel
            $channel = $user->otp_channel;
            if ($channel === 'email') {
                $identifier = $user->email;
            } elseif ($channel === 'telegram') {
                $identifier = $user->telegram_id;
            } else {
                return response()->json(['success' => false, 'message' => 'Metode verifikasi belum diatur'], 400);
            }
            
            $result = $this->otpService->generateAndSend(
                $user,
                $channel,
                $identifier,
                'login'
            );
            
            if ($result['sent']) {
                session(['otp_login.sent_at' => now()->timestamp]);
                return response()->json(['success' => true, 'message' => 'Kode OTP baru telah dikirim']);
            }
        } elseif ($purpose === '2fa_login') {
            // Handle 2FA login resend
            if (!session('two-factor:auth')) {
                return response()->json(['success' => false, 'message' => 'Sesi tidak ditemukan'], 400);
            }
            
            $authData = session('two-factor:auth');
            $user = User::find($authData['id']);
            
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Pengguna tidak ditemukan'], 400);
            }
            
            // Get identifier based on channel
            $channel = $user->otp_channel;
            if ($channel === 'email') {
                $identifier = $user->email;
            } elseif ($channel === 'telegram') {
                $identifier = $user->telegram_id;
            } else {
                return response()->json(['success' => false, 'message' => 'Metode verifikasi belum diatur'], 400);
            }
            
            $result = $this->otpService->generateAndSend(
                $user,
                $channel,
                $identifier,
                '2fa_login'
            );
            
            if ($result['sent']) {
                return response()->json(['success' => true, 'message' => 'Kode 2FA baru telah dikirim']);
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
