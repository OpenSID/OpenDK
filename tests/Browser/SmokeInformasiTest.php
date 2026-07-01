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
    ['Event', '/informasi/event', ['btn-tambah', 'btn-lihat', 'btn-edit', 'btn-hapus']],
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
    if (in_array('btn-lihat', $buttons)) {
        $this->page->assertVisible('[data-testid="table-informasi"] tbody tr:first-child [data-testid="btn-lihat"]');
    }
    if (in_array('btn-edit', $buttons)) {
        $this->page->assertVisible('[data-testid="table-informasi"] tbody tr:first-child [data-testid="btn-edit"]');
    }
    if (in_array('btn-hapus', $buttons)) {
        $this->page->assertVisible('[data-testid="table-informasi"] tbody tr:first-child [data-testid="btn-hapus"]');
    }
    if (in_array('btn-download', $buttons)) {
        $this->page->assertVisible('[data-testid="table-informasi"] tbody tr:first-child [data-testid="btn-download"]');
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
