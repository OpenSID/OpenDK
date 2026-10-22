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

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

uses(DatabaseTransactions::class);

beforeEach(function () {
    auth()->logout();
    Mail::fake();
});

test('login is throttled after too many failed attempts for the same email', function () {
    $user = User::factory()->create([
        'email' => 'login-throttle@example.com',
        'password' => Hash::make('password'),
        'status' => 1,
    ]);

    for ($attempt = 0; $attempt < 10; $attempt++) {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('login'));
    }

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])->assertStatus(429);
});

test('login rate limit keeps separate buckets for different emails on the same ip', function () {
    $throttledUser = User::factory()->create([
        'email' => 'login-throttle-a@example.com',
        'password' => Hash::make('password'),
        'status' => 1,
    ]);

    $otherUser = User::factory()->create([
        'email' => 'login-throttle-b@example.com',
        'password' => Hash::make('password'),
        'status' => 1,
    ]);

    for ($attempt = 0; $attempt < 10; $attempt++) {
        $this->from(route('login'))->post(route('login'), [
            'email' => $throttledUser->email,
            'password' => 'wrong-password',
        ])->assertRedirect(route('login'));
    }

    $this->post(route('login'), [
        'email' => $throttledUser->email,
        'password' => 'wrong-password',
    ])->assertStatus(429);

    $this->from(route('login'))->post(route('login'), [
        'email' => $otherUser->email,
        'password' => 'wrong-password',
    ])->assertRedirect(route('login'));
});

test('otp request is throttled after too many attempts for the same identifier', function () {
    $user = User::factory()->create([
        'email' => 'otp-throttle@example.com',
        'name' => 'otp-throttle',
        'password' => Hash::make('password'),
        'status' => 1,
        'otp_enabled' => true,
        'otp_verified' => true,
        'otp_channel' => 'email',
    ]);

    for ($attempt = 0; $attempt < 3; $attempt++) {
        $this->post(route('otp.request-login'), [
            'identifier' => $user->email,
        ])->assertRedirect(route('otp.verify-login'));
    }

    $this->post(route('otp.request-login'), [
        'identifier' => $user->email,
    ])->assertStatus(429);
});

test('otp request rate limit keeps separate buckets for different identifiers on the same ip', function () {
    $throttledUser = User::factory()->create([
        'email' => 'otp-throttle-a@example.com',
        'name' => 'otp-throttle-a',
        'password' => Hash::make('password'),
        'status' => 1,
        'otp_enabled' => true,
        'otp_verified' => true,
        'otp_channel' => 'email',
    ]);

    $otherUser = User::factory()->create([
        'email' => 'otp-throttle-b@example.com',
        'name' => 'otp-throttle-b',
        'password' => Hash::make('password'),
        'status' => 1,
        'otp_enabled' => true,
        'otp_verified' => true,
        'otp_channel' => 'email',
    ]);

    for ($attempt = 0; $attempt < 3; $attempt++) {
        $this->post(route('otp.request-login'), [
            'identifier' => $throttledUser->email,
        ])->assertRedirect(route('otp.verify-login'));
    }

    $this->post(route('otp.request-login'), [
        'identifier' => $throttledUser->email,
    ])->assertStatus(429);

    $this->post(route('otp.request-login'), [
        'identifier' => $otherUser->email,
    ])->assertRedirect(route('otp.verify-login'));
});

test('2fa verification is throttled using the pending authentication session', function () {
    $user = User::factory()->create([
        'email' => '2fa-throttle@example.com',
        'password' => Hash::make('password'),
        'status' => 1,
        'two_fa_enabled' => true,
        'otp_verified' => true,
        'otp_channel' => 'email',
    ]);

    app(OtpService::class)->generateAndSave($user, 'email', $user->email, '2fa_login');

    session([
        'two-factor:auth' => [
            'id' => $user->id,
            'email' => $user->email,
        ],
    ]);

    for ($attempt = 0; $attempt < 10; $attempt++) {
        $this->from(route('2fa.verify-login'))->post(route('2fa.verify-login'), [
            'otp' => '000000',
        ])->assertRedirect();
    }

    $this->post(route('2fa.verify-login'), [
        'otp' => '000000',
    ])->assertStatus(429);
});
