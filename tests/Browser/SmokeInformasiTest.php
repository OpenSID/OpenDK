<?php

use App\Models\User;
use Tests\BrowserTestCase;

uses(BrowserTestCase::class);

beforeEach(function () {
    // Kita jalankan test dengan mode gabungan / default,
    // Walaupun menu Informasi biasanya menggunakan data lokal.
    $this->user = \Tests\Browser\SessionState::loginAdminUser();
});

dataset('informasi_menus', [
    // ['Nama Menu', 'URL Route', [Daftar Button yang diuji]]
    ['Prosedur', '/informasi/prosedur', ['btn-tambah', 'btn-lihat', 'btn-edit', 'btn-hapus', 'btn-download']],
    ['Regulasi', '/informasi/regulasi', ['btn-tambah', 'btn-lihat', 'btn-edit', 'btn-hapus', 'btn-download']],
    ['Potensi', '/informasi/potensi', ['btn-tambah', 'btn-lihat', 'btn-edit', 'btn-hapus']],
    ['Event', '/informasi/event', ['btn-tambah', 'btn-lihat-web', 'btn-edit', 'btn-hapus']],
    ['Artikel', '/informasi/artikel', ['btn-tambah', 'btn-edit', 'btn-hapus']],
    ['Artikel Kategori', '/informasi/kategori', ['btn-tambah', 'btn-edit', 'btn-hapus']], // URL di menu adalah informasi/kategori*
    ['Komentar Artikel', '/informasi/komentar-artikel', ['btn-hapus']],
    ['FAQ', '/informasi/faq', ['btn-tambah', 'btn-edit', 'btn-hapus']],
    ['Dokumen', '/informasi/form-dokumen', ['btn-tambah', 'btn-edit', 'btn-hapus']],
    ['Media Sosial', '/informasi/media-sosial', ['btn-tambah', 'btn-edit', 'btn-hapus']],
    ['Sinergi Program', '/informasi/sinergi-program', ['btn-tambah', 'btn-edit', 'btn-hapus']],
]);

it('smoke test menu informasi', function (string $menuName, string $url, array $buttons) {
    // Inject Dummy Data if table is empty
    if ($menuName === 'Prosedur' && \App\Models\Prosedur::count() === 0) {
        \App\Models\Prosedur::create(['judul_prosedur' => 'Test Prosedur', 'slug' => 'test-prosedur', 'file_prosedur' => 'test.pdf', 'mime_type' => 'application/pdf']);
    } else if ($menuName === 'Regulasi' && \App\Models\Regulasi::count() === 0) {
        $tipe = \App\Models\TipeRegulasi::firstOrCreate(['nama' => 'Test Tipe']);
        \App\Models\Regulasi::create(['judul' => 'Test Regulasi', 'tipe_regulasi' => $tipe->id, 'file_regulasi' => 'test.pdf', 'deskripsi' => 'test', 'mime_type' => 'application/pdf']);
    } else if ($menuName === 'Potensi' && \App\Models\Potensi::count() === 0) {
        $kategori = \App\Models\TipePotensi::firstOrCreate(['nama_kategori' => 'Test Kategori']);
        \App\Models\Potensi::create(['nama_potensi' => 'Test Potensi', 'kategori_id' => $kategori->id, 'deskripsi' => 'test', 'lokasi' => 'test']);
    } else if ($menuName === 'Event' && \App\Models\Event::count() === 0) {
        \App\Models\Event::factory()->create(['status' => 'OPEN']);
    } else if ($menuName === 'Artikel' && \App\Models\Artikel::count() === 0) {
        \App\Models\Artikel::factory()->create(['status' => 1]);
    } else if ($menuName === 'Artikel Kategori' && \App\Models\ArtikelKategori::count() === 0) {
        \App\Models\ArtikelKategori::create(['nama_kategori' => 'Test Kategori', 'status' => 1]);
    } else if ($menuName === 'Komentar Artikel' && \App\Models\Comment::count() === 0) {
        $artikel = \App\Models\Artikel::factory()->create(['status' => 1]);
        \App\Models\Comment::create(['das_artikel_id' => $artikel->id, 'email' => 'test@test.com', 'body' => 'Test', 'nama' => 'Test', 'status' => 1]);
    } else if ($menuName === 'FAQ' && \App\Models\Faq::count() === 0) {
        \App\Models\Faq::create(['question' => 'Test FAQ', 'answer' => 'Test', 'status' => 1]);
    } else if ($menuName === 'Dokumen' && \App\Models\FormDokumen::count() === 0) {
            $jenis = \App\Models\JenisDokumen::firstOrCreate(['nama' => 'Test Jenis']);
            \App\Models\FormDokumen::create(['nama_dokumen' => 'Test Dokumen', 'jenis_dokumen_id' => $jenis->id, 'is_published' => 1, 'file_dokumen' => 'test']);
        } else if ($menuName === 'Media Sosial' && \App\Models\MediaSosial::count() === 0) {
            \App\Models\MediaSosial::create(['nama' => 'Facebook', 'url' => 'http://facebook.com', 'logo' => 'fa-facebook', 'status' => 1]);
        } else if ($menuName === 'Sinergi Program') {
            \App\Models\SinergiProgram::query()->delete();
            \App\Models\SinergiProgram::create(['nama' => 'Test Program', 'url' => 'http://127.0.0.1', 'gambar' => '/img/logo.png', 'status' => 1, 'urutan' => 1]);
        }

    // 1. Kunjungi halaman menu menggunakan SessionState helper
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, $url);
    $this->page->assertPathIs($url);

    // 2. Verifikasi Tombol Tambah (Jika ada)
    if (in_array('btn-tambah', $buttons)) {
        $this->page->assertVisible('[data-testid="btn-tambah"]');
    }

    // Khusus untuk menu Dokumen ada filter spesifik
    if ($menuName === 'Dokumen') {
        $this->page->assertVisible('select[name="bulan"]');
        $this->page->assertVisible('select[name="tahun"]');
    }

    // 3. Verifikasi Datatable Render
    // Field Cari Tampil
    $this->page->assertVisible('input[type="search"]');
    // Field Tampilkan N Data Tampil
    $this->page->assertVisible('select[name$="_length"]');

    // Tunggu datatable selesai proses AJAX (DataTables Processing text hilang)
    $this->page->assertScript(
        "new Promise((resolve) => {
            const check = () => {
                const table = document.querySelector('[data-testid=\"table-informasi\"]');
                const tbody = table ? table.querySelector('tbody') : null;
                const processing = document.querySelector('.dataTables_processing');
                
                // Kondisi selesai: tbody memiliki child tr dan indikator processing tidak terlihat
                if (tbody && tbody.children.length > 0 && (!processing || processing.style.display === 'none')) {
                    resolve(true);
                } else {
                    setTimeout(check, 300);
                }
            };
            check();
        })",
        true
    );

    // 4. Verifikasi Minimal 1 Data Tampil (Class dataTables_empty tidak boleh ada)
    $this->page->assertMissing('.dataTables_empty');
    
    // 5. Verifikasi Aksi Datatable (Tombol Lihat, Edit, Hapus, Download) di row pertama
    $table = '[data-testid="table-informasi"]';
    if (in_array('btn-lihat', $buttons)) {
        $this->page->assertScript("document.querySelector('{$table} tbody tr:first-child [data-testid=\"btn-lihat\"]') !== null", true);
    }
    if (in_array('btn-lihat-web', $buttons)) {
        $this->page->assertPresent('[data-testid="table-informasi"] tbody tr:first-child [data-testid="btn-lihat-web"]');
    }
    if (in_array('btn-edit', $buttons)) {
        $this->page->assertScript("document.querySelector('{$table} tbody tr:first-child [data-testid=\"btn-edit\"]') !== null", true);
    }
    if (in_array('btn-hapus', $buttons)) {
        $this->page->assertScript("document.querySelector('{$table} tbody tr:first-child [data-testid=\"btn-hapus\"]') !== null", true);
    }
    if (in_array('btn-download', $buttons)) {
        $this->page->assertScript("document.querySelector('{$table} tbody tr:first-child [data-testid=\"btn-download\"]') !== null", true);
    }

})->with('informasi_menus')->group('smoke', 'smoke-informasi', 'browser');

// Tes terpisah untuk Media Terkait yang menggunakan Livewire
it('smoke test menu informasi - Media Terkait (Livewire)', function () {
    \App\Models\MediaTerkait::where('nama', 'Test Media')->delete();
    if (\App\Models\MediaTerkait::count() === 0) {
        \App\Models\MediaTerkait::create(['nama' => 'Test Media', 'url' => 'http://test.com', 'logo' => 'test.png', 'status' => 1]);
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/informasi/media-terkait');
    $this->page->assertPathIs('/informasi/media-terkait');
    
    // Tombol Tambah
    $this->page->assertVisible('button[wire\:click="create"]');
    // Tombol Hapus Terpilih
    $this->page->assertVisible('button[wire\:click="deleteSelected"]');
    // Filter status
    $this->page->assertVisible('select[wire\:model\.live="status"]');

    // Pastikan tabel muncul (Tabel dibuat menggunakan tag <table> dari blade components)
    // Walaupun tidak ada id "table-informasi", kita bisa cari isinya
    $this->page->assertSee('Test Media');

    // Minimal 1 data tampil (di Livewire, jika data ada, tbody tr td akan tampil, kalau kosong biasanya teks "Data tidak ditemukan")
    // Pastikan tidak ada tulisan "Data tidak ditemukan" (jika OpenDK menggunakan pesan ini)
    // Untuk lebih aman, kita hanya ngecek teks 'Test Media' (sudah dilakukan di atas)
})->group('smoke', 'smoke-informasi', 'browser');

// =============================================================================
// CHECKLIST MANUAL A: SINERGI PROGRAM - URUTAN NAIK / TURUN
// =============================================================================

/**
 * Siapkan 3 baris Sinergi Program dengan urutan 1..3 (Program A, B, C).
 */
function siapkanDataSinergiUrutan(): array
{
    \App\Models\SinergiProgram::query()->delete();

    return collect(['Program A', 'Program B', 'Program C'])
        ->map(fn (string $nama, int $i) => \App\Models\SinergiProgram::create([
            'nama' => $nama,
            'url' => 'http://127.0.0.1',
            'gambar' => '/img/logo.png',
            'status' => 1,
            'urutan' => $i + 1,
        ]))
        ->values()
        ->all();
}

// Selector tombol toggle dropdown aksi pada baris ke-$baris
function sinergiToggle(int $baris): string
{
    return "#sinergi-program-table tbody tr:nth-child({$baris}) .dropdown-toggle";
}

// Selector tombol Naik/Turun (form POST) di dropdown yang sedang terbuka.
// Saat dibuka, plugin dropdown memindahkan <ul class="dropdown-menu"> ke <body>,
// sehingga selector harus mencari dari body, bukan dari baris tabel.
function sinergiAksi(string $aksi): string
{
    // Dropdown Sinergi dipindah ke <body> saat dibuka, dropdown Navigasi tetap di dalam baris.
    return "body > ul.dropdown-menu[style*=\"display: block\"] [data-testid=\"{$aksi}\"] button, .btn-group.open [data-testid=\"{$aksi}\"] button";
}

// Selector sel nama pada baris ke-$baris
function sinergiBaris(int $baris): string
{
    return "#sinergi-program-table tbody tr:nth-child({$baris})";
}

it('smoke test Sinergi Program - item Naik dan Turun tersedia di setiap baris', function () {
    siapkanDataSinergiUrutan();

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/informasi/sinergi-program');
    $this->page->assertPathIs('/informasi/sinergi-program');
    $this->page->waitForText('Program C');

    $this->page->assertScript("document.querySelectorAll('#sinergi-program-table tbody tr [data-testid=\"btn-naik\"]').length === 3", true);
    $this->page->assertScript("document.querySelectorAll('#sinergi-program-table tbody tr [data-testid=\"btn-turun\"]').length === 3", true);
})->group('smoke', 'smoke-informasi', 'browser');

it('smoke test Sinergi Program - Naik dan Turun mengubah urutan, menampilkan pesan, dan bertahan setelah refresh', function () {
    siapkanDataSinergiUrutan();

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/informasi/sinergi-program');
    $this->page->assertPathIs('/informasi/sinergi-program');
    $this->page->waitForText('Program C');

    // 1. Turun pada baris pertama: baris pindah ke bawah (urutan: B, A, C)
    //    Tidak adanya error 419 dibuktikan dengan munculnya pesan sukses setelah POST.
    $this->page->press(sinergiToggle(1))->press(sinergiAksi('btn-turun'));
    $this->page->assertSee('Urutan Sinergi Program berhasil diubah!');
    $this->page->assertSeeIn(sinergiBaris(1), 'Program B');
    $this->page->assertSeeIn(sinergiBaris(2), 'Program A');

    // 2. Naik pada baris kedua: baris pindah ke atas (urutan: A, B, C)
    $this->page->press(sinergiToggle(2))->press(sinergiAksi('btn-naik'));
    $this->page->assertSee('Urutan Sinergi Program berhasil diubah!');
    $this->page->assertSeeIn(sinergiBaris(1), 'Program A');
    $this->page->assertSeeIn(sinergiBaris(2), 'Program B');

    // 3. Naik pada baris paling atas: muncul error, urutan tidak berubah
    $this->page->press(sinergiToggle(1))->press(sinergiAksi('btn-naik'));
    $this->page->assertSee('Urutan Sinergi Program sudah berada diurutan pertama!');
    $this->page->assertSeeIn(sinergiBaris(1), 'Program A');

    // 4. Turun pada baris paling bawah: muncul error, urutan tidak berubah
    $this->page->press(sinergiToggle(3))->press(sinergiAksi('btn-turun'));
    $this->page->assertSee('Urutan Sinergi Program sudah berada diurutan terakhir!');
    $this->page->assertSeeIn(sinergiBaris(3), 'Program C');

    // 5. Urutan tersimpan di database
    expect(\App\Models\SinergiProgram::orderBy('urutan')->pluck('nama')->all())
        ->toBe(['Program A', 'Program B', 'Program C']);

    // 6. Setelah refresh, urutan tetap sesuai perubahan
    $this->page->refresh();
    $this->page->waitForText('Program C');
    $this->page->assertSeeIn(sinergiBaris(1), 'Program A');
    $this->page->assertSeeIn(sinergiBaris(2), 'Program B');
    $this->page->assertSeeIn(sinergiBaris(3), 'Program C');
})->group('smoke', 'smoke-informasi', 'browser');

it('smoke test Sinergi Program - route urut menolak GET dengan 405', function () {
    $data = siapkanDataSinergiUrutan();

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/informasi/sinergi-program');
    $this->page->waitForText('Program C');

    $id = $data[0]->id;
    $this->page->assertScript("fetch('/informasi/sinergi-program/urut/{$id}/1', { credentials: 'same-origin' }).then(r => r.status === 405)", true);
})->group('smoke', 'smoke-informasi', 'browser');

it('smoke test Sinergi Program - item Naik dan Turun rapi dan area klik penuh', function () {
    siapkanDataSinergiUrutan();

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/informasi/sinergi-program');
    $this->page->waitForText('Program C');
    $this->page->press(sinergiToggle(1));

    // Tombol Naik dan Turun sejajar (left dan width sama), dan mengisi lebar itemnya.
    // Dropdown harus sudah terbuka (width > 0) agar pengecekan tidak lolos secara semu.
    $this->page->assertScript(<<<'JS'
        (() => {
            const menu = document.querySelector('body > ul.dropdown-menu[style*="display: block"]');
            const naik = menu.querySelector('[data-testid="btn-naik"]');
            const turun = menu.querySelector('[data-testid="btn-turun"]');
            const btnNaik = naik.querySelector('button').getBoundingClientRect();
            const btnTurun = turun.querySelector('button').getBoundingClientRect();
            const liNaik = naik.getBoundingClientRect();
            return btnNaik.width > 0
                && Math.abs(btnNaik.left - btnTurun.left) < 1
                && Math.abs(btnNaik.width - btnTurun.width) < 1
                && Math.abs(btnNaik.width - liNaik.width) < 2;
        })()
        JS, true);
})->group('smoke', 'smoke-informasi', 'browser');


// Selector menu dropdown Sinergi yang terbuka (dipindah ke <body> saat dibuka, atau tetap di baris)
function sinergiMenu(string $testid): string
{
    return "body > ul.dropdown-menu[style*=\"display: block\"] [data-testid=\"{$testid}\"], .btn-group.open [data-testid=\"{$testid}\"]";
}

it('smoke test Sinergi Program - Ubah dan Hapus dari dropdown aksi berfungsi', function () {
    $data = siapkanDataSinergiUrutan();

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/informasi/sinergi-program');
    $this->page->waitForText('Program C');

    // Ubah: membuka halaman ubah Sinergi Program
    $this->page->press(sinergiToggle(1))->press(sinergiMenu('btn-edit'));
    $this->page->assertPathIs("/informasi/sinergi-program/edit/{$data[0]->id}");

    // Hapus: dialog konfirmasi muncul, Batal tidak menghapus data
    $this->page->navigate('/informasi/sinergi-program');
    $this->page->waitForText('Program C');
    $this->page->press(sinergiToggle(1))->press(sinergiMenu('btn-hapus'));
    $this->page->assertVisible('#delete-modal');
    $this->page->press('#delete-modal-cancel');
    $this->page->assertScript("getComputedStyle(document.querySelector('#delete-modal')).display === 'none'", true);
    expect(\App\Models\SinergiProgram::count())->toBe(3);
})->group('smoke', 'smoke-informasi', 'browser');

it('smoke test Sinergi Program - tampil di lebar mobile tanpa scroll horizontal', function () {
    siapkanDataSinergiUrutan();

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/informasi/sinergi-program');
    $this->page->resize(390, 844);
    $this->page->waitForText('Program C');

    $this->page->assertVisible('#sinergi-program-table');
    $this->page->assertScript("document.documentElement.scrollWidth <= window.innerWidth + 1", true);
})->group('smoke', 'smoke-informasi', 'browser');
