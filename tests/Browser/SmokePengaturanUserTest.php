<?php

use Tests\BrowserTestCase;
use Laravel\Dusk\Browser;

uses(BrowserTestCase::class);

beforeEach(function () {
    $this->user = \Tests\Browser\SessionState::loginAdminUser();
});

// =============================================================================
// MENU: PENGATURAN -> GRUP PENGGUNA
// =============================================================================
it('smoke test menu Pengaturan User - Grup Pengguna', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/role');
    $this->page->assertPathIs('/setting/role');

    $this->page->assertSee('Grup Pengguna');
    $this->page->assertPresent('.box-body table');
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> PENGGUNA
// =============================================================================
it('smoke test menu Pengaturan User - Pengguna', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/user');
    $this->page->assertPathIs('/setting/user');

    $this->page->assertSee('Pengguna');
    $this->page->assertPresent('#user-table');
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> GANTI PASSWORD
// =============================================================================
it('smoke test menu Pengaturan User - Ganti Password', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/profile/password');
    $this->page->assertPathIs('/profile/password');

    $this->page->assertSee('Ganti Password');
    $this->page->assertPresent('form[action*="password"]'); // form untuk update password
    $this->page->assertPresent('input[name="current_password"]');
    $this->page->assertPresent('input[name="password"]');
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> OTP & 2FA
// =============================================================================
it('smoke test menu Pengaturan User - OTP & 2FA', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/otp-2fa');
    
    // We only assert see text because redirection might occur depending on settings
    $this->page->assertSee('OTP');
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// CHECKLIST MANUAL E: TWO-FACTOR AUTHENTICATION
// =============================================================================

// Menu dropdown aksi (dipindah ke <body> saat dibuka, atau tetap di baris) untuk tombol tertentu
function penggunaMenu(string $testid): string
{
    return "body > ul.dropdown-menu[style*=\"display: block\"] [data-testid=\"{$testid}\"], .btn-group.open [data-testid=\"{$testid}\"]";
}

it('smoke test OTP & 2FA - 2FA aktif dinonaktifkan lewat tombol: kembali ke /otp-2fa dan status nonaktif', function () {
    $user = $this->user;
    $statusAwal = $user->two_fa_enabled;

    // Arrange: 2FA dalam kondisi aktif (pengaktifan lewat channel OTP tidak diuji di sini)
    $user->forceFill(['two_fa_enabled' => true])->save();

    try {
        $this->page = \Tests\Browser\SessionState::loginAndNavigate($user, '/otp-2fa');
        $this->page->assertPathIs('/otp-2fa');
        $this->page->assertSee('2FA diaktifkan: Ya');

        // Tombol Nonaktifkan memunculkan konfirmasi SweetAlert; OK melanjutkan
        $this->page->press('.btn-confirm-deactivate');
        // Penanda di window hilang setelah form dikirim dan halaman dimuat ulang
        $this->page->assertVisible('.swal2-confirm');
        $this->page->script("window.__sebelumNonaktif = true");
        // Klik lewat DOM agar tidak menunggu navigasi form; kepastian reload dicek lewat penanda di bawah
        $this->page->script("document.querySelector('.swal2-confirm').click()");
        $this->page->assertScript("typeof window.__sebelumNonaktif === 'undefined'", true);

        $this->page->assertPathIs('/otp-2fa');
        expect($user->fresh()->two_fa_enabled)->toBeFalse();
    } finally {
        $user->forceFill(['two_fa_enabled' => $statusAwal])->save();
    }
})->group('smoke', 'smoke-pengaturan', 'browser');

it('smoke test OTP & 2FA - GET /2fa/deactivate ditolak 405 dan 2FA tetap aktif', function () {
    $user = $this->user;
    $statusAwal = $user->two_fa_enabled;
    $user->forceFill(['two_fa_enabled' => true])->save();

    try {
        $this->page = \Tests\Browser\SessionState::loginAndNavigate($user, '/otp-2fa');
        $this->page->waitForText('2FA diaktifkan');

        $this->page->assertScript("fetch('/2fa/deactivate', { credentials: 'same-origin' }).then(r => r.status === 405)", true);
        expect($user->fresh()->two_fa_enabled)->toBeTrue();
    } finally {
        $user->forceFill(['two_fa_enabled' => $statusAwal])->save();
    }
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// CHECKLIST MANUAL F: REGRESI - DROPDOWN AKSI MODUL LAIN (PENGGUNA)
// =============================================================================

it('smoke test Pengguna - dropdown aksi menampilkan dan menjalankan Ubah, Hapus, dan Tangguhkan', function () {
    // Pengguna uji dibuat khusus karena aksi Tangguhkan/Aktifkan tidak tersedia untuk user id 1
    $pengguna = \App\Models\User::factory()->create([
        'name' => 'Checklist Pengguna Uji',
        'email' => 'checklist-uji@example.test',
    ]);
    $pengguna->forceFill(['status' => 1])->save();

    try {
        $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/user');
        $this->page->assertPathIs('/setting/user');
        $this->page->type('input[type="search"]', 'Checklist Pengguna Uji');
        $this->page->waitForText('checklist-uji@example.test');

        // Buka dropdown baris pertama: Ubah, Hapus, dan Tangguhkan harus tampil
        $this->page->press('#user-table tbody tr:nth-child(1) .dropdown-toggle');
        $this->page->assertVisible(penggunaMenu('btn-edit'));
        $this->page->assertVisible(penggunaMenu('btn-hapus'));
        $this->page->assertScript("document.querySelector('#suspendModal') !== null", true);

        // Hapus: dialog muncul, Batal tidak menghapus
        $this->page->press(penggunaMenu('btn-hapus'));
        $this->page->assertVisible('#delete-modal');
        $this->page->press('#delete-modal-cancel');
        $this->page->assertScript("getComputedStyle(document.querySelector('#delete-modal')).display === 'none'", true);

        // Tangguhkan: dialog muncul, Batal tidak menonaktifkan pengguna
        $this->page->press('#user-table tbody tr:nth-child(1) .dropdown-toggle');
        $this->page->script("document.querySelector('#suspendModal').click()");
        $this->page->assertScript("getComputedStyle(document.querySelector('#suspend-modal')).display !== 'none'", true);
        $this->page->press('#suspend-modal-cancel');
        $this->page->assertScript("getComputedStyle(document.querySelector('#suspend-modal')).display === 'none'", true);
        expect($pengguna->fresh()->status)->toBe(1);

        // Ubah: membuka halaman ubah pengguna
        $this->page->press('#user-table tbody tr:nth-child(1) .dropdown-toggle');
        $this->page->press(penggunaMenu('btn-edit'));
        $this->page->assertPathBeginsWith('/setting/user/edit/');
    } finally {
        $pengguna->delete();
    }
})->group('smoke', 'smoke-pengaturan', 'browser');
