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

test('generate and send creates otp token for 2fa activation', function () {
    $result = $this->otpService->generateAndSend(
        $this->user,
        'email',
        'test@example.com',
        '2fa_activation'
    );

    expect($result['sent'])->toBeTrue()
        ->and($result)->toHaveKey('token')
        ->and($result['token'])->toBeInstanceOf(OtpToken::class);

    // Check database
    $this->assertDatabaseHas('otp_tokens', [
        'user_id' => $this->user->id,
        'channel' => 'email',
        'identifier' => 'test@example.com',
        'purpose' => '2fa_activation',
    ]);
});

test('generate and send creates otp token for 2fa login', function () {
    $result = $this->otpService->generateAndSend(
        $this->user,
        'email',
        'test@example.com',
        '2fa_login'
    );

    expect($result['sent'])->toBeTrue()
        ->and($result)->toHaveKey('token')
        ->and($result['token'])->toBeInstanceOf(OtpToken::class);

    // Check database
    $this->assertDatabaseHas('otp_tokens', [
        'user_id' => $this->user->id,
        'channel' => 'email',
        'identifier' => 'test@example.com',
        'purpose' => '2fa_login',
    ]);
});

test('verify otp with valid code for 2fa activation succeeds', function () {
    $result = $this->otpService->generateAndSave(
        $this->user,
        'email',
        'test@example.com',
        '2fa_activation'
    );

    $otp = $result['otp'];

    $verifyResult = $this->otpService->verify($this->user, (string) $otp, '2fa_activation');

    expect($verifyResult['success'])->toBeTrue()
        ->and($verifyResult['message'])->toBe('Kode OTP berhasil diverifikasi');

    // Token should be deleted after successful verification
    $this->assertDatabaseMissing('otp_tokens', [
        'user_id' => $this->user->id,
        'purpose' => '2fa_activation',
    ]);
});

test('verify otp with valid code for 2fa login succeeds', function () {
    $result = $this->otpService->generateAndSave(
        $this->user,
        'email',
        'test@example.com',
        '2fa_login'
    );

    $otp = $result['otp'];

    $verifyResult = $this->otpService->verify($this->user, (string) $otp, '2fa_login');

    expect($verifyResult['success'])->toBeTrue()
        ->and($verifyResult['message'])->toBe('Kode 2FA berhasil diverifikasi');

    // Token should be deleted after successful verification
    $this->assertDatabaseMissing('otp_tokens', [
        'user_id' => $this->user->id,
        'purpose' => '2fa_login',
    ]);
});

test('verify otp with invalid code for 2fa activation fails', function () {
    $this->otpService->generateAndSave(
        $this->user,
        'email',
        'test@example.com',
        '2fa_activation'
    );

    $verifyResult = $this->otpService->verify($this->user, '000000', '2fa_activation');

    expect($verifyResult['success'])->toBeFalse()
        ->and($verifyResult['message'])->toContain('Kode OTP salah');
});

test('verify otp with invalid code for 2fa login fails', function () {
    $this->otpService->generateAndSave(
        $this->user,
        'email',
        'test@example.com',
        '2fa_login'
    );

    $verifyResult = $this->otpService->verify($this->user, '000000', '2fa_login');

    expect($verifyResult['success'])->toBeFalse()
        ->and($verifyResult['message'])->toContain('Kode 2FA salah');
});

test('verify otp fails after max attempts for 2fa activation', function () {
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

    expect($verifyResult['success'])->toBeFalse()
        ->and($verifyResult['message'])->toContain('Maksimal percobaan');
});

test('verify otp fails after max attempts for 2fa login', function () {
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

    expect($verifyResult['success'])->toBeFalse()
        ->and($verifyResult['message'])->toContain('Maksimal percobaan');
});

test('verify otp fails with expired token for 2fa activation', function () {
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

    expect($verifyResult['success'])->toBeFalse()
        ->and($verifyResult['message'])->toContain('tidak valid atau sudah kadaluarsa');
});

test('verify otp fails with expired token for 2fa login', function () {
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

    expect($verifyResult['success'])->toBeFalse()
        ->and($verifyResult['message'])->toContain('tidak valid atau sudah kadaluarsa');
});

test('otp token relationship with user for 2fa', function () {
    $result = $this->otpService->generateAndSave($this->user, 'email', 'test@example.com', '2fa_activation');

    expect($this->user->otpTokens()->first())->toBeInstanceOf(OtpToken::class)
        ->and($result['token']->user->id)->toBe($this->user->id);
});