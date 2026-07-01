<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

use Tests\BrowserTestCase;
use Tests\Browser\SessionState;

uses(BrowserTestCase::class);

beforeEach(function () {
    SessionState::clear();
});

afterEach(function () {
    SessionState::clear();
});

it('smoke: halaman login dapat dirender', function () {
    visit('/login')
        ->assertSee('Login')
        ->assertPresent('#email')
        ->assertPresent('#password');
})->group('smoke', 'browser', 'login');

it('smoke: login berhasil dengan kredensial valid', function () {
    // Pada BrowserTestCase::createTestData(), user default adalah admin@mail.com / Admin123!
    visit('/login')
        ->fill('#email', 'admin@mail.com')
        ->fill('#password', 'Admin123!')
        ->press('Sign In')
        ->assertDontSee('Kredensial yang diberikan tidak cocok')
        ->assertSee('Dashboard');
})->group('smoke', 'browser', 'login');

it('smoke: pesan error tampil untuk kredensial invalid', function () {
    visit('/login')
        ->fill('#email', 'admin@mail.com')
        ->fill('#password', 'wrongpassword')
        ->press('Sign In')
        ->assertSee('Identitas tersebut tidak cocok dengan data kami.');
})->group('smoke', 'browser', 'login');

it('smoke: session restoration berfungsi', function () {
    $user = SessionState::loginAdminUser();
    
    // Melakukan navigasi ke dashboard tanpa melalui form login (bypass via SessionState cookie)
    SessionState::loginAndNavigate($user, '/dashboard')
        ->assertPathIs('/dashboard')
        ->assertSee('Dashboard');
})->group('smoke', 'browser', 'login');
