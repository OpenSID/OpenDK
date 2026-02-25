<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 */

use App\Models\User;
use App\Models\OtpToken;
use App\Models\SettingAplikasi;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Mews\Captcha\Facades\Captcha;

uses(DatabaseTransactions::class);

beforeEach(function () {
    auth()->logout();

    $this->otpService = app(OtpService::class);

    // Disable captcha
    SettingAplikasi::updateOrCreate(
        ['key' => 'google_recaptcha'],
        ['value' => 0]
    );

    Captcha::shouldReceive('display')
        ->andReturn('<input type="hidden" name="captcha" value="1" />');

    $this->password = 'Password123!';
    $this->user = User::factory()->create([
        'email' => '2fa-test@example.com',
        'password' => Hash::make($this->password),
        'two_fa_enabled' => true,
        'otp_verified' => true,
        'otp_channel' => 'email',
        'status' => 1,
    ]);
});

describe('2FA Login Flow', function () {
    test('user with 2FA enabled is redirected to verification page after password login', function () {
        $response = $this->post(route('login'), [
            'email' => $this->user->email,
            'password' => $this->password,
            'captcha' => '1',
        ]);

        $response->assertRedirect(route('2fa.verify-login'));
        $this->assertGuest();

        expect(session('two-factor:auth'))->not->toBeNull()
            ->and(session('two-factor:auth')['id'])->toBe($this->user->id);

        $this->assertDatabaseHas('otp_tokens', [
            'user_id' => $this->user->id,
            'purpose' => '2fa_login',
        ]);
    });

    test('user can complete 2FA login with valid code', function () {
        // Step 1: Password login (sets session)
        $this->post(route('login'), [
            'email' => $this->user->email,
            'password' => $this->password,
            'captcha' => '1',
        ]);

        // Step 2: Get OTP from DB
        $token = OtpToken::where('user_id', $this->user->id)->where('purpose', '2fa_login')->first();

        // We need the plain OTP, let's just generate a known one for verification test
        // Or reuse the OtpService to verify
        $result = $this->otpService->generateAndSave($this->user, 'email', $this->user->email, '2fa_login');

        $response = $this->post('/2fa/verify-login', [
            'otp' => $result['otp'],
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($this->user);
        expect(session('two-factor:auth'))->toBeNull();
    });

    test('2FA login fails with invalid code', function () {
        // Step 1: Password login
        $this->post(route('login'), [
            'email' => $this->user->email,
            'password' => $this->password,
            'captcha' => '1',
        ]);

        $response = $this->from('/2fa/verify-login')->post('/2fa/verify-login', [
            'otp' => '000000',
        ]);

        $response->assertRedirect('/2fa/verify-login');
        $response->assertSessionHas('error');
        $this->assertGuest();
    });

    test('2FA login requires active session', function () {
        $response = $this->post('/2fa/verify-login', [
            'otp' => '123456',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    });
});

describe('2FA Management', function () {
    test('user can enable 2FA after verifying settings', function () {
        $user = User::factory()->create([
            'otp_verified' => true,
            'otp_channel' => 'email',
            'two_fa_enabled' => false,
        ]);

        $this->actingAs($user);

        // Request activation (sets a token)
        $response = $this->post(route('2fa.request-activation'));
        $response->assertRedirect(route('otp2fa.index'));

        // The controller actually enables it immediately for activation requests if I recall
        // Let's check User record
        expect($user->fresh()->two_fa_enabled)->toBeTrue();
    });

    test('user can deactivate 2FA', function () {
        $this->actingAs($this->user);

        // The route is a GET according to routes/web.php
        $response = $this->get(route('2fa.deactivate'));
        $response->assertRedirect('/');
        expect($this->user->fresh()->two_fa_enabled)->toBeFalse();
    });
});
