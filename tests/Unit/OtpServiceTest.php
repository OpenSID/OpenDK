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

class OtpServiceTest extends TestCase
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

    public function test_generate_otp_code_returns_six_digits()
    {
        $otp = $this->otpService->generateOtpCode();
        
        $this->assertIsInt($otp);
        $this->assertGreaterThanOrEqual(100000, $otp);
        $this->assertLessThanOrEqual(999999, $otp);
    }

    public function test_generate_and_save_creates_otp_token()
    {
        $result = $this->otpService->generateAndSave(
            $this->user,
            'email',
            'test@example.com',
            'login'
        );

        $this->assertArrayHasKey('token', $result);
        $this->assertArrayHasKey('otp', $result);
        $this->assertInstanceOf(OtpToken::class, $result['token']);
        $this->assertIsInt($result['otp']);

        // Check database
        $this->assertDatabaseHas('otp_tokens', [
            'user_id' => $this->user->id,
            'channel' => 'email',
            'identifier' => 'test@example.com',
            'purpose' => 'login',
        ]);
    }

    public function test_generate_and_save_deletes_old_tokens()
    {
        // Create first token
        $result1 = $this->otpService->generateAndSave(
            $this->user,
            'email',
            'test@example.com',
            'login'
        );

        // Create second token (should delete first)
        $result2 = $this->otpService->generateAndSave(
            $this->user,
            'email',
            'test@example.com',
            'login'
        );

        // Check only one token exists
        $tokenCount = OtpToken::where('user_id', $this->user->id)
            ->where('purpose', 'login')
            ->count();

        $this->assertEquals(1, $tokenCount);
    }

    public function test_verify_otp_with_valid_code_succeeds()
    {
        $result = $this->otpService->generateAndSave(
            $this->user,
            'email',
            'test@example.com',
            'login'
        );

        $otp = $result['otp'];

        $verifyResult = $this->otpService->verify($this->user, (string) $otp, 'login');

        $this->assertTrue($verifyResult['success']);
        $this->assertEquals('Kode OTP berhasil diverifikasi', $verifyResult['message']);

        // Token should be deleted after successful verification
        $this->assertDatabaseMissing('otp_tokens', [
            'user_id' => $this->user->id,
            'purpose' => 'login',
        ]);
    }

    public function test_verify_otp_with_invalid_code_fails()
    {
        $this->otpService->generateAndSave(
            $this->user,
            'email',
            'test@example.com',
            'login'
        );

        $verifyResult = $this->otpService->verify($this->user, '000000', 'login');

        $this->assertFalse($verifyResult['success']);
        $this->assertStringContainsString('Kode OTP salah', $verifyResult['message']);
    }

    public function test_verify_otp_increments_attempts()
    {
        $result = $this->otpService->generateAndSave(
            $this->user,
            'email',
            'test@example.com',
            'login'
        );

        // Try with wrong code
        $this->otpService->verify($this->user, '000000', 'login');

        // Check attempts incremented
        $token = OtpToken::where('user_id', $this->user->id)
            ->where('purpose', 'login')
            ->first();

        $this->assertEquals(1, $token->attempts);
    }

    public function test_verify_otp_fails_with_expired_token()
    {
        $result = $this->otpService->generateAndSave(
            $this->user,
            'email',
            'test@example.com',
            'login'
        );

        // Manually expire the token
        $token = OtpToken::where('user_id', $this->user->id)->first();
        $token->expires_at = now()->subMinutes(10);
        $token->save();

        $verifyResult = $this->otpService->verify($this->user, (string) $result['otp'], 'login');

        $this->assertFalse($verifyResult['success']);
        $this->assertStringContainsString('tidak valid atau sudah kadaluarsa', $verifyResult['message']);
    }

    public function test_otp_token_is_expired_method()
    {
        $token = OtpToken::create([
            'user_id' => $this->user->id,
            'token_hash' => Hash::make('123456'),
            'channel' => 'email',
            'identifier' => 'test@example.com',
            'purpose' => 'login',
            'expires_at' => now()->subMinutes(1),
            'attempts' => 0,
        ]);

        $this->assertTrue($token->isExpired());

        $token->expires_at = now()->addMinutes(5);
        $token->save();

        $this->assertFalse($token->isExpired());
    }

    public function test_otp_token_valid_scope()
    {
        // Create expired token
        $expiredToken = OtpToken::create([
            'user_id' => $this->user->id,
            'token_hash' => Hash::make('123456'),
            'channel' => 'email',
            'identifier' => 'test@example.com',
            'purpose' => 'login',
            'expires_at' => now()->subMinutes(1),
            'attempts' => 0,
        ]);

        // Create token with max attempts
        $maxAttemptsToken = OtpToken::create([
            'user_id' => $this->user->id,
            'token_hash' => Hash::make('654321'),
            'channel' => 'email',
            'identifier' => 'test@example.com',
            'purpose' => 'activation',
            'expires_at' => now()->addMinutes(5),
            'attempts' => 3,
        ]);

        // Create valid token
        $validToken = OtpToken::create([
            'user_id' => $this->user->id,
            'token_hash' => Hash::make('789012'),
            'channel' => 'email',
            'identifier' => 'test@example.com',
            'purpose' => 'login',
            'expires_at' => now()->addMinutes(5),
            'attempts' => 0,
        ]);

        $validTokens = OtpToken::where('user_id', $this->user->id)->valid()->get();

        $this->assertEquals(1, $validTokens->count());
        $this->assertEquals($validToken->id, $validTokens->first()->id);
    }
}
