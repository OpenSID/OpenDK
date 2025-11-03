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

namespace Tests\Unit;

use App\Models\OtpToken;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TwoFactorServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected $otpService;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->otpService = new OtpService();
        
        // Create a test user
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'name' => 'Test User',
            'status' => 1,
        ]);
    }

    public function test_generate_and_send_creates_otp_token_for_2fa_activation()
    {
        $result = $this->otpService->generateAndSend(
            $this->user,
            'email',
            'test@example.com',
            '2fa_activation'
        );

        $this->assertTrue($result['sent']);
        $this->assertArrayHasKey('token', $result);
        $this->assertInstanceOf(OtpToken::class, $result['token']);

        // Check database
        $this->assertDatabaseHas('otp_tokens', [
            'user_id' => $this->user->id,
            'channel' => 'email',
            'identifier' => 'test@example.com',
            'purpose' => '2fa_activation',
        ]);
    }

    public function test_generate_and_send_creates_otp_token_for_2fa_login()
    {
        $result = $this->otpService->generateAndSend(
            $this->user,
            'email',
            'test@example.com',
            '2fa_login'
        );

        $this->assertTrue($result['sent']);
        $this->assertArrayHasKey('token', $result);
        $this->assertInstanceOf(OtpToken::class, $result['token']);

        // Check database
        $this->assertDatabaseHas('otp_tokens', [
            'user_id' => $this->user->id,
            'channel' => 'email',
            'identifier' => 'test@example.com',
            'purpose' => '2fa_login',
        ]);
    }

    public function test_verify_otp_with_valid_code_for_2fa_activation_succeeds()
    {
        $result = $this->otpService->generateAndSave(
            $this->user,
            'email',
            'test@example.com',
            '2fa_activation'
        );

        $otp = $result['otp'];

        $verifyResult = $this->otpService->verify($this->user, (string) $otp, '2fa_activation');

        $this->assertTrue($verifyResult['success']);
        $this->assertEquals('Kode OTP berhasil diverifikasi', $verifyResult['message']);

        // Token should be deleted after successful verification
        $this->assertDatabaseMissing('otp_tokens', [
            'user_id' => $this->user->id,
            'purpose' => '2fa_activation',
        ]);
    }

    public function test_verify_otp_with_valid_code_for_2fa_login_succeeds()
    {
        $result = $this->otpService->generateAndSave(
            $this->user,
            'email',
            'test@example.com',
            '2fa_login'
        );

        $otp = $result['otp'];

        $verifyResult = $this->otpService->verify($this->user, (string) $otp, '2fa_login');

        $this->assertTrue($verifyResult['success']);
        $this->assertEquals('Kode 2FA berhasil diverifikasi', $verifyResult['message']);

        // Token should be deleted after successful verification
        $this->assertDatabaseMissing('otp_tokens', [
            'user_id' => $this->user->id,
            'purpose' => '2fa_login',
        ]);
    }

    public function test_verify_otp_with_invalid_code_for_2fa_activation_fails()
    {
        $this->otpService->generateAndSave(
            $this->user,
            'email',
            'test@example.com',
            '2fa_activation'
        );

        $verifyResult = $this->otpService->verify($this->user, '000000', '2fa_activation');

        $this->assertFalse($verifyResult['success']);
        $this->assertStringContainsString('Kode OTP salah', $verifyResult['message']);
    }

    public function test_verify_otp_with_invalid_code_for_2fa_login_fails()
    {
        $this->otpService->generateAndSave(
            $this->user,
            'email',
            'test@example.com',
            '2fa_login'
        );

        $verifyResult = $this->otpService->verify($this->user, '000000', '2fa_login');

        $this->assertFalse($verifyResult['success']);
        $this->assertStringContainsString('Kode 2FA salah', $verifyResult['message']);
    }

    public function test_verify_otp_fails_after_max_attempts_for_2fa_activation()
    {
        $result = $this->otpService->generateAndSave(
            $this->user,
            'email',
            'test@example.com',
            '2fa_activation'
        );

        // Try 5 times with wrong code (max attempts)
        for ($i = 0; $i < 5; $i++) {
            $this->otpService->verify($this->user, '000000', '2fa_activation');
        }

        // Sixth attempt should fail due to max attempts
        $verifyResult = $this->otpService->verify($this->user, (string) $result['otp'], '2fa_activation');

        $this->assertFalse($verifyResult['success']);
        $this->assertStringContainsString('Maksimal percobaan', $verifyResult['message']);
    }

    public function test_verify_otp_fails_after_max_attempts_for_2fa_login()
    {
        $result = $this->otpService->generateAndSave(
            $this->user,
            'email',
            'test@example.com',
            '2fa_login'
        );

        // Try 5 times with wrong code (max attempts)
        for ($i = 0; $i < 5; $i++) {
            $this->otpService->verify($this->user, '000000', '2fa_login');
        }

        // Sixth attempt should fail due to max attempts
        $verifyResult = $this->otpService->verify($this->user, (string) $result['otp'], '2fa_login');

        $this->assertFalse($verifyResult['success']);
        $this->assertStringContainsString('Maksimal percobaan', $verifyResult['message']);
    }

    public function test_verify_otp_fails_with_expired_token_for_2fa_activation()
    {
        $result = $this->otpService->generateAndSave(
            $this->user,
            'email',
            'test@example.com',
            '2fa_activation'
        );

        // Manually expire the token
        $token = OtpToken::where('user_id', $this->user->id)->first();
        $token->expires_at = now()->subMinutes(10);
        $token->save();

        $verifyResult = $this->otpService->verify($this->user, (string) $result['otp'], '2fa_activation');

        $this->assertFalse($verifyResult['success']);
        $this->assertStringContainsString('tidak valid atau sudah kadaluarsa', $verifyResult['message']);
    }

    public function test_verify_otp_fails_with_expired_token_for_2fa_login()
    {
        $result = $this->otpService->generateAndSave(
            $this->user,
            'email',
            'test@example.com',
            '2fa_login'
        );

        // Manually expire the token
        $token = OtpToken::where('user_id', $this->user->id)->first();
        $token->expires_at = now()->subMinutes(10);
        $token->save();

        $verifyResult = $this->otpService->verify($this->user, (string) $result['otp'], '2fa_login');

        $this->assertFalse($verifyResult['success']);
        $this->assertStringContainsString('tidak valid atau sudah kadaluarsa', $verifyResult['message']);
    }

    public function test_otp_token_relationship_with_user_for_2fa()
    {
        $result = $this->otpService->generateAndSave($this->user, 'email', 'test@example.com', '2fa_activation');

        $this->assertInstanceOf(OtpToken::class, $this->user->otpTokens()->first());
        $this->assertEquals($this->user->id, $result['token']->user->id);
    }
}