<?php

use App\Exports\LaporanPendudukExport;
use App\Models\DataDesa;
use App\Models\LaporanPenduduk;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Maatwebsite\Excel\Facades\Excel;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->withoutMiddleware();

    // Menonaktifkan database gabungan untuk testing
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '0']
    );
});

test('export excel gabungan inactive', function () {
    // Arrange: Bersihkan data dan buat data test baru
    LaporanPenduduk::query()->delete();

    $desa = DataDesa::factory()->create([
        'nama' => 'Desa Test Export'
    ]);

    LaporanPenduduk::factory()->create([
        'judul' => 'Laporan Test 1',
        'bulan' => '01',
        'tahun' => '2024',
        'nama_file' => 'laporan-test-1.pdf',
        'id_laporan_penduduk' => 1001,
        'desa_id' => $desa->desa_id,
    ]);

    LaporanPenduduk::factory()->create([
        'judul' => 'Laporan Test 2',
        'bulan' => '02',
        'tahun' => '2024',
        'nama_file' => 'laporan-test-2.pdf',
        'id_laporan_penduduk' => 1002,
        'desa_id' => $desa->desa_id,
    ]);

    // Act: Export laporan penduduk dengan mode gabungan non-aktif
    Excel::fake();

    $export = new LaporanPendudukExport(false);
    $collection = $export->collection();

    // Assert: Periksa bahwa export berhasil
    expect($collection)->toHaveCount(2)
        ->and(LaporanPenduduk::count())->toBe(2);
});

test('export excel gabungan active', function () {
    // Arrange: Aktifkan database gabungan
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );

    // Act: Test export dengan mode gabungan aktif menggunakan class langsung
    $export = new LaporanPendudukExport(true);

    // Assert: Pastikan export dapat diinstansiasi dengan mode gabungan
    expect($export)->toBeInstanceOf(LaporanPendudukExport::class);
});

test('export laporan penduduk headings', function () {
    // Arrange: Buat instance export
    $export = new LaporanPendudukExport(false);

    // Act: Ambil headings
    $headings = $export->headings();

    // Assert: Periksa struktur headings
    $expectedHeadings = [
        'ID',
        'DESA',
        'JUDUL',
        'BULAN',
        'TAHUN',
        'TANGGAL LAPOR',
    ];

    expect($headings)->toBe($expectedHeadings);
});

test('export laporan penduduk local data', function () {
    // Arrange: Bersihkan data dan buat data test baru
    LaporanPenduduk::query()->delete();

    $desa = DataDesa::factory()->create([
        'nama' => 'Desa Test Local'
    ]);

    $laporan = LaporanPenduduk::factory()->create([
        'judul' => 'Laporan Test Local',
        'bulan' => '03',
        'tahun' => '2024',
        'nama_file' => 'laporan-local.pdf',
        'id_laporan_penduduk' => 2001,
        'desa_id' => $desa->desa_id,
    ]);

    // Act: Buat export dengan mode lokal
    $export = new LaporanPendudukExport(false);
    $collection = $export->collection();

    // Assert: Periksa data yang diekspor
    expect($collection)->toHaveCount(1);

    $exportedData = $collection->first();
    expect($exportedData['id'])->toBe($laporan->id)
        ->and($exportedData['nama_desa'])->toBe('Desa Test Local')
        ->and($exportedData['judul'])->toBe('Laporan Test Local')
        ->and($exportedData['bulan'])->toEqual(3)
        ->and($exportedData['tahun'])->toEqual(2024);
});

test('export laporan penduduk no data', function () {
    // Pastikan tidak ada data laporan penduduk
    LaporanPenduduk::query()->delete();

    // Act: Export ketika tidak ada data
    $export = new LaporanPendudukExport(false);
    $collection = $export->collection();

    // Assert: Periksa bahwa collection kosong
    expect($collection)->toHaveCount(0);
});
