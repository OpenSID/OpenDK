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

use App\Helpers\IpAddress;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

uses(DatabaseTransactions::class);

beforeEach(function () {
    // Logout any authenticated user
    auth()->logout();
});

// ============================================
// IP Detection Tests
// ============================================

describe('IpAddress Helper - IP Detection', function () {
    test('detects cloudflare connecting ip', function () {
        $request = Request::create('/login', 'POST');
        $request->headers->set('CF-Connecting-IP', '1.2.3.4');

        $ip = IpAddress::getRealIp($request);

        expect($ip)->toBe('1.2.3.4');
    })->group('security', 'ip-detection', 'cloudflare');

    test('detects x-forwarded-for header', function () {
        $request = Request::create('/login', 'POST');
        $request->headers->set('X-Forwarded-For', '5.6.7.8');

        $ip = IpAddress::getRealIp($request);

        expect($ip)->toBe('5.6.7.8');
    })->group('security', 'ip-detection', 'proxy');

    test('parses first ip from x-forwarded-for with multiple ips', function () {
        $request = Request::create('/login', 'POST');
        $request->headers->set('X-Forwarded-For', '1.2.3.4, 10.0.0.1, 172.16.0.1');

        $ip = IpAddress::getRealIp($request);

        expect($ip)->toBe('1.2.3.4');
    })->group('security', 'ip-detection', 'proxy');

    test('detects x-real-ip header', function () {
        $request = Request::create('/login', 'POST');
        $request->headers->set('X-Real-IP', '9.10.11.12');

        $ip = IpAddress::getRealIp($request);

        expect($ip)->toBe('9.10.11.12');
    })->group('security', 'ip-detection', 'nginx');

    test('falls back to request ip when no proxy headers', function () {
        $request = Request::create('/login', 'POST', [], [], [], ['REMOTE_ADDR' => '192.168.1.100']);

        $ip = IpAddress::getRealIp($request);

        expect($ip)->toBe('192.168.1.100');
    })->group('security', 'ip-detection');

    test('rejects invalid ip format', function () {
        $request = Request::create('/login', 'POST');
        $request->headers->set('X-Forwarded-For', '"><script>alert("xss")</script>');

        $ip = IpAddress::getRealIp($request);

        expect($ip)->not->toBe('"><script>alert("xss")</script>');
    })->group('security', 'ip-validation', 'xss');

    test('filters private ips when trustPrivateIp is false', function () {
        $request = Request::create('/login', 'POST');
        $request->headers->set('X-Forwarded-For', '192.168.1.1');

        $ip = IpAddress::getRealIp($request, trustPrivateIp: false);

        // Should fall back to $request->ip() instead
        expect($ip)->not->toBe('192.168.1.1');
    })->group('security', 'ip-detection', 'private-ip');

    test('accepts private ips when trustPrivateIp is true', function () {
        $request = Request::create('/login', 'POST');
        $request->server->set('REMOTE_ADDR', '192.168.1.100');

        $ip = IpAddress::getRealIp($request, trustPrivateIp: true);

        expect($ip)->toBe('192.168.1.100');
    })->group('security', 'ip-detection', 'private-ip');
});

// ============================================
// Rate Limit Key Generation Tests
// ============================================

describe('IpAddress Helper - Rate Limit Key', function () {
    test('generates rate limit key with ip and email', function () {
        $request = Request::create('/login', 'POST');
        $request->headers->set('CF-Connecting-IP', '1.2.3.4');
        $request->merge(['email' => 'user@example.com']);

        $key = IpAddress::getRateLimitKey($request, 'user@example.com');

        expect($key)->toBe('1.2.3.4|user@example.com');
    })->group('security', 'rate-limit', 'key-generation');

    test('sanitizes email in rate limit key', function () {
        $request = Request::create('/login', 'POST');
        $request->headers->set('CF-Connecting-IP', '1.2.3.4');

        $key = IpAddress::getRateLimitKey($request, '  User@Example.COM  ');

        expect($key)->toBe('1.2.3.4|user@example.com');
    })->group('security', 'rate-limit', 'sanitization');

    test('generates key with only ip when email is null', function () {
        $request = Request::create('/login', 'POST');
        $request->headers->set('CF-Connecting-IP', '1.2.3.4');

        $key = IpAddress::getRateLimitKey($request);

        expect($key)->toBe('1.2.3.4');
    })->group('security', 'rate-limit', 'key-generation');
});

// ============================================
// Login Rate Limiting Tests
// ============================================

describe('Login Rate Limiting', function () {
    test('allows login within rate limit', function () {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        // Attempt login 5 times (under the limit of 10)
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post(route('login'), [
                'email' => $user->email,
                'password' => 'wrongpassword',
            ]);
            $this->assertNotEquals(429, $response->status());
        }
    })->group('security', 'rate-limit', 'login');

    test('blocks login after rate limit exceeded', function () {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        // Clear any existing rate limits
        Cache::flush();

        // Attempt login 11 times (exceeds limit of 10)
        $statusCode = null;
        for ($i = 0; $i < 12; $i++) {
            $response = $this->post(route('login'), [
                'email' => $user->email,
                'password' => 'wrongpassword',
            ]);
            $statusCode = $response->status();
        }

        // Last request should be rate limited
        expect($statusCode)->toBe(429);
    })->group('security', 'rate-limit', 'login');

    test('different email bypasses rate limit', function () {
        $user1 = User::factory()->create(['email' => 'user1@example.com']);
        $user2 = User::factory()->create(['email' => 'user2@example.com']);

        // Exhaust rate limit for user1
        for ($i = 0; $i < 11; $i++) {
            $this->post(route('login'), [
                'email' => 'user1@example.com',
                'password' => 'wrong',
            ]);
        }

        // user2 should still be able to attempt login
        $response = $this->post(route('login'), [
            'email' => 'user2@example.com',
            'password' => 'wrong',
        ]);

        $this->assertNotEquals(429, $response->status());
    })->group('security', 'rate-limit', 'login');
});

// ============================================
// OTP Rate Limiting Tests
// ============================================

describe('OTP Rate Limiting', function () {
    test('allows otp request within rate limit', function () {
        $user = User::factory()->create();

        // Request OTP 2 times (under the limit of 3)
        for ($i = 0; $i < 2; $i++) {
            $response = $this->post(route('otp.request-login'), [
                'email' => $user->email,
            ]);
            $this->assertNotEquals(429, $response->status());
        }
    })->group('security', 'rate-limit', 'otp');

    test('blocks otp request after rate limit exceeded', function () {
        $user = User::factory()->create();

        // Clear any existing rate limits
        Cache::flush();

        // Request OTP 4 times (exceeds limit of 3)
        $statusCode = null;
        for ($i = 0; $i < 4; $i++) {
            $response = $this->post(route('otp.request-login'), [
                'email' => $user->email,
            ]);
            $statusCode = $response->status();
        }

        // Last request should be rate limited
        expect($statusCode)->toBe(429);
    })->group('security', 'rate-limit', 'otp');
});

// ============================================
// Security Validation Tests
// ============================================

describe('Security - Header Injection Prevention', function () {
    test('rejects script injection in x-forwarded-for', function () {
        $maliciousInputs = [
            '"><script>alert(1)</script>',
            'javascript:alert(1)',
            '../../etc/passwd',
            '\x00\x01\x02',
            'very.long.ip.address.that.exceeds.maximum.length.for.ipv6.addresses.and.should.be.rejected.by.the.validator',
        ];

        foreach ($maliciousInputs as $input) {
            $request = Request::create('/login', 'POST');
            $request->headers->set('X-Forwarded-For', $input);

            $ip = IpAddress::getRealIp($request);

            // Should not return the malicious input
            expect($ip)->not->toBe($input);
        }
    })->group('security', 'validation', 'injection');

    test('validates ipv4 format', function () {
        $request = Request::create('/login', 'POST');
        $request->headers->set('CF-Connecting-IP', '256.256.256.256'); // Invalid IP

        $ip = IpAddress::getRealIp($request);

        expect($ip)->not->toBe('256.256.256.256');
    })->group('security', 'validation', 'ipv4');
});

// ============================================
// Priority Tests
// ============================================

describe('IP Detection Priority Order', function () {
    test('cf-connecting-ip has highest priority', function () {
        $request = Request::create('/login', 'POST');
        $request->headers->set('CF-Connecting-IP', '1.2.3.4');
        $request->headers->set('X-Forwarded-For', '5.6.7.8');
        $request->headers->set('X-Real-IP', '9.10.11.12');

        $ip = IpAddress::getRealIp($request);

        expect($ip)->toBe('1.2.3.4'); // CF-Connecting-IP wins
    })->group('security', 'ip-detection', 'priority');

    test('x-forwarded-for has priority over x-real-ip', function () {
        $request = Request::create('/login', 'POST');
        $request->headers->set('X-Forwarded-For', '5.6.7.8');
        $request->headers->set('X-Real-IP', '9.10.11.12');

        $ip = IpAddress::getRealIp($request);

        expect($ip)->toBe('5.6.7.8'); // X-Forwarded-For wins
    })->group('security', 'ip-detection', 'priority');
});
