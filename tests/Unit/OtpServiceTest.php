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

use App\Models\OtpToken;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->otpService = new OtpService();

    // Create a test user
    $this->user = User::factory()->create([
        'email' => 'test@example.com',
        'name' => 'Test User',
        'status' => 1,
    ]);
});

test('generate otp code returns six digits', function () {
    $otp = $this->otpService->generateOtpCode();

    expect($otp)
        ->toBeInt()
        ->toBeGreaterThanOrEqual(100000)
        ->toBeLessThanOrEqual(999999);
});

test('generate and save creates otp token', function () {
    $result = $this->otpService->generateAndSave(
        $this->user,
        'email',
        'test@example.com',
        'login'
    );

    expect($result)
        ->toHaveKey('token')
        ->toHaveKey('otp')
        ->and($result['token'])->toBeInstanceOf(OtpToken::class)
        ->and($result['otp'])->toBeInt();

    // Check database
    $this->assertDatabaseHas('otp_tokens', [
        'user_id' => $this->user->id,
        'channel' => 'email',
        'identifier' => 'test@example.com',
        'purpose' => 'login',
    ]);
});

test('generate and save deletes old tokens', function () {
    // Create first token
    $this->otpService->generateAndSave(
        $this->user,
        'email',
        'test@example.com',
        'login'
    );

    // Create second token (should delete first)
    $this->otpService->generateAndSave(
        $this->user,
        'email',
        'test@example.com',
        'login'
    );

    // Check only one token exists
    $tokenCount = OtpToken::where('user_id', $this->user->id)
        ->where('purpose', 'login')
        ->count();

    expect($tokenCount)->toBe(1);
});

test('verify otp with valid code succeeds', function () {
    $result = $this->otpService->generateAndSave(
        $this->user,
        'email',
        'test@example.com',
        'login'
    );

    $otp = $result['otp'];

    $verifyResult = $this->otpService->verify($this->user, (string) $otp, 'login');

    expect($verifyResult['success'])->toBeTrue()
        ->and($verifyResult['message'])->toBe('Kode OTP berhasil diverifikasi');

    // Token should be deleted after successful verification
    $this->assertDatabaseMissing('otp_tokens', [
        'user_id' => $this->user->id,
        'purpose' => 'login',
    ]);
});

test('verify otp with invalid code fails', function () {
    $this->otpService->generateAndSave(
        $this->user,
        'email',
        'test@example.com',
        'login'
    );

    $verifyResult = $this->otpService->verify($this->user, '000000', 'login');

    expect($verifyResult['success'])->toBeFalse()
        ->and($verifyResult['message'])->toContain('Kode OTP salah');
});

test('verify otp increments attempts', function () {
    $this->otpService->generateAndSave(
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

    expect($token->attempts)->toBe(1);
});

test('verify otp fails with expired token', function () {
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

    expect($verifyResult['success'])->toBeFalse()
        ->and($verifyResult['message'])->toContain('tidak valid atau sudah kadaluarsa');
});

test('otp token is expired method', function () {
    $token = OtpToken::create([
        'user_id' => $this->user->id,
        'token_hash' => Hash::make('123456'),
        'channel' => 'email',
        'identifier' => 'test@example.com',
        'purpose' => 'login',
        'expires_at' => now()->subMinutes(1),
        'attempts' => 0,
    ]);

    expect($token->isExpired())->toBeTrue();

    $token->expires_at = now()->addMinutes(5);
    $token->save();

    expect($token->isExpired())->toBeFalse();
});

test('otp token valid scope', function () {
    // Create expired token
    OtpToken::create([
        'user_id' => $this->user->id,
        'token_hash' => Hash::make('123456'),
        'channel' => 'email',
        'identifier' => 'test@example.com',
        'purpose' => 'login',
        'expires_at' => now()->subMinutes(1),
        'attempts' => 0,
    ]);

    // Create token with max attempts
    OtpToken::create([
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

    expect($validTokens)->toHaveCount(1)
        ->and($validTokens->first()->id)->toBe($validToken->id);
});
