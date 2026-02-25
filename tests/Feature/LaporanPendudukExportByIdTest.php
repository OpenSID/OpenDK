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

use App\Exports\LaporanPendudukByIdExport;
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

// =============================================================================
// BASIC EXPORT BY ID TESTS
// =============================================================================

test('export laporan penduduk by id', function () {
    // Arrange: Buat data test
    LaporanPenduduk::query()->delete();
    
    $desa = DataDesa::factory()->create([
        'nama' => 'Desa Test Export By ID'
    ]);

    $laporan = LaporanPenduduk::factory()->create([
        'judul' => 'Laporan Test Export',
        'bulan' => '01',
        'tahun' => '2024',
        'nama_file' => 'laporan-test-export.pdf',
        'id_laporan_penduduk' => 1001,
        'desa_id' => $desa->desa_id,
    ]);

    // Prepare data as JSON
    $data = json_encode([
        'id' => $laporan->id,
        'nama_desa' => $desa->nama,
        'judul' => $laporan->judul,
        'bulan' => (int) $laporan->bulan,
        'tahun' => (int) $laporan->tahun,
        'tanggal_lapor' => $laporan->created_at?->format('d/m/Y') ?? '',
    ]);

    // Act: Export menggunakan class langsung (testing the export class)
    $export = new LaporanPendudukByIdExport(false, $data);
    $collection = $export->collection();

    // Assert: Periksa bahwa export berhasil
    expect($collection)->toHaveCount(1)
        ->and($collection->first()['id'])->toBe($laporan->id)
        ->and($collection->first()['nama_desa'])->toBe('Desa Test Export By ID')
        ->and($collection->first()['judul'])->toBe('Laporan Test Export');
});

test('export laporan penduduk by id returns correct data', function () {
    // Arrange: Buat data test
    LaporanPenduduk::query()->delete();
    
    $desa = DataDesa::factory()->create([
        'nama' => 'Desa Test Data'
    ]);

    $laporan = LaporanPenduduk::factory()->create([
        'judul' => 'Laporan Test Data',
        'bulan' => '03',
        'tahun' => '2024',
        'nama_file' => 'laporan-test-data.pdf',
        'id_laporan_penduduk' => 1002,
        'desa_id' => $desa->desa_id,
    ]);

    // Prepare data as JSON
    $data = json_encode([
        'id' => $laporan->id,
        'nama_desa' => $desa->nama,
        'judul' => $laporan->judul,
        'bulan' => 3,
        'tahun' => 2024,
        'tanggal_lapor' => $laporan->created_at?->format('d/m/Y') ?? '',
    ]);

    // Act: Export menggunakan class langsung
    $export = new LaporanPendudukByIdExport(false, $data);
    $collection = $export->collection();

    // Assert: Periksa data yang diekspor (collection returns array with one element)
    expect($collection)->toHaveCount(1)
        ->and($collection->first()['judul'])->toBe('Laporan Test Data')
        ->and($collection->first()['nama_desa'])->toBe('Desa Test Data')
        ->and($collection->first()['bulan'])->toEqual(3)
        ->and($collection->first()['tahun'])->toEqual(2024);
});

test('export laporan penduduk by id headings', function () {
    // Arrange: Prepare dummy data
    $data = json_encode([
        'id' => 1,
        'nama_desa' => 'Test',
        'judul' => 'Test',
        'bulan' => 1,
        'tahun' => 2024,
        'tanggal_lapor' => '',
    ]);

    // Act: Buat instance export dan ambil headings
    $export = new LaporanPendudukByIdExport(false, $data);
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

test('export laporan penduduk by id with special characters in judul', function () {
    // Arrange: Buat data dengan karakter khusus
    LaporanPenduduk::query()->delete();
    
    $desa = DataDesa::factory()->create();

    $laporan = LaporanPenduduk::factory()->create([
        'judul' => 'Laporan & Special <Characters> "Quotes"',
        'bulan' => '05',
        'tahun' => '2024',
        'nama_file' => 'laporan-special.pdf',
        'id_laporan_penduduk' => 1003,
        'desa_id' => $desa->desa_id,
    ]);

    // Prepare data as JSON
    $data = json_encode([
        'id' => $laporan->id,
        'nama_desa' => $desa->nama,
        'judul' => $laporan->judul,
        'bulan' => 5,
        'tahun' => 2024,
        'tanggal_lapor' => $laporan->created_at?->format('d/m/Y') ?? '',
    ]);

    // Act: Export
    $export = new LaporanPendudukByIdExport(false, $data);
    $collection = $export->collection();

    // Assert: Karakter khusus harus tetap terjaga
    expect($collection)->toHaveCount(1)
        ->and($collection->first()['judul'])->toBe('Laporan & Special <Characters> "Quotes"');
});

test('export laporan penduduk by id with unicode characters', function () {
    // Arrange: Buat data dengan unicode
    LaporanPenduduk::query()->delete();
    
    $desa = DataDesa::factory()->create([
        'nama' => 'Desa Cékér Ménténg'
    ]);

    $laporan = LaporanPenduduk::factory()->create([
        'judul' => 'Laporan Unicode Test',
        'bulan' => '06',
        'tahun' => '2024',
        'nama_file' => 'laporan-unicode.pdf',
        'id_laporan_penduduk' => 1004,
        'desa_id' => $desa->desa_id,
    ]);

    // Prepare data as JSON
    $data = json_encode([
        'id' => $laporan->id,
        'nama_desa' => $desa->nama,
        'judul' => $laporan->judul,
        'bulan' => 6,
        'tahun' => 2024,
        'tanggal_lapor' => $laporan->created_at?->format('d/m/Y') ?? '',
    ]);

    // Act: Export
    $export = new LaporanPendudukByIdExport(false, $data);
    $collection = $export->collection();

    // Assert: Unicode harus tetap terjaga
    expect($collection)->toHaveCount(1)
        ->and($collection->first()['nama_desa'])->toBe('Desa Cékér Ménténg');
});

test('export laporan penduduk by id with gabungan mode active', function () {
    // Arrange: Aktifkan database gabungan
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );

    $desa = DataDesa::factory()->create();
    $laporan = LaporanPenduduk::factory()->create([
        'desa_id' => $desa->desa_id,
    ]);

    // Prepare data as JSON
    $data = json_encode([
        'id' => $laporan->id,
        'nama_desa' => $desa->nama,
        'judul' => $laporan->judul,
        'bulan' => (int) $laporan->bulan,
        'tahun' => (int) $laporan->tahun,
        'tanggal_lapor' => $laporan->created_at?->format('d/m/Y') ?? '',
    ]);

    // Act: Export dengan mode gabungan
    $export = new LaporanPendudukByIdExport(true, $data);

    // Assert: Instance harus bisa dibuat
    expect($export)->toBeInstanceOf(LaporanPendudukByIdExport::class)
        ->and(method_exists($export, 'collection'))->toBeTrue();
});

test('export laporan penduduk by id switches to local when gabungan inactive', function () {
    // Arrange: Nonaktifkan database gabungan
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '0']
    );

    $desa = DataDesa::factory()->create();
    $laporan = LaporanPenduduk::factory()->create([
        'desa_id' => $desa->desa_id,
    ]);

    // Prepare data as JSON
    $data = json_encode([
        'id' => $laporan->id,
        'nama_desa' => $desa->nama,
        'judul' => $laporan->judul,
        'bulan' => (int) $laporan->bulan,
        'tahun' => (int) $laporan->tahun,
        'tanggal_lapor' => $laporan->created_at?->format('d/m/Y') ?? '',
    ]);

    // Act: Export dengan mode lokal
    $export = new LaporanPendudukByIdExport(false, $data);
    $collection = $export->collection();

    // Assert: Harus menggunakan data lokal
    expect($collection)->toHaveCount(1)
        ->and($collection->first()['nama_desa'])->toBe($desa->nama);
});

test('export laporan penduduk by id performance', function () {
    // Arrange: Buat data test
    LaporanPenduduk::query()->delete();
    
    $desa = DataDesa::factory()->create();
    $laporan = LaporanPenduduk::factory()->create([
        'desa_id' => $desa->desa_id,
    ]);

    // Prepare data as JSON
    $data = json_encode([
        'id' => $laporan->id,
        'nama_desa' => $desa->nama,
        'judul' => $laporan->judul,
        'bulan' => (int) $laporan->bulan,
        'tahun' => (int) $laporan->tahun,
        'tanggal_lapor' => $laporan->created_at?->format('d/m/Y') ?? '',
    ]);

    // Act: Export dan monitor waktu
    $startTime = microtime(true);
    
    $export = new LaporanPendudukByIdExport(false, $data);
    $collection = $export->collection();
    
    $endTime = microtime(true);
    $executionTime = $endTime - $startTime;

    // Assert: Export harus cepat (< 1 detik)
    expect($collection)->toHaveCount(1)
        ->and($executionTime)->toBeLessThan(1);
});
