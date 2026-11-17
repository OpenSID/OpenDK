<?php

use Tests\BrowserTestCase;

uses(BrowserTestCase::class);

beforeEach(function () {
    \App\Models\Pesan::query()->delete();
    $this->user = \Tests\Browser\SessionState::loginAdminUser();
});

// =============================================================================
// MENU: PESAN -> PESAN MASUK
// =============================================================================

it('smoke test menu Pesan - Pesan Masuk', function () {
    if (\App\Models\Pesan::count() === 0) {
        \App\Models\Pesan::factory()->create(['jenis' => \App\Models\Pesan::PESAN_MASUK]);
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/pesan');
    $this->page->assertPathIs('/pesan');

    // Memastikan tombol buat pesan tampil
    $this->page->assertSee('Buat Pesan');

    // Panel kategori tampil
    $this->page->assertSee('Kategori');

    // List kategori tampil
    $this->page->assertSee('Pesan Masuk');
    $this->page->assertSee('Pesan Keluar');
    $this->page->assertSee('Arsip');

    // Field pilih desa dan cari pesan
    $this->page->assertPresent('select[name="das_data_desa_id"]');
    $this->page->assertVisible('input#cari-pesan');

    // Tabel pesan tampil
    $this->page->assertVisible('.table-hover');

    // Minimal 1 data pesan tampil
    $this->page->assertScript(
        "document.querySelector('.table-hover tbody tr') !== null"
    );

})->group('smoke', 'smoke-pesan', 'browser');


// =============================================================================
// MENU: PESAN -> PESAN KELUAR
// =============================================================================

it('smoke test menu Pesan - Pesan Keluar', function () {
    if (\App\Models\Pesan::where('jenis', \App\Models\Pesan::PESAN_KELUAR)->count() === 0) {
        \App\Models\Pesan::factory()->create(['jenis' => \App\Models\Pesan::PESAN_KELUAR]);
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/pesan/keluar');
    $this->page->assertPathIs('/pesan/keluar');

    // Memastikan tombol buat pesan tampil
    $this->page->assertSee('Buat Pesan');

    // Panel kategori tampil
    $this->page->assertSee('Kategori');

    // List kategori tampil
    $this->page->assertSee('Pesan Masuk');
    $this->page->assertSee('Pesan Keluar');
    $this->page->assertSee('Arsip');

    // Field pilih desa dan cari pesan
    $this->page->assertPresent('select[name="das_data_desa_id"]');
    $this->page->assertVisible('input#cari-pesan');

    // Tabel pesan tampil
    $this->page->assertVisible('.table-hover');

    // Minimal 1 data pesan tampil
    $this->page->assertScript(
        "document.querySelector('.table-hover tbody tr') !== null"
    );

})->group('smoke', 'smoke-pesan', 'browser');


// =============================================================================
// MENU: PESAN -> ARSIP
// =============================================================================

it('smoke test menu Pesan - Arsip', function () {
    if (\App\Models\Pesan::where('diarsipkan', 1)->count() === 0) {
        \App\Models\Pesan::factory()->create(['diarsipkan' => 1]);
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/pesan/arsip');
    $this->page->assertPathIs('/pesan/arsip');

    // Memastikan tombol buat pesan tampil
    $this->page->assertSee('Buat Pesan');

    // Panel kategori tampil
    $this->page->assertSee('Kategori');

    // List kategori tampil
    $this->page->assertSee('Pesan Masuk');
    $this->page->assertSee('Pesan Keluar');
    $this->page->assertSee('Arsip');

    // Field pilih desa dan cari pesan
    $this->page->assertPresent('select[name="das_data_desa_id"]');
    $this->page->assertVisible('input#cari-pesan');

    // Tabel pesan tampil
    $this->page->assertVisible('.table-hover');

    // Minimal 1 data pesan tampil
    $this->page->assertScript(
        "document.querySelector('.table-hover tbody tr') !== null"
    );

})->group('smoke', 'smoke-pesan', 'browser');

// =============================================================================
// CHECKLIST MANUAL C: TANDAI PESAN DIBACA DAN AKSI DI HALAMAN PESAN
// =============================================================================

// Init script untuk mensimulasikan kegagalan request tandai-dibaca:
// PATCH ke endpoint tandai-dibaca diarahkan ke alamat yang tidak bisa dijangkau.
// Pest hanya merekam console.log, jadi console.error dicatat sendiri ke window.__pesanConsoleError.
const PESAN_BLOKIR_TANDAI_DIBACA = <<<'JS'
(() => {
    window.__pesanConsoleError = [];
    const consoleError = console.error;
    console.error = function (...args) {
        window.__pesanConsoleError.push(args.map(arg => String(arg)).join(' '));
        return consoleError.apply(console, args);
    };

    const open = XMLHttpRequest.prototype.open;
    XMLHttpRequest.prototype.open = function (method, url, ...rest) {
        if (String(method).toUpperCase() === 'PATCH' && String(url).includes('tandai-dibaca')) {
            url = 'http://127.0.0.1:9/diblokir';
        }
        return open.call(this, method, url, ...rest);
    };
})();
JS;

function buatPesanUji(int $sudahDibaca = \App\Models\Pesan::BELUM_DIBACA): \App\Models\Pesan
{
    return \App\Models\Pesan::factory()->create([
        'jenis' => \App\Models\Pesan::PESAN_MASUK,
        'sudah_dibaca' => $sudahDibaca,
        'diarsipkan' => \App\Models\Pesan::NON_ARSIP,
    ]);
}

it('smoke test Pesan - pesan belum dibaca berubah menjadi sudah dibaca setelah dibuka', function () {
    $pesan = buatPesanUji();

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/pesan');
    $this->page->assertPresent("tr.unread a[href$=\"/pesan/{$pesan->id}\"]");

    $this->page->navigate("/pesan/{$pesan->id}");
    $this->page->assertPathIs("/pesan/{$pesan->id}");
    // Tunggu sampai request PATCH selesai (muncul di resource timing), bukan dengan jeda tetap
    $this->page->assertScript("performance.getEntriesByType('resource').some(e => e.name.includes('tandai-dibaca'))", true);

    $this->page->navigate('/pesan');
    $this->page->assertNotPresent("tr.unread a[href$=\"/pesan/{$pesan->id}\"]");
    expect($pesan->fresh()->sudah_dibaca)->toBe(\App\Models\Pesan::SUDAH_DIBACA);
})->group('smoke', 'smoke-pesan', 'browser');

it('smoke test Pesan - pesan yang sudah dibaca tidak mengirim request tandai-dibaca', function () {
    $pesan = buatPesanUji(\App\Models\Pesan::SUDAH_DIBACA);

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, "/pesan/{$pesan->id}");
    $this->page->assertPathIs("/pesan/{$pesan->id}");
    $this->page->wait(1);

    // Setara dengan memeriksa DevTools → Network: tidak ada request ke tandai-dibaca
    $this->page->assertScript("performance.getEntriesByType('resource').filter(e => e.name.includes('tandai-dibaca')).length === 0", true);
    $this->page->assertSourceMissing('tandai-dibaca');
})->group('smoke', 'smoke-pesan', 'browser');

it('smoke test Pesan - gagal menandai dibaca menampilkan error di console dan status tetap belum dibaca', function () {
    $pesan = buatPesanUji();

    // Pasang init script di context browser, lalu buka halaman baca pesan
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/pesan');
    $this->page->page()->context()->addInitScript(PESAN_BLOKIR_TANDAI_DIBACA);

    $this->page->navigate("/pesan/{$pesan->id}");
    $this->page->assertPathIs("/pesan/{$pesan->id}");
    // Tunggu sampai error gagal-tandai tercatat di console
    $this->page->assertScript("window.__pesanConsoleError.length > 0", true);

    $consoleErrors = $this->page->script("window.__pesanConsoleError.join('\\n')");
    expect($consoleErrors)->toContain('Gagal menandai pesan sebagai dibaca');

    $this->page->navigate('/pesan');
    $this->page->assertPresent("tr.unread a[href$=\"/pesan/{$pesan->id}\"]");
    expect($pesan->fresh()->sudah_dibaca)->toBe(\App\Models\Pesan::BELUM_DIBACA);
})->group('smoke', 'smoke-pesan', 'browser');

it('smoke test Pesan - tombol prev dan next pada halaman baca pesan berfungsi', function () {
    $pesan = buatPesanUji(\App\Models\Pesan::SUDAH_DIBACA);

    // Lebih dari 20 balasan agar pagination detail pesan aktif (20 per halaman)
    for ($i = 1; $i <= 25; $i++) {
        \App\Models\PesanDetail::create([
            'pesan_id' => $pesan->id,
            'text' => "Isi pesan uji {$i}",
            'pengirim' => 'Desa',
            'nama_pengirim' => 'Pengirim Uji',
        ]);
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, "/pesan/{$pesan->id}");
    $this->page->assertPresent('#next-links');

    $this->page->press('#next-links');
    $this->page->assertQueryStringHas('page', '2');
    // Tunggu halaman 2 siap (tombol prev sudah punya data halaman) sebelum klik
    // Pastikan handler klik prev sudah terpasang di halaman baru
    $this->page->assertScript("!!(window.jQuery && jQuery._data(document.querySelector('#prev-links'), 'events'))", true);

    $this->page->press('#prev-links');
    $this->page->assertQueryStringHas('page', '1');
})->group('smoke', 'smoke-pesan', 'browser');

it('smoke test Pesan - tombol Balas mengirim balasan', function () {
    $pesan = buatPesanUji(\App\Models\Pesan::SUDAH_DIBACA);

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, "/pesan/{$pesan->id}");
    $this->page->type('#reply_message', 'Balasan uji checklist');
    $this->page->press('#action-reply');

    $this->page->assertSee('Pesan berhasil dikirim!');
})->group('smoke', 'smoke-pesan', 'browser');

it('smoke test Pesan - tombol Arsipkan: Batal tidak mengarsipkan, OK mengarsipkan', function () {
    $pesan = buatPesanUji(\App\Models\Pesan::SUDAH_DIBACA);

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, "/pesan/{$pesan->id}");

    // Batal: dialog tertutup dan pesan tetap tidak diarsipkan
    $this->page->press('#arsip-action');
    $this->page->assertVisible('#modal-confirm');
    $this->page->press('#modal-confirm .modal-footer button[data-dismiss="modal"]');
    $this->page->assertScript("!document.querySelector('#modal-confirm').classList.contains('in')", true);
    expect($pesan->fresh()->diarsipkan)->toBe(\App\Models\Pesan::NON_ARSIP);

    // OK: pesan diarsipkan
    $this->page->press('#arsip-action');
    $this->page->assertVisible('#modal-confirm');
    $this->page->press('#modal-confirm-yes');
    $this->page->assertSee('Pesan berhasil diarsipkan!');
    expect($pesan->fresh()->diarsipkan)->toBe(\App\Models\Pesan::MASUK_ARSIP);
})->group('smoke', 'smoke-pesan', 'browser');
