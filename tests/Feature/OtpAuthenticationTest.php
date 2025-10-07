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
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OtpAuthenticationTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'name' => 'Test User',
            'password' => bcrypt('password'),
            'status' => 1,
        ]);
    }

    public function test_otp_activation_page_is_displayed_for_authenticated_users()
    {
        $response = $this->actingAs($this->user)->get(route('otp.activate'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.otp.activate');
    }

    public function test_otp_activation_page_redirects_guests()
    {
        $response = $this->get(route('otp.activate'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_request_otp_activation_via_email()
    {
        Mail::fake();

        $response = $this->actingAs($this->user)->post(route('otp.request-activation'), [
            'channel' => 'email',
            'identifier' => 'test@example.com',
        ]);

        $response->assertRedirect(route('otp.verify-activation'));
        $response->assertSessionHas('success');

        // Check OTP token created
        $this->assertDatabaseHas('otp_tokens', [
            'user_id' => $this->user->id,
            'channel' => 'email',
            'identifier' => 'test@example.com',
            'purpose' => 'activation',
        ]);
    }

    public function test_user_can_activate_otp_with_valid_code()
    {
        $otpService = new OtpService();
        $result = $otpService->generateAndSave($this->user, 'email', 'test@example.com', 'activation');
        
        session([
            'otp_activation' => [
                'channel' => 'email',
                'identifier' => 'test@example.com',
                'sent_at' => now()->timestamp,
            ]
        ]);

        $response = $this->actingAs($this->user)->post(route('otp.verify-activation'), [
            'otp' => (string) $result['otp'],
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        // Check user OTP enabled
        $this->user->refresh();
        $this->assertTrue($this->user->otp_enabled);
        $this->assertEquals('email', $this->user->otp_channel);
        $this->assertEquals('test@example.com', $this->user->otp_identifier);
    }

    public function test_user_cannot_activate_otp_with_invalid_code()
    {
        $otpService = new OtpService();
        $otpService->generateAndSave($this->user, 'email', 'test@example.com', 'activation');
        
        session([
            'otp_activation' => [
                'channel' => 'email',
                'identifier' => 'test@example.com',
                'sent_at' => now()->timestamp,
            ]
        ]);

        $response = $this->actingAs($this->user)->post(route('otp.verify-activation'), [
            'otp' => '000000',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        // Check user OTP still disabled
        $this->user->refresh();
        $this->assertFalse($this->user->otp_enabled);
    }

    public function test_user_can_deactivate_otp()
    {
        // Enable OTP first
        $this->user->update([
            'otp_enabled' => true,
            'otp_channel' => 'email',
            'otp_identifier' => 'test@example.com',
        ]);

        $response = $this->actingAs($this->user)->get(route('otp.deactivate'));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check user OTP disabled
        $this->user->refresh();
        $this->assertFalse($this->user->otp_enabled);
        $this->assertNull($this->user->otp_channel);
        $this->assertNull($this->user->otp_identifier);
    }

    public function test_otp_login_page_is_displayed()
    {
        $response = $this->get(route('otp.login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.otp.login');
    }

    public function test_user_can_request_otp_for_login()
    {
        // Enable OTP for user
        $this->user->update([
            'otp_enabled' => true,
            'otp_channel' => 'email',
            'otp_identifier' => 'test@example.com',
        ]);

        Mail::fake();

        $response = $this->post(route('otp.request-login'), [
            'identifier' => 'test@example.com',
        ]);

        $response->assertRedirect(route('otp.verify-login'));
        $response->assertSessionHas('success');

        // Check OTP token created
        $this->assertDatabaseHas('otp_tokens', [
            'user_id' => $this->user->id,
            'channel' => 'email',
            'purpose' => 'login',
        ]);
    }

    public function test_user_cannot_request_otp_if_not_enabled()
    {
        $response = $this->post(route('otp.request-login'), [
            'identifier' => 'test@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_user_can_login_with_valid_otp()
    {
        // Enable OTP for user
        $this->user->update([
            'otp_enabled' => true,
            'otp_channel' => 'email',
            'otp_identifier' => 'test@example.com',
        ]);

        $otpService = new OtpService();
        $result = $otpService->generateAndSave($this->user, 'email', 'test@example.com', 'login');
        
        session([
            'otp_login' => [
                'user_id' => $this->user->id,
                'sent_at' => now()->timestamp,
            ]
        ]);

        $response = $this->post(route('otp.verify-login'), [
            'otp' => (string) $result['otp'],
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($this->user);
    }

    public function test_user_cannot_login_with_invalid_otp()
    {
        // Enable OTP for user
        $this->user->update([
            'otp_enabled' => true,
            'otp_channel' => 'email',
            'otp_identifier' => 'test@example.com',
        ]);

        $otpService = new OtpService();
        $otpService->generateAndSave($this->user, 'email', 'test@example.com', 'login');
        
        session([
            'otp_login' => [
                'user_id' => $this->user->id,
                'sent_at' => now()->timestamp,
            ]
        ]);

        $response = $this->post(route('otp.verify-login'), [
            'otp' => '000000',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_otp_verification_requires_six_digit_code()
    {
        session([
            'otp_activation' => [
                'channel' => 'email',
                'identifier' => 'test@example.com',
                'sent_at' => now()->timestamp,
            ]
        ]);

        $response = $this->actingAs($this->user)->post(route('otp.verify-activation'), [
            'otp' => '123',
        ]);

        $response->assertSessionHasErrors('otp');
    }

    public function test_email_validation_for_otp_activation()
    {
        $response = $this->actingAs($this->user)->post(route('otp.request-activation'), [
            'channel' => 'email',
            'identifier' => 'invalid-email',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('identifier');
    }

    public function test_otp_token_relationship_with_user()
    {
        $otpService = new OtpService();
        $result = $otpService->generateAndSave($this->user, 'email', 'test@example.com', 'login');

        $this->assertInstanceOf(OtpToken::class, $this->user->otpTokens()->first());
        $this->assertEquals($this->user->id, $result['token']->user->id);
    }
}
