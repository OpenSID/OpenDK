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

namespace Tests\Feature;

use App\Models\OtpToken;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OtpLoginTest extends TestCase
{
    use DatabaseTransactions;

    protected $otpService;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Logout any authenticated user from parent TestCase
        auth()->logout();

        $this->otpService = new OtpService();

        // Create a test user with OTP enabled
        $this->user = User::factory()->create([
            'email' => 'otp-test@example.com',
            'name' => 'OTP Test User',
            'password' => Hash::make('password'),
            'status' => 1,
            'otp_enabled' => true,
            'otp_verified' => true,
            'otp_channel' => 'email',
        ]);
    }

    public function test_user_can_request_otp_for_login()
    {
        // Request OTP
        $response = $this->post(route('otp.request-login'), [
            'identifier' => $this->user->email,
        ]);

        // Should redirect to verify form
        $response->assertRedirect(route('otp.verify-login'));
        $response->assertSessionHas('success');

        // Check OTP token created in database
        $this->assertDatabaseHas('otp_tokens', [
            'user_id' => $this->user->id,
            'channel' => 'email',
            'purpose' => 'login',
        ]);

        // Check session has login data
        $this->assertNotNull(session('otp_login'));
        $this->assertEquals($this->user->id, session('otp_login')['user_id']);
    }

    public function test_user_can_login_with_valid_otp()
    {
        // Generate OTP
        $result = $this->otpService->generateAndSave(
            $this->user,
            'email',
            $this->user->email,
            'login'
        );

        $otp = $result['otp'];

        // Set session as if user requested OTP
        session([
            'otp_login' => [
                'user_id' => $this->user->id,
                'sent_at' => now()->timestamp,
            ]
        ]);

        // Login with OTP
        $response = $this->post('/otp/verify-login', [
            'otp' => $otp,
        ]);

        // Should redirect to dashboard
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success', 'Login berhasil!');

        // Check user is authenticated
        $this->assertAuthenticatedAs($this->user);

        // Check OTP token is deleted after use
        $this->assertDatabaseMissing('otp_tokens', [
            'user_id' => $this->user->id,
            'purpose' => 'login',
        ]);
    }

    public function test_user_cannot_login_with_invalid_otp()
    {
        // Generate OTP
        $this->otpService->generateAndSave(
            $this->user,
            'email',
            $this->user->email,
            'login'
        );

        // Set session
        session([
            'otp_login' => [
                'user_id' => $this->user->id,
                'sent_at' => now()->timestamp,
            ]
        ]);

        // Try login with wrong OTP
        $response = $this->post('/otp/verify-login', [
            'otp' => '000000',
        ]);

        // Should redirect back with error
        $response->assertRedirect();
        $response->assertSessionHas('error');

        // User should not be authenticated
        $this->assertGuest();
    }

    public function test_user_without_otp_enabled_cannot_request_otp()
    {
        // Create user without OTP enabled
        $userWithoutOtp = User::factory()->create([
            'email' => 'no-otp@example.com',
            'name' => 'No OTP User',
            'status' => 1,
            'otp_enabled' => false,
        ]);

        // Try to request OTP
        $response = $this->post(route('otp.request-login'), [
            'identifier' => $userWithoutOtp->email,
        ]);

        // Should redirect back with error
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_otp_login_requires_valid_session()
    {
        // Try to login without session
        $response = $this->post('/otp/verify-login', [
            'otp' => '123456',
        ]);

        // Should redirect to OTP login page
        $response->assertRedirect(route('otp.login'));
        $response->assertSessionHas('error');
    }

    public function test_otp_expires_after_timeout()
    {
        // Generate OTP
        $result = $this->otpService->generateAndSave(
            $this->user,
            'email',
            $this->user->email,
            'login'
        );

        $otp = $result['otp'];

        // Manually expire the token
        $token = OtpToken::where('user_id', $this->user->id)->first();
        $token->expires_at = now()->subMinutes(10);
        $token->save();

        // Set session
        session([
            'otp_login' => [
                'user_id' => $this->user->id,
                'sent_at' => now()->timestamp,
            ]
        ]);

        // Try to login with expired OTP
        $response = $this->post('/otp/verify-login', [
            'otp' => $otp,
        ]);

        // Should fail
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertGuest();
    }
}
