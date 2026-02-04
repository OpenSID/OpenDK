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

use App\Models\PpidPengaturan;
use App\Models\PpidPertanyaan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    // Clean up database before each test
    DB::table('ppid_pengaturan')->truncate();
    DB::table('ppid_pertanyaan')->truncate();
});

afterEach(function () {
    // Clean up database after each test
    DB::table('ppid_pengaturan')->truncate();
    DB::table('ppid_pertanyaan')->truncate();
});

// ==================== PPID PENGATURAN TESTS ====================

test('should display PPID pengaturan page correctly', function () {
    $this->actingAs(User::role('super-admin')->first())
        ->visit('/ppid/pengaturan')
        ->assertSee('Pengaturan PPID')
        ->assertSee('Banner PPID')
        ->assertSee('Judul PPID')
        ->assertSee('Informasi')
        ->assertSee('Batas Pengajuan')
        ->assertSee('Layanan Permohonan')
        ->assertSee('Layanan Keberatan');
})->group('browser', 'ppid', 'pengaturan');

test('should create default PPID pengaturan if not exists', function () {
    // Ensure no pengaturan exists
    DB::table('ppid_pengaturan')->truncate();

    $this->actingAs(User::role('super-admin')->first())
        ->visit('/ppid/pengaturan')
        ->assertSee('Pengaturan PPID');

    // Verify pengaturan was created
    $pengaturan = PpidPengaturan::first();
    expect($pengaturan)->not->toBeNull();
    expect($pengaturan->ppid_judul)->toBe('Layanan Informasi Publik Desa');
    expect($pengaturan->ppid_permohonan)->toBe('1');
    expect($pengaturan->ppid_keberatan)->toBe('1');
})->group('browser', 'ppid', 'pengaturan');

test('should update PPID pengaturan successfully', function () {
    // Create initial pengaturan
    $pengaturan = PpidPengaturan::create([
        'ppid_judul' => 'Layanan Informasi Publik Desa',
        'ppid_informasi' => 'Informasi deskripsi',
        'ppid_batas_pengajuan' => 10,
        'ppid_permohonan' => '1',
        'ppid_keberatan' => '1',
    ]);

    $this->actingAs(User::role('super-admin')->first())
        ->visit('/ppid/pengaturan')
        ->type('ppid_judul', 'PPID Desa Digital')
        ->type('ppid_informasi', 'Deskripsi layanan PPID')
        ->type('ppid_batas_pengajuan', '14')
        ->select('ppid_permohonan', '0')
        ->select('ppid_keberatan', '1')
        ->press('Simpan')
        ->assertSee('Pengaturan PPID berhasil disimpan');

    // Verify data was updated
    $pengaturan->refresh();
    expect($pengaturan->ppid_judul)->toBe('PPID Desa Digital');
    expect($pengaturan->ppid_informasi)->toBe('Deskripsi layanan PPID');
    expect($pengaturan->ppid_batas_pengajuan)->toBe(14);
    expect($pengaturan->ppid_permohonan)->toBe('0');
    expect($pengaturan->ppid_keberatan)->toBe('1');
})->group('browser', 'ppid', 'pengaturan');

test('should validate required fields when updating pengaturan', function () {
    $pengaturan = PpidPengaturan::create([
        'ppid_judul' => 'Test Judul',
        'ppid_permohonan' => '1',
        'ppid_keberatan' => '1',
    ]);

    $this->actingAs(User::role('super-admin')->first())
        ->visit('/ppid/pengaturan')
        ->type('ppid_judul', '') // Empty judul
        ->type('ppid_batas_pengajuan', 'abc') // Invalid number
        ->press('Simpan')
        ->assertSee('Ada kesalahan pada kolom inputan');
})->group('browser', 'ppid', 'pengaturan');

test('should upload banner image successfully', function () {
    $pengaturan = PpidPengaturan::create([
        'ppid_judul' => 'Test Judul',
        'ppid_permohonan' => '1',
        'ppid_keberatan' => '1',
    ]);

    // Create a test image
    $imagePath = storage_path('app/testing/test-banner.jpg');
    $directory = dirname($imagePath);
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }

    // Copy a sample image from public folder if exists
    $sourceImage = public_path('img/no-image.png');
    if (file_exists($sourceImage)) {
        copy($sourceImage, $imagePath);

        $this->actingAs(User::role('super-admin')->first())
            ->visit('/ppid/pengaturan')
            ->attach('ppid_banner', $imagePath)
            ->press('Simpan')
            ->assertSee('Pengaturan PPID berhasil disimpan');

        $pengaturan->refresh();
        expect($pengaturan->ppid_banner)->not->toBeEmpty();
    }

    // Cleanup
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
})->group('browser', 'ppid', 'pengaturan');

test('should validate banner file type', function () {
    $pengaturan = PpidPengaturan::create([
        'ppid_judul' => 'Test Judul',
        'ppid_permohonan' => '1',
        'ppid_keberatan' => '1',
    ]);

    // Create invalid file
    $invalidFile = storage_path('app/testing/test-document.pdf');
    $directory = dirname($invalidFile);
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
    file_put_contents($invalidFile, 'Invalid file content');

    $this->actingAs(User::role('super-admin')->first())
        ->visit('/ppid/pengaturan')
        ->attach('ppid_banner', $invalidFile)
        ->press('Simpan')
        ->assertSee('Ada kesalahan pada kolom inputan');

    // Cleanup
    if (file_exists($invalidFile)) {
        unlink($invalidFile);
    }
})->group('browser', 'ppid', 'pengaturan');

// ==================== PPID PERTANYAAN TESTS ====================

test('should display pertanyaan tabs correctly', function () {
    $this->actingAs(User::role('super-admin')->first())
        ->visit('/ppid/pengaturan')
        ->assertSee('Form Permohonan')
        ->assertSee('Informasi')
        ->assertSee('Mendapatkan')
        ->assertSee('Keberatan');
})->group('browser', 'ppid', 'pertanyaan');

test('should add new pertanyaan for Informasi type', function () {
    $judul = 'Apa saja dokumen yang tersedia?';

    $this->actingAs(User::role('super-admin')->first())
        ->visit('/ppid/pengaturan')
        ->click('button[onclick="showTambahPertanyaanModal(1)"]')
        ->pause(500) // Wait for modal
        ->type('ppid_judul', $judul)
        ->select('ppid_status', '1')
        ->press('Simpan')
        ->pause(1000) // Wait for AJAX
        ->visit('/ppid/pengaturan')
        ->assertSee($judul);

    // Verify in database
    $pertanyaan = PpidPertanyaan::where('ppid_judul', $judul)
        ->where('ppid_tipe', '1')
        ->first();
    expect($pertanyaan)->not->toBeNull();
    expect($pertanyaan->ppid_status)->toBe('1');
})->group('browser', 'ppid', 'pertanyaan');

test('should add new pertanyaan for Mendapatkan type', function () {
    $judul = 'Bagaimana cara mendapatkan informasi?';

    $this->actingAs(User::role('super-admin')->first())
        ->visit('/ppid/pengaturan')
        ->click('button[onclick="showTambahPertanyaanModal(2)"]')
        ->pause(500)
        ->type('ppid_judul', $judul)
        ->select('ppid_status', '1')
        ->press('Simpan')
        ->pause(1000)
        ->visit('/ppid/pengaturan')
        ->assertSee($judul);

    // Verify in database
    $pertanyaan = PpidPertanyaan::where('ppid_judul', $judul)
        ->where('ppid_tipe', '2')
        ->first();
    expect($pertanyaan)->not->toBeNull();
})->group('browser', 'ppid', 'pertanyaan');

test('should add new pertanyaan for Keberatan type', function () {
    $judul = 'Bagaimana mengajukan keberatan?';

    $this->actingAs(User::role('super-admin')->first())
        ->visit('/ppid/pengaturan')
        ->click('button[onclick="showTambahPertanyaanModal(0)"]')
        ->pause(500)
        ->type('ppid_judul', $judul)
        ->select('ppid_status', '1')
        ->press('Simpan')
        ->pause(1000)
        ->visit('/ppid/pengaturan')
        ->assertSee($judul);

    // Verify in database
    $pertanyaan = PpidPertanyaan::where('ppid_judul', $judul)
        ->where('ppid_tipe', '0')
        ->first();
    expect($pertanyaan)->not->toBeNull();
})->group('browser', 'ppid', 'pertanyaan');

test('should validate judul field when adding pertanyaan', function () {
    $this->actingAs(User::role('super-admin')->first())
        ->visit('/ppid/pengaturan')
        ->click('button[onclick="showTambahPertanyaanModal(1)"]')
        ->pause(500)
        ->press('Simpan')
        ->pause(500);

    // Should still be on the same page
    $this->assertPageLoaded('/ppid/pengaturan');
})->group('browser', 'ppid', 'pertanyaan');

test('should delete pertanyaan successfully', function () {
    // Create a pertanyaan first
    $pertanyaan = PpidPertanyaan::create([
        'ppid_judul' => 'Pertanyaan test',
        'ppid_tipe' => '1',
        'ppid_status' => '1',
        'urutan' => 1,
    ]);

    $this->actingAs(User::role('super-admin')->first())
        ->visit('/ppid/pengaturan')
        ->click('button[onclick="deletePertanyaan(' . $pertanyaan->id . ')"]')
        ->pause(500) // Wait for confirm dialog
        // Note: confirm() in browser tests may need special handling
        ->visit('/ppid/pengaturan')
        ->assertDontSee('Pertanyaan test');

    // Verify deleted from database
    $deletedPertanyaan = PpidPertanyaan::find($pertanyaan->id);
    expect($deletedPertanyaan)->toBeNull();
})->group('browser', 'ppid', 'pertanyaan');

test('should toggle pertanyaan status successfully', function () {
    // Create pertanyaan with active status
    $pertanyaan = PpidPertanyaan::create([
        'ppid_judul' => 'Test Status Toggle',
        'ppid_tipe' => '1',
        'ppid_status' => '1',
        'urutan' => 1,
    ]);

    $this->actingAs(User::role('super-admin')->first())
        ->visit('/ppid/pengaturan')
        ->click('button[onclick="toggleStatus(' . $pertanyaan->id . ', \'1\')"]')
        ->pause(500)
        // Note: confirm() may need special handling
        ->visit('/ppid/pengaturan');

    // Verify status changed
    $pertanyaan->refresh();
    expect($pertanyaan->ppid_status)->toBe('0');
})->group('browser', 'ppid', 'pertanyaan');

test('should display pertanyaan list in correct order', function () {
    // Create multiple pertanyaan
    PpidPertanyaan::create([
        'ppid_judul' => 'Pertanyaan Pertama',
        'ppid_tipe' => '1',
        'ppid_status' => '1',
        'urutan' => 1,
    ]);

    PpidPertanyaan::create([
        'ppid_judul' => 'Pertanyaan Kedua',
        'ppid_tipe' => '1',
        'ppid_status' => '1',
        'urutan' => 2,
    ]);

    $this->actingAs(User::role('super-admin')->first())
        ->visit('/ppid/pengaturan')
        ->assertSeeInOrder(['Pertanyaan Pertama', 'Pertanyaan Kedua']);
})->group('browser', 'ppid', 'pertanyaan');

test('should show empty message when no pertanyaan exists', function () {
    $this->actingAs(User::role('super-admin')->first())
        ->visit('/ppid/pengaturan')
        ->assertSee('Belum ada pertanyaan');
})->group('browser', 'ppid', 'pertanyaan');

// ==================== AUTHORIZATION TESTS ====================

test('should redirect unauthorized users to login', function () {
    // Test without authentication
    auth()->logout();

    $this->visit('/ppid/pengaturan')
        ->assertSee('Login');
})->group('browser', 'ppid', 'auth');

test('should forbid access for non-authorized roles', function () {
    // Create user with kontributor-artikel role (should not have access)
    $user = User::role('kontributor-artikel')->first();
    if (!$user) {
        $this->markTestSkipped('No kontributor-artikel user found');
    }

    $this->actingAs($user)
        ->visit('/ppid/pengaturan')
        ->assertDontSee('Pengaturan PPID');
})->group('browser', 'ppid', 'auth');

test('should allow access for super-admin', function () {
    $this->actingAs(User::role('super-admin')->first())
        ->visit('/ppid/pengaturan')
        ->assertSee('Pengaturan PPID');
})->group('browser', 'ppid', 'auth');

test('should allow access for admin-kecamatan', function () {
    $user = User::role('admin-kecamatan')->first();
    if (!$user) {
        $this->markTestSkipped('No admin-kecamatan user found');
    }

    $this->actingAs($user)
        ->visit('/ppid/pengaturan')
        ->assertSee('Pengaturan PPID');
})->group('browser', 'ppid', 'auth');

test('should allow access for administrator-website', function () {
    $user = User::role('administrator-website')->first();
    if (!$user) {
        $this->markTestSkipped('No administrator-website user found');
    }

    $this->actingAs($user)
        ->visit('/ppid/pengaturan')
        ->assertSee('Pengaturan PPID');
})->group('browser', 'ppid', 'auth');

// ==================== INTEGRATION TESTS ====================

test('should save pengaturan and pertanyaan in one session', function () {
    $this->actingAs(User::role('super-admin')->first())
        // Update pengaturan
        ->visit('/ppid/pengaturan')
        ->type('ppid_judul', 'PPID Terpadu')
        ->type('ppid_batas_pengajuan', '7')
        ->press('Simpan')
        ->pause(1000)
        ->assertSee('Pengaturan PPID berhasil disimpan')

        // Add pertanyaan
        ->visit('/ppid/pengaturan')
        ->click('button[onclick="showTambahPertanyaanModal(1)"]')
        ->pause(500)
        ->type('ppid_judul', 'Dokumen apa saja yang tersedia?')
        ->select('ppid_status', '1')
        ->press('Simpan')
        ->pause(1000)
        ->visit('/ppid/pengaturan')
        ->assertSee('Dokumen apa saja yang tersedia?');

    // Verify both were saved
    $pengaturan = PpidPengaturan::first();
    expect($pengaturan->ppid_judul)->toBe('PPID Terpadu');
    expect($pengaturan->ppid_batas_pengajuan)->toBe(7);

    $pertanyaan = PpidPertanyaan::where('ppid_judul', 'Dokumen apa saja yang tersedia?')->first();
    expect($pertanyaan)->not->toBeNull();
})->group('browser', 'ppid', 'integration');

test('should handle concurrent pertanyaan creation', function () {
    $this->actingAs(User::role('super-admin')->first())
        ->visit('/ppid/pengaturan')
        ->click('button[onclick="showTambahPertanyaanModal(1)"]')
        ->pause(500)
        ->type('ppid_judul', 'Pertanyaan Konkuren 1')
        ->select('ppid_status', '1')
        ->press('Simpan')
        ->pause(1000);

    // Verify urutan is auto-incremented
    $pertanyaan1 = PpidPertanyaan::where('ppid_judul', 'Pertanyaan Konkuren 1')->first();
    expect($pertanyaan->urutan)->toBe(1);

    $this->actingAs(User::role('super-admin')->first())
        ->visit('/ppid/pengaturan')
        ->click('button[onclick="showTambahPertanyaanModal(1)"]')
        ->pause(500)
        ->type('ppid_judul', 'Pertanyaan Konkuren 2')
        ->select('ppid_status', '1')
        ->press('Simpan')
        ->pause(1000);

    $pertanyaan2 = PpidPertanyaan::where('ppid_judul', 'Pertanyaan Konkuren 2')->first();
    expect($pertanyaan2->urutan)->toBe(2);
})->group('browser', 'ppid', 'integration');
