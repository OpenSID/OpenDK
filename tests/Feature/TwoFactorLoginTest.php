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
use App\Models\SettingAplikasi;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Mews\Captcha\Facades\Captcha;

uses(DatabaseTransactions::class);

beforeEach(function () {
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
});

test('user with 2fa redirected to verification after password login', function () {
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
    expect(session('two-factor:auth'))->not->toBeNull()
        ->and(session('two-factor:auth')['id'])->toBe($this->user->id);

    // Check OTP token created for 2FA
    $this->assertDatabaseHas('otp_tokens', [
        'user_id' => $this->user->id,
        'channel' => 'email',
        'purpose' => '2fa_login',
    ]);
});

test('user can complete 2fa login with valid code', function () {
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
    expect(session('two-factor:auth'))->toBeNull();
});

test('user cannot complete 2fa with invalid code', function () {
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
});

test('2fa login requires valid session', function () {
    // Try to verify 2FA without session
    $response = $this->post('/2fa/verify-login', [
        'otp' => '123456',
    ]);

    // Should redirect to login page
    $response->assertRedirect(route('login'));
    $response->assertSessionHas('error');
});

test('2fa code expires after timeout', function () {
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
});

test('user without 2fa can login normally', function () {
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
    expect(session('two-factor:auth'))->toBeNull();
});

test('2fa fails after max attempts', function () {
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
});

test('complete 2fa login flow', function () {
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

    expect($token)->not->toBeNull();

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
});
