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

class TwoFactorAuthenticationTest extends TestCase
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

    public function test_2fa_settings_page_is_displayed_for_authenticated_users()
    {
        $response = $this->actingAs($this->user)->get(route('2fa.settings'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.2fa.settings');
    }

    public function test_2fa_settings_page_redirects_guests()
    {
        $response = $this->get(route('2fa.settings'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_save_2fa_settings_with_email_channel()
    {
        $response = $this->actingAs($this->user)->post(route('2fa.save-settings'), [
            'channel' => 'email',
            'identifier' => 'test@example.com',
        ]);

        $response->assertRedirect(route('2fa.activate'));
        $response->assertSessionHas('success');

        // Check user 2FA settings updated
        $this->user->refresh();
        $this->assertEquals('email', $this->user->otp_channel);
        $this->assertEquals('test@example.com', $this->user->otp_identifier);
        $this->assertNull($this->user->telegram_chat_id);
    }

    public function test_user_can_save_2fa_settings_with_telegram_channel()
    {
        $response = $this->actingAs($this->user)->post(route('2fa.save-settings'), [
            'channel' => 'telegram',
            'identifier' => '123456789',
        ]);

        $response->assertRedirect(route('2fa.activate'));
        $response->assertSessionHas('success');

        // Check user 2FA settings updated
        $this->user->refresh();
        $this->assertEquals('telegram', $this->user->otp_channel);
        $this->assertEquals('123456789', $this->user->otp_identifier);
        $this->assertEquals('123456789', $this->user->telegram_chat_id);
    }

    public function test_user_cannot_save_2fa_settings_with_invalid_email()
    {
        $response = $this->actingAs($this->user)->post(route('2fa.save-settings'), [
            'channel' => 'email',
            'identifier' => 'invalid-email',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('identifier');
    }

    public function test_user_cannot_save_2fa_settings_without_channel()
    {
        $response = $this->actingAs($this->user)->post(route('2fa.save-settings'), [
            'identifier' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('channel');
    }

    public function test_user_cannot_save_2fa_settings_without_identifier()
    {
        $response = $this->actingAs($this->user)->post(route('2fa.save-settings'), [
            'channel' => 'email',
        ]);

        $response->assertSessionHasErrors('identifier');
    }

    public function test_2fa_activation_page_is_displayed_for_authenticated_users()
    {
        $response = $this->actingAs($this->user)->get(route('2fa.activate'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.2fa.activate');
    }

    public function test_2fa_activation_page_redirects_guests()
    {
        $response = $this->get(route('2fa.activate'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_request_2fa_activation_with_email()
    {
        // Set up user 2FA settings first
        $this->user->update([
            'otp_channel' => 'email',
            'otp_identifier' => 'test@example.com',
        ]);

        Mail::fake();

        $response = $this->actingAs($this->user)->post(route('2fa.request-activation'));

        $response->assertRedirect(route('2fa.verify-activation'));
        $response->assertSessionHas('success');
        $response->assertSessionHas('2fa_activation');

        // Check OTP token created
        $this->assertDatabaseHas('otp_tokens', [
            'user_id' => $this->user->id,
            'channel' => 'email',
            'identifier' => 'test@example.com',
            'purpose' => '2fa_activation',
        ]);
    }

    public function test_user_cannot_request_2fa_activation_without_settings()
    {
        $response = $this->actingAs($this->user)->post(route('2fa.request-activation'));

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_2fa_verify_activation_page_is_displayed_with_session()
    {
        session([
            '2fa_activation' => [
                'channel' => 'email',
                'identifier' => 'test@example.com',
                'sent_at' => now()->timestamp,
            ]
        ]);

        $response = $this->actingAs($this->user)->get(route('2fa.verify-activation'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.2fa.verify-activation');
    }

    public function test_2fa_verify_activation_page_redirects_without_session()
    {
        $response = $this->actingAs($this->user)->get(route('2fa.verify-activation'));

        $response->assertRedirect(route('2fa.activate'));
        $response->assertSessionHas('error');
    }

    public function test_user_can_activate_2fa_with_valid_code()
    {
        // First set up the user with 2FA settings
        $this->user->update([
            'otp_channel' => 'email',
            'otp_identifier' => 'test@example.com',
        ]);

        $otpService = new OtpService();
        $result = $otpService->generateAndSave($this->user, 'email', 'test@example.com', '2fa_activation');

        session([
            '2fa_activation' => [
                'channel' => 'email',
                'identifier' => 'test@example.com',
                'sent_at' => now()->timestamp,
            ]
        ]);

        $response = $this->actingAs($this->user)->post(route('2fa.verify-activation'), [
            'otp' => (string) $result['otp'],
        ]);

        $response->assertRedirect(route('2fa.activate'));
        $response->assertSessionHas('success');

        // Check user 2FA enabled
        $this->user->refresh();
        $this->assertTrue((bool) $this->user->two_fa_enabled);
        $this->assertEquals('email', $this->user->otp_channel);
        $this->assertEquals('test@example.com', $this->user->otp_identifier);
    }

    public function test_user_cannot_activate_2fa_with_invalid_code()
    {
        // First set up the user with 2FA settings
        $this->user->update([
            'otp_channel' => 'email',
            'otp_identifier' => 'test@example.com',
        ]);

        $otpService = new OtpService();
        $otpService->generateAndSave($this->user, 'email', 'test@example.com', '2fa_activation');

        session([
            '2fa_activation' => [
                'channel' => 'email',
                'identifier' => 'test@example.com',
                'sent_at' => now()->timestamp,
            ]
        ]);

        $response = $this->actingAs($this->user)->post(route('2fa.verify-activation'), [
            'otp' => '000000',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        // Check user 2FA still disabled
        $this->user->refresh();
        $this->assertFalse($this->user->two_fa_enabled);
    }

    public function test_user_cannot_activate_2fa_without_session()
    {
        // First set up the user with 2FA settings
        $this->user->update([
            'otp_channel' => 'email',
            'otp_identifier' => 'test@example.com',
        ]);

        $response = $this->actingAs($this->user)->post(route('2fa.verify-activation'), [
            'otp' => '123456',
        ]);

        $response->assertRedirect(route('2fa.activate'));
        $response->assertSessionHas('error');
    }

    public function test_2fa_activation_requires_six_digit_code()
    {
        session([
            '2fa_activation' => [
                'channel' => 'email',
                'identifier' => 'test@example.com',
                'sent_at' => now()->timestamp,
            ]
        ]);

        $response = $this->actingAs($this->user)->post(route('2fa.verify-activation'), [
            'otp' => '123',
        ]);

        $response->assertSessionHasErrors('otp');
    }

    public function test_user_can_deactivate_2fa()
    {
        // Enable 2FA first
        $this->user->update([
            'two_fa_enabled' => true,
            'otp_channel' => 'email',
            'otp_identifier' => 'test@example.com',
        ]);

        $response = $this->actingAs($this->user)->get(route('2fa.deactivate'));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check user 2FA disabled
        $this->user->refresh();
        $this->assertFalse($this->user->two_fa_enabled);
    }

    public function test_2fa_login_page_is_displayed_with_session()
    {
        $user = $this->user;

        session([
            'two-factor:auth' => [
                'id' => $user->id,
                'remember' => false,
            ]
        ]);

        $response = $this->get(route('2fa.verify-login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.2fa.verify-login');
    }

    public function test_2fa_login_page_redirects_without_session()
    {
        $response = $this->get(route('2fa.verify-login'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    }

    public function test_user_can_login_with_valid_2fa_code()
    {
        // Enable 2FA for user
        $this->user->update([
            'two_fa_enabled' => true,
            'otp_channel' => 'email',
            'otp_identifier' => 'test@example.com',
        ]);

        $otpService = new OtpService();
        $result = $otpService->generateAndSave($this->user, 'email', 'test@example.com', '2fa_login');

        session([
            'two-factor:auth' => [
                'id' => $this->user->id,
                'remember' => false,
            ]
        ]);

        $response = $this->post(route('2fa.verify-login'), [
            'otp' => (string) $result['otp'],
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($this->user);
    }

    public function test_user_cannot_login_with_invalid_2fa_code()
    {
        // Enable 2FA for user
        $this->user->update([
            'two_fa_enabled' => true,
            'otp_channel' => 'email',
            'otp_identifier' => 'test@example.com',
        ]);

        $otpService = new OtpService();
        $otpService->generateAndSave($this->user, 'email', 'test@example.com', '2fa_login');

        session([
            'two-factor:auth' => [
                'id' => $this->user->id,
                'remember' => false,
            ]
        ]);

        $response = $this->post(route('2fa.verify-login'), [
            'otp' => '000000',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_user_cannot_login_2fa_without_session()
    {
        $response = $this->post(route('2fa.verify-login'), [
            'otp' => '123456',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_2fa_login_requires_six_digit_code()
    {
        session([
            'two-factor:auth' => [
                'id' => $this->user->id,
                'remember' => false,
            ]
        ]);

        $response = $this->post(route('2fa.verify-login'), [
            'otp' => '123',
        ]);

        $response->assertSessionHasErrors('otp');
    }
}