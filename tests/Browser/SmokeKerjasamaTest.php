<?php

use Tests\BrowserTestCase;
use Illuminate\Support\Facades\Http;

uses(BrowserTestCase::class);

beforeEach(function () {
    $this->user = \Tests\Browser\SessionState::loginAdminUser();
});

// =============================================================================
// MENU: KERJASAMA → PENDAFTARAN KERJASAMA (Livewire)
// =============================================================================
//
// Halaman ini memanggil ApiService->terdaftar() dan ApiService->getFormRegister()
// yang menghit API eksternal. Kita gunakan Http::fake() agar:
//   - terdaftar() mengembalikan `success: false` → memaksa blok form tampil
//   - getFormRegister() mengembalikan status_langganan 'belum terdaftar'
//

it('smoke test menu Kerjasama - Pendaftaran Kerjasama (Livewire)', function () {
    // Mock API eksternal agar form pendaftaran selalu tampil.
    // ApiService->terdaftar() memanggil POST ke /pelanggan/terdaftar-kecamatan
    // ApiService->getFormRegister() memanggil GET ke /pelanggan/form-register-kecamatan
    //
    // Kita fake terdaftar() mengembalikan HTTP 422/404 agar $response['success'] = false
    // Lalu getFormRegister() mengembalikan data form register yang valid
    Http::fake([
        '*/pelanggan/terdaftar-kecamatan*' => Http::response(
            // HTTP 422 agar ApiService mengembalikan ['success' => false, ...]
            ['message' => 'Belum terdaftar'],
            422
        ),
        '*/pelanggan/form-register-kecamatan*' => Http::response([
            'data' => [
                'status_langganan' => 'belum terdaftar',
            ],
        ], 200),
        // Fallback
        '*' => Http::response([], 200),
    ]);

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/kerjasama/pendaftaran-kerjasama');
    $this->page->assertPathIs('/kerjasama/pendaftaran-kerjasama');

    // 1. Panel informasi pendaftaran kerjasama tampil (box pertama selalu ada)
    $this->page->assertSee('Pendaftaran Kerjasama');

    // 2. Panel Form Pendaftaran Kerjasama tampil
    $this->page->assertSee('Form Pendaftaran Kerjasama');

    // 3. Tombol Unduh Dokumen Kerjasama tampil
    $this->page->assertVisible('a[href*="template"]');

    // 4. Field-field form tampil
    // Field Email
    $this->page->assertVisible('input[wire\\:model="email"]');
    // Field Status Registrasi
    $this->page->assertVisible('input[wire\\:model="status_registrasi"]');
    // Field Kode Kecamatan
    $this->page->assertVisible('input[wire\\:model="kecamatan_id"]');
    // Field Domain Kecamatan
    $this->page->assertVisible('input[wire\\:model="domain"]');
    // Field Nama Kontak
    $this->page->assertVisible('input[wire\\:model="kontak_nama"]');
    // Field No HP Kontak
    $this->page->assertVisible('input[wire\\:model="kontak_no_hp"]');

    // 5. Area Unggah Dokumen tampil (x-upload-file component → input type="file")
    $this->page->assertVisible('input[type="file"]');

    // 6. Tombol Batal tampil
    $this->page->assertVisible('button[type="reset"]');
    $this->page->assertSee('Batal');

    // 7. Tombol Simpan tampil (walaupun dalam kondisi disabled karena permohonan belum diisi)
    $this->page->assertVisible('button[wire\\:click="register"]');
    $this->page->assertSee('Simpan');
})->group('smoke', 'smoke-kerjasama', 'browser');
