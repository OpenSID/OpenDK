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
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

uses(DatabaseTransactions::class);

beforeEach(function () {
    auth()->logout();

    $this->otpService = app(OtpService::class);

    // Disable captcha
    SettingAplikasi::updateOrCreate(
        ['key' => 'google_recaptcha'],
        ['value' => 0]
    );

    $this->user = User::factory()->create([
        'email' => 'otp-test@example.com',
        'otp_enabled' => true,
        'otp_verified' => true,
        'otp_channel' => 'email',
        'status' => 1,
    ]);
});

describe('OTP Request', function () {
    test('user can request OTP for login', function () {
        Mail::fake();

        $response = $this->post(route('otp.request-login'), [
            'identifier' => $this->user->email,
        ]);

        $response->assertRedirect(route('otp.verify-login'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('otp_tokens', [
            'user_id' => $this->user->id,
            'channel' => 'email',
            'purpose' => 'login',
        ]);

        Mail::assertSent(OtpMail::class, function ($mail) {
            return $mail->hasTo($this->user->email);
        });
    });

    test('inactive user cannot request OTP', function () {
        $inactiveUser = User::factory()->create(['status' => 0, 'otp_enabled' => true]);

        $response = $this->post(route('otp.request-login'), [
            'identifier' => $inactiveUser->email,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    });

    test('user without OTP enabled cannot request OTP', function () {
        $this->user->update(['otp_enabled' => false]);

        $response = $this->post(route('otp.request-login'), [
            'identifier' => $this->user->email,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    });
});

describe('OTP Verification', function () {
    test('user can login with valid OTP', function () {
        $result = $this->otpService->generateAndSave($this->user, 'email', $this->user->email, 'login');
        $otp = $result['otp'];

        session([
            'otp_login' => [
                'user_id' => $this->user->id,
                'sent_at' => now()->timestamp,
            ]
        ]);

        $response = $this->post('/otp/verify-login', [
            'otp' => $otp,
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($this->user);

        // Token should be deleted
        $this->assertDatabaseMissing('otp_tokens', ['user_id' => $this->user->id, 'purpose' => 'login']);
    });

    test('verification fails with invalid OTP', function () {
        $this->otpService->generateAndSave($this->user, 'email', $this->user->email, 'login');

        session([
            'otp_login' => [
                'user_id' => $this->user->id,
                'sent_at' => now()->timestamp,
            ]
        ]);

        $response = $this->from('/otp/verify-login')->post('/otp/verify-login', [
            'otp' => '000000',
        ]);

        $response->assertRedirect('/otp/verify-login');
        $response->assertSessionHas('error');
        $this->assertGuest();
    });

    test('verification fails with expired OTP', function () {
        $result = $this->otpService->generateAndSave($this->user, 'email', $this->user->email, 'login');
        $otp = $result['otp'];

        $token = OtpToken::where('user_id', $this->user->id)->first();
        $token->update(['expires_at' => now()->subMinutes(10)]);

        session([
            'otp_login' => [
                'user_id' => $this->user->id,
                'sent_at' => now()->timestamp,
            ]
        ]);

        $response = $this->post('/otp/verify-login', [
            'otp' => $otp,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    });

    test('verification fails after max attempts', function () {
        $result = $this->otpService->generateAndSave($this->user, 'email', $this->user->email, 'login');

        session([
            'otp_login' => [
                'user_id' => $this->user->id,
                'sent_at' => now()->timestamp,
            ]
        ]);

        // Fail 5 times
        for ($i = 0; $i < 5; $i++) {
            $this->post('/otp/verify-login', ['otp' => '000000']);
        }

        // 6th attempt with CORRECT code should fail
        $response = $this->post('/otp/verify-login', ['otp' => $result['otp']]);

        $response->assertSessionHas('error', 'Maksimal percobaan telah tercapai. Silakan minta kode baru.');
    });
});
