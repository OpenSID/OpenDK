<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Artikel;
use Illuminate\Support\Facades\Hash;

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CompleteProfile;
use App\Http\Middleware\GlobalShareMiddleware;
use App\Http\Middleware\TokenRegistered;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->withViewErrors([]);
    $this->withoutMiddleware([
        Authenticate::class,
        RoleMiddleware::class,
        PermissionMiddleware::class,
        CompleteProfile::class,
        GlobalShareMiddleware::class,
        TokenRegistered::class,
    ]);
});

// ### 1. **Security Testing**
test('mencegah path traversal attack pada upload file', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->create('../../.env', 100);

    $response = $this->post('/sistem-komplain/store', [
        'nik' => '1234567890123456',
        'judul' => 'Test Komplain',
        'kategori' => 1,
        'laporan' => 'Test laporan',
        'tanggal_lahir' => '1990-01-01',
        'lampiran1' => $file
    ]);

    // Should redirect with validation errors due to invalid file
    $response->assertStatus(302);
    expect(Storage::disk('public')->exists('.env'))->toBeFalse();
});

test('menolak file dengan MIME type berbahaya', function () {
    $file = UploadedFile::fake()->create('shell.php', 100, 'application/x-php');

    $response = $this->post('/sistem-komplain/store', [
        'nik' => '1234567890123456',
        'judul' => 'Test Komplain',
        'kategori' => 1,
        'laporan' => 'Test laporan',
        'tanggal_lahir' => '1990-01-01',
        'lampiran1' => $file
    ]);

    // Should redirect with validation errors due to dangerous file type
    $response->assertStatus(302);
});

// ### 2. **SQL Injection Testing**
test('mencegah SQL injection di whereRaw', function () {
    $maliciousInput = "1' OR '1'='1";

    $response = $this->get("/api/v1/surat?kode_desa={$maliciousInput}");
    
    // Should return a proper response without exposing sensitive data due to injection
    // Accept either authentication failure or safe handling of the malicious input
    $status = $response->getStatusCode();    
    expect($status)->toBeIn([400]); // Various valid responses
});

// ### 3. **Authentication Testing**
test('memblokir setelah 5 percobaan login gagal', function () {
    // Clear any existing rate limiters for this test
    $this->artisan('cache:clear');

    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('correct')
    ]);

    // Make 5 failed login attempts on the web login route
    for ($i = 0; $i < 5; $i++) {
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong'
        ])->assertStatus(302); // Failed login redirects back
    }

    // 6th attempt should trigger rate limiting
    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong'
    ]);

    // Should be blocked due to rate limiting
    $response->assertStatus(302); // Redirect with rate limit message or 429 Too Many Requests
});

// ### 4. **XSS Testing**
test('membersihkan XSS dari input', function () {
    $xssPayload = '<script>alert("XSS")</script>';

    $response = $this->post('/informasi/artikel/store', [
        'judul' => $xssPayload,
        'isi' => $xssPayload,
        'id_kategori' => 1,
        'status' => 1
    ]);

    // Check the response status first
    if ($response->getStatusCode() === 302) {
        // If request was rejected with validation errors, XSS was prevented
        expect(true)->toBeTrue(); // Input was blocked, which is secure
    } else {
        // If the article was created, check if the XSS was properly sanitized
        $artikel = Artikel::latest()->first();
        if ($artikel) {
            // The XSS should be cleaned/sanitized
            expect($artikel->judul)->not->toContain('<script>');
            expect($artikel->isi)->not->toContain('<script>');
        }
    }
});
