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
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TwoFactorController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        parent::__construct();
        // Remove auth middleware for verify login methods
        $this->middleware('auth')->except(['showVerifyLoginForm', 'verifyLogin']);
        $this->otpService = $otpService;
    }

    /**
     * Combined OTP & 2FA management page
     */
    public function index()
    {
        $user = Auth::user();
        $needsSetup = empty($user->otp_channel) || empty($user->otp_identifier);

        return view('auth.otp2fa.index', [
            'page_title' => 'OTP & 2FA',
            'page_description' => 'Pengaturan dan status OTP serta Two-Factor Authentication',
            'user' => $user,
            'needsSetup' => $needsSetup,
        ]);
    }

    /**
     * Show 2FA settings form
     */
    public function showSettingsForm()
    {
        $user = Auth::user();

        return view('auth.2fa.settings', [
            'page_title' => 'Pengaturan 2FA',
            'page_description' => 'Konfigurasi Two-Factor Authentication',
            'user' => $user,
        ]);
    }

    /**
     * Save 2FA settings (email/telegram contact)
     */
    public function saveSettings(Request $request)
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
            // For Telegram, we'll store the identifier but validate later during activation
        }

        // Save 2FA contact information (but don't activate yet)
        $user->update([
            'otp_channel' => $channel,
            'otp_identifier' => $identifier
        ]);

    return redirect()->route('otp2fa.index')->with('success', 'Pengaturan 2FA berhasil disimpan. Silakan aktifkan 2FA untuk mulai menggunakannya.');
    }

    /**
     * Show 2FA activation form
     */
    public function showActivationForm()
    {
        $user = Auth::user();

        // Check if user has set up 2FA contact information
        $needsSetup = empty($user->otp_channel) || empty($user->otp_identifier);

        return view('auth.2fa.activate', [
            'page_title' => 'Aktivasi 2FA',
            'page_description' => 'Aktifkan Two-Factor Authentication',
            'user' => $user,
            'needsSetup' => $needsSetup,
        ]);
    }

    /**
     * Request OTP for 2FA activation
     */
    public function requestActivation(Request $request)
    {
        $user = Auth::user();

        // Check if user has set up 2FA contact information
        if (empty($user->otp_channel) || empty($user->otp_identifier)) {
            return back()->with('error', 'Silakan atur metode verifikasi terlebih dahulu sebelum mengaktifkan 2FA.');
        }

        // Verify Telegram chat ID if using Telegram
        if ($user->otp_channel === 'telegram') {
            if (!$this->otpService->verifyTelegramChatId($user->otp_identifier)) {
                return back()->with('error', 'Chat ID Telegram tidak valid. Pastikan Anda sudah mengirim /start ke bot.');
            }
        }

        // Generate and send OTP
        $result = $this->otpService->generateAndSend($user, $user->otp_channel, $user->otp_identifier, '2fa_activation');

        if (!$result['sent']) {
            return back()->with('error', 'Gagal mengirim kode OTP. Silakan coba lagi.');
        }

        // Store activation data in session
        session([
            '2fa_activation' => [
                'channel' => $user->otp_channel,
                'identifier' => $user->otp_identifier,
                'sent_at' => now()->timestamp,
            ]
        ]);

        return redirect()->route('2fa.verify-activation')
            ->with('success', 'Kode OTP telah dikirim ke ' . ($user->otp_channel === 'email' ? 'email' : 'Telegram') . ' Anda.');
    }

    /**
     * Show OTP verification form for 2FA activation
     */
    public function showVerifyActivationForm()
    {
        if (!session('2fa_activation')) {
            return redirect()->route('otp2fa.index')
                ->with('error', 'Silakan minta kode OTP terlebih dahulu.');
        }

        $activation = session('2fa_activation');

        return view('auth.2fa.verify-activation', [
            'page_title' => 'Verifikasi 2FA',
            'page_description' => 'Masukkan kode OTP untuk mengaktifkan 2FA',
            'channel' => $activation['channel'],
            'identifier' => $activation['identifier'],
        ]);
    }

    /**
     * Verify and activate 2FA
     */
    public function verifyActivation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|numeric|digits:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (!session('2fa_activation')) {
            return redirect()->route('otp2fa.index')
                ->with('error', 'Sesi aktivasi tidak ditemukan. Silakan mulai lagi.');
        }

        $user = Auth::user();
        $activation = session('2fa_activation');

        // Verify OTP
        $result = $this->otpService->verify($user, $request->otp, '2fa_activation');

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        // Activate 2FA for user
        $user->update([
            // 'otp_enabled' => true,
            'two_fa_enabled' => true,
        ]);

        // Clear session
        session()->forget('2fa_activation');

        return redirect()->route('otp2fa.index')
            ->with('success', 'Two-Factor Authentication berhasil diaktifkan!');
    }

    /**
     * Deactivate 2FA
     */
    public function deactivate(Request $request)
    {
        $user = Auth::user();

        $user->update([
            // 'otp_enabled' => false,
            'two_fa_enabled' => false,
        ]);

        return back()->with('success', 'Two-Factor Authentication berhasil dinonaktifkan.');
    }

    /**
     * Show 2FA verification form for login
     */
    public function showVerifyLoginForm()
    {
        // Check if we have a 2FA login session
        if (!session('two-factor:auth')) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $authData = session('two-factor:auth');
        $user = User::find($authData['id']);

        if (!$user) {
            session()->forget('two-factor:auth');
            return redirect()->route('login')
                ->with('error', 'Pengguna tidak ditemukan.');
        }

        return view('auth.2fa.verify-login', [
            'page_title' => 'Verifikasi 2FA',
            'page_description' => 'Masukkan kode 2FA untuk melanjutkan login',
            'user' => $user,
        ]);
    }

    /**
     * Verify 2FA code and complete login
     */
    public function verifyLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|numeric|digits:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if we have a 2FA login session
        if (!session('two-factor:auth')) {
            return redirect()->route('login')
                ->with('error', 'Sesi login tidak ditemukan. Silakan mulai lagi.');
        }

        $authData = session('two-factor:auth');
        $user = User::find($authData['id']);

        if (!$user) {
            session()->forget('two-factor:auth');
            return redirect()->route('login')
                ->with('error', 'Pengguna tidak ditemukan.');
        }

        // Verify OTP for 2FA login
        $result = $this->otpService->verify($user, $request->otp, '2fa_login');

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        // Login user
        Auth::login($user, $authData['remember'] ?? false);

        // Update last login
        $user->update(['last_login' => now()]);

        // Clear 2FA session
        session()->forget('two-factor:auth');

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Login berhasil!');
    }
}