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
use App\Models\SettingAplikasi;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Mews\Captcha\Facades\Captcha;
use Tests\TestCase;

class TwoFactorLoginTest extends TestCase
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

        // Disable captcha for testing
        SettingAplikasi::updateOrCreate(
            ['key' => 'google_recaptcha'],
            ['value' => 0]
        );

        Captcha::shouldReceive('display')
            ->andReturn('<input type="hidden" name="captcha" value="1" />');

        // Create a test user with 2FA enabled
        $this->user = User::factory()->create([
            'email' => '2fa-test@example.com',
            'name' => '2FA Test User',
            'password' => Hash::make('password'),
            'status' => 1,
            'two_fa_enabled' => true,
            'otp_verified' => true,
            'otp_channel' => 'email',
        ]);
    }

    public function test_user_with_2fa_redirected_to_verification_after_password_login()
    {
        // Login with password
        $response = $this->post(route('login'), [
            'email' => $this->user->email,
            'password' => 'password',
            'captcha' => '1',
        ]);

        // Should redirect to 2FA verification page
        $response->assertRedirect(route('2fa.verify-login'));
        $response->assertSessionHas('success');

        // User should not be authenticated yet
        $this->assertGuest();

        // Check 2FA session is set
        $this->assertNotNull(session('two-factor:auth'));
        $this->assertEquals($this->user->id, session('two-factor:auth')['id']);

        // Check OTP token created for 2FA
        $this->assertDatabaseHas('otp_tokens', [
            'user_id' => $this->user->id,
            'channel' => 'email',
            'purpose' => '2fa_login',
        ]);
    }

    public function test_user_can_complete_2fa_login_with_valid_code()
    {
        // Simulate first login step (password)
        session([
            'two-factor:auth' => [
                'id' => $this->user->id,
                'email' => $this->user->email,
            ]
        ]);

        // Generate 2FA code
        $result = $this->otpService->generateAndSave(
            $this->user,
            'email',
            $this->user->email,
            '2fa_login'
        );

        $code = $result['otp'];

        // Submit 2FA code
        $response = $this->post('/2fa/verify-login', [
            'otp' => $code,
        ]);

        // Should redirect to dashboard
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        // User should now be authenticated
        $this->assertAuthenticatedAs($this->user);

        // Check 2FA token is deleted after use
        $this->assertDatabaseMissing('otp_tokens', [
            'user_id' => $this->user->id,
            'purpose' => '2fa_login',
        ]);

        // Check 2FA session is cleared
        $this->assertNull(session('two-factor:auth'));
    }

    public function test_user_cannot_complete_2fa_with_invalid_code()
    {
        // Simulate first login step
        session([
            'two-factor:auth' => [
                'id' => $this->user->id,
                'email' => $this->user->email,
            ]
        ]);

        // Generate 2FA code
        $this->otpService->generateAndSave(
            $this->user,
            'email',
            $this->user->email,
            '2fa_login'
        );

        // Try with wrong code
        $response = $this->post('/2fa/verify-login', [
            'otp' => '000000',
        ]);

        // Should redirect back with error
        $response->assertRedirect();
        $response->assertSessionHas('error');

        // User should not be authenticated
        $this->assertGuest();
    }

    public function test_2fa_login_requires_valid_session()
    {
        // Try to verify 2FA without session
        $response = $this->post('/2fa/verify-login', [
            'otp' => '123456',
        ]);

        // Should redirect to login page
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    }

    public function test_2fa_code_expires_after_timeout()
    {
        // Simulate first login step
        session([
            'two-factor:auth' => [
                'id' => $this->user->id,
                'email' => $this->user->email,
            ]
        ]);

        // Generate 2FA code
        $result = $this->otpService->generateAndSave(
            $this->user,
            'email',
            $this->user->email,
            '2fa_login'
        );

        $code = $result['otp'];

        // Manually expire the token
        $token = OtpToken::where('user_id', $this->user->id)
            ->where('purpose', '2fa_login')
            ->first();
        $token->expires_at = now()->subMinutes(10);
        $token->save();

        // Try to verify with expired code
        $response = $this->post('/2fa/verify-login', [
            'otp' => $code,
        ]);

        // Should fail
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_user_without_2fa_can_login_normally()
    {
        // Create user without 2FA
        $userWithout2FA = User::factory()->create([
            'email' => 'no-2fa@example.com',
            'name' => 'No 2FA User',
            'password' => Hash::make('password'),
            'status' => 1,
            'two_fa_enabled' => false,
        ]);

        // Login with password
        $response = $this->post(route('login'), [
            'email' => $userWithout2FA->email,
            'password' => 'password',
            'captcha' => '1',
        ]);

        // Should redirect directly to dashboard (no 2FA step)
        $response->assertRedirect();

        // User should be authenticated immediately
        $this->assertAuthenticatedAs($userWithout2FA);

        // No 2FA session should be created
        $this->assertNull(session('two-factor:auth'));
    }

    public function test_2fa_fails_after_max_attempts()
    {
        // Simulate first login step
        session([
            'two-factor:auth' => [
                'id' => $this->user->id,
                'email' => $this->user->email,
            ]
        ]);

        // Generate 2FA code
        $result = $this->otpService->generateAndSave(
            $this->user,
            'email',
            $this->user->email,
            '2fa_login'
        );

        // Try 5 times with wrong code
        for ($i = 0; $i < 5; $i++) {
            $this->post('/2fa/verify-login', [
                'otp' => '000000',
            ]);
        }

        // Next attempt should fail even with correct code
        $response = $this->post('/2fa/verify-login', [
            'otp' => $result['otp'],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_complete_2fa_login_flow()
    {
        // Step 1: Login with password
        $response = $this->post(route('login'), [
            'email' => $this->user->email,
            'password' => 'password',
            'captcha' => '1',
        ]);

        $response->assertRedirect(route('2fa.verify-login'));
        $this->assertGuest();

        // Step 2: Get the 2FA code from database
        $token = OtpToken::where('user_id', $this->user->id)
            ->where('purpose', '2fa_login')
            ->first();

        $this->assertNotNull($token);

        // We need to get the plain OTP, so let's verify with OTP service
        $result = $this->otpService->generateAndSave(
            $this->user,
            'email',
            $this->user->email,
            '2fa_login'
        );

        // Step 3: Submit 2FA code
        $response = $this->post('/2fa/verify-login', [
            'otp' => $result['otp'],
        ]);

        // Step 4: Should be logged in and redirected
        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($this->user);
    }
}
