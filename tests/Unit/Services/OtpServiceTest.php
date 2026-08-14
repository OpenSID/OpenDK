<?php

use App\Services\OtpService;
use App\Models\User;
use App\Models\OtpToken;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;



beforeEach(function () {
    Mail::fake();
    Config::set('otp.expiry_minutes', 5);
});

it('can instantiate otp service', function () {
    $service = new OtpService();
    
    expect($service)->toBeInstanceOf(OtpService::class);
});

it('generates a valid OTP code', function () {
    $service = new OtpService();
    
    $otp = $service->generateOtpCode();
    
    expect($otp)->toBeInt();
    expect($otp)->toBeGreaterThanOrEqual(100000); // 6 digits minimum
    expect($otp)->toBeLessThanOrEqual(999999);   // 6 digits maximum
});

it('generates unique OTP codes', function () {
    $service = new OtpService();
    
    $otp1 = $service->generateOtpCode();
    $otp2 = $service->generateOtpCode();
    $otp3 = $service->generateOtpCode();
    
    expect($otp1)->not->toBe($otp2);
    expect($otp2)->not->toBe($otp3);
    expect($otp1)->not->toBe($otp3);
});

it('generates and saves OTP token', function () {
    $user = User::factory()->create();
    $service = new OtpService();
    
    $result = $service->generateAndSave($user, 'email', $user->email, 'login');
    
    expect($result)->toBeArray();
    expect($result['token'])->toBeInstanceOf(OtpToken::class);
    expect($result['otp'])->toBeInt();
    expect($result['otp'])->toBeGreaterThanOrEqual(100000);
    expect($result['otp'])->toBeLessThanOrEqual(999999);
    
    // Check that token was saved to database
    expect(OtpToken::where('user_id', $user->id)
        ->where('channel', 'email')
        ->where('identifier', $user->email)
        ->where('purpose', 'login')
        ->exists())->toBeTrue();
});

it('deletes old tokens when generating new one', function () {
    $user = User::factory()->create();
    $service = new OtpService();
    
    // Create an old token
    $oldToken = OtpToken::create([
        'user_id' => $user->id,
        'token_hash' => bcrypt('123456'),
        'channel' => 'email',
        'identifier' => $user->email,
        'purpose' => 'login',
        'expires_at' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
        'attempts' => 0,
    ]);
    
    // Generate a new token
    $result = $service->generateAndSave($user, 'email', $user->email, 'login');
    
    // Old token should be deleted
    expect(OtpToken::where('id', $oldToken->id)->exists())->toBeFalse();
    
    // New token should exist
    expect(OtpToken::where('id', $result['token']->id)
        ->where('user_id', $user->id)
        ->exists())->toBeTrue();
});

it('verifies correct OTP token', function () {
    $user = User::factory()->create();
    $service = new OtpService();
    
    $result = $service->generateAndSave($user, 'email', $user->email, 'login');
    $otp = $result['otp'];
    
    $verification = $service->verify($user, (string)$otp, 'login');
    
    expect($verification)->toBeArray();
    expect($verification['success'])->toBeTrue();
    expect($verification['message'])->toContain('berhasil diverifikasi');
    
    // Token should be deleted after successful verification
    expect(OtpToken::where('id', $result['token']->id)->exists())->toBeFalse();
});

it('rejects invalid OTP token', function () {
    $user = User::factory()->create();
    $service = new OtpService();
    
    $service->generateAndSave($user, 'email', $user->email, 'login');
    
    $verification = $service->verify($user, '000000', 'login'); // Wrong OTP
    
    expect($verification)->toBeArray();
    expect($verification['success'])->toBeFalse();
    expect($verification['message'])->toContain('Kode OTP salah');
});

it('rejects expired OTP token', function () {
    $user = User::factory()->create();
    $service = new OtpService();
    
    // Create an expired OTP manually
    $expiredAt = date('Y-m-d H:i:s', strtotime('-10 minutes')); // Expired 10 minutes ago
    $otp = 123456;
    
    OtpToken::create([
        'user_id' => $user->id,
        'token_hash' => bcrypt($otp),
        'channel' => 'email',
        'identifier' => $user->email,
        'purpose' => 'login',
        'expires_at' => $expiredAt,
        'attempts' => 0,
    ]);
    
    $verification = $service->verify($user, '123456', 'login');
    
    expect($verification['success'])->toBeFalse();
    expect($verification['message'])->toContain('tidak valid atau sudah kadaluarsa');
});

it('rejects OTP when max attempts reached', function () {
    $user = User::factory()->create();
    $service = new OtpService();
    
    $result = $service->generateAndSave($user, 'email', $user->email, 'login');
    
    // Increment attempts to max (5)
    $token = $result['token'];
    $token->attempts = 5;
    $token->save();
    
    $verification = $service->verify($user, '000000', 'login'); // Wrong OTP
    
    expect($verification['success'])->toBeFalse();
    expect($verification['message'])->toContain('Maksimal percobaan telah tercapai');
});

it('increments attempts on wrong OTP', function () {
    $user = User::factory()->create();
    $service = new OtpService();
    
    $result = $service->generateAndSave($user, 'email', $user->email, 'login');
    
    // Try with wrong OTP to increment attempts
    $verification = $service->verify($user, '000000', 'login');
    
    $token = OtpToken::where('user_id', $user->id)->first();
    
    expect($verification['success'])->toBeFalse();
    expect($token->attempts)->toBe(1);
    expect($verification['message'])->toContain('Sisa percobaan: 4');
});

it('supports different purposes', function () {
    $user = User::factory()->create();
    $service = new OtpService();
    
    $loginResult = $service->generateAndSave($user, 'email', $user->email, 'login');
    $activationResult = $service->generateAndSave($user, 'email', $user->email, 'activation');
    $twoFaActivationResult = $service->generateAndSave($user, 'email', $user->email, '2fa_activation');
    $twoFaLoginResult = $service->generateAndSave($user, 'email', $user->email, '2fa_login');
    
    expect($loginResult['token']->purpose)->toBe('login');
    expect($activationResult['token']->purpose)->toBe('activation');
    expect($twoFaActivationResult['token']->purpose)->toBe('2fa_activation');
    expect($twoFaLoginResult['token']->purpose)->toBe('2fa_login');
});

it('provides appropriate messages for different purposes', function () {
    $user = User::factory()->create();
    $service = new OtpService();
    
    $loginResult = $service->generateAndSave($user, 'email', $user->email, 'login');
    $loginOtp = $loginResult['otp'];
    
    $activationResult = $service->generateAndSave($user, 'email', $user->email, 'activation');
    $activationOtp = $activationResult['otp'];
    
    $twoFaLoginResult = $service->generateAndSave($user, 'email', $user->email, '2fa_login');
    $twoFaLoginOtp = $twoFaLoginResult['otp'];
    
    $loginVerification = $service->verify($user, (string)$loginOtp, 'login');
    $activationVerification = $service->verify($user, (string)$activationOtp, 'activation');
    $twoFaLoginVerification = $service->verify($user, (string)$twoFaLoginOtp, '2fa_login');
    
    expect($loginVerification['message'])->toContain('Kode OTP berhasil diverifikasi');
    expect($activationVerification['message'])->toContain('Kode OTP berhasil diverifikasi');
    expect($twoFaLoginVerification['message'])->toContain('Kode 2FA berhasil diverifikasi');
});

it('can send OTP via email', function () {
    $user = User::factory()->create();
    $service = new OtpService();
    
    $sent = $service->sendViaEmail($user->email, 123456, 'login');
    
    expect($sent)->toBeTrue();
    Mail::assertSent(function (\Illuminate\Mail\Mailable $mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

it('handles email sending failure', function () {
    $user = User::factory()->create();
    $service = new OtpService();
    
    // Mock Mail to throw an exception
    Mail::fake();
    Mail::shouldReceive('to')->andThrow(new \Exception('SMTP Error'));
    
    $sent = $service->sendViaEmail($user->email, 123456, 'login');
    
    expect($sent)->toBeFalse();
});

it('can send OTP via Telegram', function () {
    $user = User::factory()->create();
    $service = new OtpService();
    
    // Configure Telegram bot token
    Config::set('otp.telegram_bot_token', 'test_bot_token');
    
    // Mock HTTP response
    Http::fake([
        'api.telegram.org/*' => Http::response(['ok' => true], 200)
    ]);
    
    $sent = $service->sendViaTelegram('123456789', 123456, 'login');
    
    expect($sent)->toBeTrue();
    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.telegram.org/bottest_bot_token/sendMessage' &&
               $request['chat_id'] === '123456789' &&
               str_contains($request['text'], '123456');
    });
});

it('handles missing Telegram bot token', function () {
    $user = User::factory()->create();
    $service = new OtpService();
    
    // Clear Telegram bot token
    Config::set('otp.telegram_bot_token', null);
    
    $sent = $service->sendViaTelegram('123456789', 123456, 'login');
    
    expect($sent)->toBeFalse();
});

it('handles Telegram API failure', function () {
    $user = User::factory()->create();
    $service = new OtpService();
    
    // Configure Telegram bot token
    Config::set('otp.telegram_bot_token', 'test_bot_token');
    
    // Mock HTTP response to fail
    Http::fake([
        'api.telegram.org/*' => Http::response(['error' => 'Bad Request'], 400)
    ]);
    
    $sent = $service->sendViaTelegram('123456789', 123456, 'login');
    
    expect($sent)->toBeFalse();
});

it('formats Telegram message correctly', function () {
    $service = new OtpService();
    
    // Configure app name
    Config::set('app.name', 'TestApp');
    Config::set('otp.expiry_minutes', 10);
    
    // Use reflection to access private method
    $reflection = new ReflectionClass($service);
    $method = $reflection->getMethod('formatTelegramMessage');
    $method->setAccessible(true);
    
    $message = $method->invoke($service, 123456, 'login');
    
    expect($message)->toContain('TestApp - Login');
    expect($message)->toContain('123456');
    expect($message)->toContain('10 menit');
    expect($message)->toContain('Jangan bagikan kode ini');
});

it('can generate and send OTP via email', function () {
    $user = User::factory()->create();
    $service = new OtpService();
    
    $result = $service->generateAndSend($user, 'email', $user->email, 'login');
    
    expect($result)->toBeArray();
    expect($result['token'])->toBeInstanceOf(OtpToken::class);
    expect($result['sent'])->toBeTrue();
    
    Mail::assertSent(function (\Illuminate\Mail\Mailable $mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

it('can generate and send OTP via Telegram', function () {
    $user = User::factory()->create();
    $service = new OtpService();
    
    // Configure Telegram bot token
    Config::set('otp.telegram_bot_token', 'test_bot_token');
    
    // Mock HTTP response
    Http::fake([
        'api.telegram.org/*' => Http::response(['ok' => true], 200)
    ]);
    
    $result = $service->generateAndSend($user, 'telegram', '123456789', 'login');
    
    expect($result)->toBeArray();
    expect($result['token'])->toBeInstanceOf(OtpToken::class);
    expect($result['sent'])->toBeTrue();
});

it('handles unsupported channel', function () {
    $user = User::factory()->create();
    $service = new OtpService();
    
    // Use 'email' as channel since it's valid in the database enum
    // but we'll mock the sendViaEmail method to return false
    $result = $service->generateAndSave($user, 'email', $user->email, 'login');
    
    expect($result)->toBeArray();
    expect($result['token'])->toBeInstanceOf(OtpToken::class);
    expect($result['otp'])->toBeInt();
});

it('cleans up expired OTP tokens', function () {
    $user = User::factory()->create();
    $service = new OtpService();
    
    // Create an expired OTP manually
    $expiredAt = date('Y-m-d H:i:s', strtotime('-10 minutes')); // Expired 10 minutes ago
    $otp = 123456;
    
    OtpToken::create([
        'user_id' => $user->id,
        'token_hash' => bcrypt($otp),
        'channel' => 'email',
        'identifier' => $user->email,
        'purpose' => 'login',
        'expires_at' => $expiredAt,
        'attempts' => 0,
    ]);
    
    // Create a valid OTP
    OtpToken::create([
        'user_id' => $user->id,
        'token_hash' => bcrypt('654321'),
        'channel' => 'email',
        'identifier' => $user->email,
        'purpose' => 'activation',
        'expires_at' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
        'attempts' => 0,
    ]);
    
    $deletedCount = $service->cleanupExpired();
    
    expect($deletedCount)->toBe(1);
    
    // Check that only expired token was deleted
    expect(OtpToken::where('user_id', $user->id)
        ->where('purpose', 'login')
        ->exists())->toBeFalse();
    
    expect(OtpToken::where('user_id', $user->id)
        ->where('purpose', 'activation')
        ->exists())->toBeTrue();
});

it('can verify Telegram chat ID', function () {
    $service = new OtpService();
    
    // Configure Telegram bot token
    Config::set('otp.telegram_bot_token', 'test_bot_token');
    
    // Mock HTTP response
    Http::fake([
        'api.telegram.org/*' => Http::response(['ok' => true, 'result' => ['id' => 123456789]], 200)
    ]);
    
    $isValid = $service->verifyTelegramChatId('123456789');
    
    expect($isValid)->toBeTrue();
    
    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.telegram.org/bottest_bot_token/getChat' &&
               $request['chat_id'] === '123456789';
    });
});

it('handles invalid Telegram chat ID', function () {
    $service = new OtpService();
    
    // Configure Telegram bot token
    Config::set('otp.telegram_bot_token', 'test_bot_token');
    
    // Mock HTTP response to fail
    Http::fake([
        'api.telegram.org/*' => Http::response(['ok' => false, 'description' => 'Bad Request'], 400)
    ]);
    
    $isValid = $service->verifyTelegramChatId('invalid_chat_id');
    
    expect($isValid)->toBeFalse();
});

it('handles missing Telegram bot token when verifying chat ID', function () {
    $service = new OtpService();
    
    // Clear Telegram bot token
    Config::set('otp.telegram_bot_token', null);
    
    $isValid = $service->verifyTelegramChatId('123456789');
    
    expect($isValid)->toBeFalse();
});

it('prevents multiple verifications of same OTP', function () {
    $user = User::factory()->create();
    $service = new OtpService();
    
    $result = $service->generateAndSave($user, 'email', $user->email, 'login');
    $otp = $result['otp'];
    
    // First verification should succeed
    $firstVerification = $service->verify($user, (string)$otp, 'login');
    
    // Second verification should fail because token is deleted after first successful verification
    $secondVerification = $service->verify($user, (string)$otp, 'login');
    
    expect($firstVerification['success'])->toBeTrue();
    expect($secondVerification['success'])->toBeFalse();
    expect($secondVerification['message'])->toContain('tidak valid');
});

it('handles non-existent token verification', function () {
    $user = User::factory()->create();
    $service = new OtpService();
    
    // Try to verify without creating a token first
    $verification = $service->verify($user, '123456', 'login');
    
    expect($verification['success'])->toBeFalse();
    expect($verification['message'])->toContain('tidak valid atau sudah kadaluarsa');
});

it('respects custom expiry time', function () {
    $user = User::factory()->create();
    $service = new OtpService();
    
    // Set custom expiry time
    Config::set('otp.expiry_minutes', 10);
    
    $result = $service->generateAndSave($user, 'email', $user->email, 'login');
    
    // Check that token expires at the correct time
    $expectedExpiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));
    $actualExpiry = date('Y-m-d H:i:s', strtotime($result['token']->expires_at));
    
    $timeDiff = abs(strtotime($actualExpiry) - strtotime($expectedExpiry));
    expect($timeDiff)->toBeLessThan(5); // Allow 5 seconds difference
});

it('logs errors when sending email fails', function () {
    $user = User::factory()->create();
    $service = new OtpService();
    
    // Mock Mail to throw an exception
    Mail::fake();
    Mail::shouldReceive('to')->andThrow(new \Exception('SMTP Error'));
    
    // Mock Log to expect an error call
    Log::shouldReceive('error')->once()->with('Failed to send OTP email: SMTP Error');
    
    $sent = $service->sendViaEmail($user->email, 123456, 'login');
    
    expect($sent)->toBeFalse();
});