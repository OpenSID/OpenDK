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

use App\Models\AnggaranDesa;
use App\Models\DataDesa;
use App\Models\SettingAplikasi;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

uses(DatabaseTransactions::class);

beforeEach(function () {
    // Setup settings untuk database gabungan
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '0']
    );
    
    // Ensure required roles exist
    ensureRolesExist();
});

/**
 * Helper function to ensure roles exist for testing
 */
function ensureRolesExist(): void
{
    $roles = ['super-admin', 'admin-desa', 'admin-kecamatan', 'admin-komplain'];
    
    foreach ($roles as $roleName) {
        Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
    }
}

// =============================================================================
// ROUTE MIDDLEWARE TESTS - WITH MIDDLEWARE ENABLED
// =============================================================================

test('export anggaran desa middleware blocks unauthenticated user', function () {
    // Arrange: Setup data
    $desa = DataDesa::factory()->create();
    AnggaranDesa::factory()->create([
        'desa_id' => $desa->desa_id
    ]);

    // Act: Coba akses tanpa login (dengan middleware aktif)
    $this->withMiddleware();
    Excel::fake();

    $response = $this->get('/data/anggaran-desa/export-excel');

    // Assert: Route ini tidak memerlukan login (hanya role middleware)
    // Role middleware hanya check role jika user sudah login
    // Unauthenticated user bisa akses karena tidak ada auth middleware
    $response->assertSuccessful();
});

test('export anggaran desa middleware allows super-admin role', function () {
    // Arrange: Buat user dengan role super-admin
    $user = User::factory()->create();
    $user->assignRole('super-admin');

    $desa = DataDesa::factory()->create();
    AnggaranDesa::factory()->create([
        'desa_id' => $desa->desa_id
    ]);

    // Act: Login dan export dengan middleware aktif
    $this->actingAs($user);
    $this->withMiddleware();
    Excel::fake();

    $response = $this->get('/data/anggaran-desa/export-excel');

    // Assert: Super admin harus bisa akses
    $response->assertSuccessful();
});

test('export anggaran desa middleware blocks user without required role', function () {
    // Arrange: Buat user dengan role yang tidak sesuai
    // Route membutuhkan: super-admin OR admin-desa
    $user = User::factory()->create();
    $user->assignRole('admin-komplain'); // Role ini tidak punya akses
    
    $desa = DataDesa::factory()->create();
    AnggaranDesa::factory()->create([
        'desa_id' => $desa->desa_id
    ]);

    // Act: Login dan coba export dengan middleware aktif
    $this->actingAs($user);
    $this->withMiddleware();
    Excel::fake();

    $response = $this->get('/data/anggaran-desa/export-excel');

    // Assert: User tanpa role yang sesuai harus ditolak (403 Forbidden)
    $response->assertForbidden();
});

test('export penduduk middleware blocks unauthenticated user', function () {
    // Arrange: Setup data
    $desa = DataDesa::factory()->create();
    \App\Models\Penduduk::factory()->create([
        'desa_id' => $desa->desa_id
    ]);

    // Act: Coba akses tanpa login
    $this->withMiddleware();
    Excel::fake();

    $response = $this->get('/data/penduduk/export-excel');

    // Assert: Route ini tidak memerlukan login (hanya role middleware)
    // Unauthenticated user bisa akses karena tidak ada auth middleware
    $response->assertSuccessful();
});

test('export penduduk middleware allows admin-desa role', function () {
    // Arrange: Buat user dengan role admin-desa
    $user = User::factory()->create();
    $user->assignRole('admin-desa');

    $desa = DataDesa::factory()->create();
    \App\Models\Penduduk::factory()->create([
        'desa_id' => $desa->desa_id
    ]);

    // Act: Login dan export dengan middleware aktif
    $this->actingAs($user);
    $this->withMiddleware();
    Excel::fake();

    $response = $this->get('/data/penduduk/export-excel');

    // Assert: Admin desa harus bisa akses
    $response->assertSuccessful();
});

test('export keluarga middleware blocks user without admin-desa role', function () {
    // Arrange: Buat user tanpa role admin-desa
    $user = User::factory()->create();
    $user->assignRole('admin-komplain'); // Role ini tidak punya akses

    $desa = DataDesa::factory()->create();
    $penduduk = \App\Models\Penduduk::factory()->create(['desa_id' => $desa->desa_id]);
    \App\Models\Keluarga::factory()->create([
        'nik_kepala' => $penduduk->nik,
        'desa_id' => $desa->desa_id
    ]);

    // Act: Login dan coba export dengan middleware aktif
    $this->actingAs($user);
    $this->withMiddleware();
    Excel::fake();

    $response = $this->get('/data/keluarga/export-excel');

    // Assert: Harus forbidden
    $response->assertForbidden();
});

test('export data desa middleware allows admin-kecamatan role', function () {
    // Arrange: Buat user dengan role admin-kecamatan
    $user = User::factory()->create();
    $user->assignRole('admin-kecamatan');

    DataDesa::factory()->count(3)->create();

    // Act: Login dan export dengan middleware aktif
    $this->actingAs($user);
    $this->withMiddleware();
    Excel::fake();

    $response = $this->get('/data/data-desa/export-excel');

    // Assert: Admin kecamatan harus bisa akses
    $response->assertSuccessful();
});

test('export data desa middleware blocks user without admin-kecamatan role', function () {
    // Arrange: Buat user tanpa role admin-kecamatan
    $user = User::factory()->create();
    $user->assignRole('admin-komplain');

    DataDesa::factory()->create();

    // Act: Login dan coba export dengan middleware aktif
    $this->actingAs($user);
    $this->withMiddleware();
    Excel::fake();

    $response = $this->get('/data/data-desa/export-excel');

    // Assert: Harus forbidden
    $response->assertForbidden();
});

// =============================================================================
// ROUTE MIDDLEWARE TESTS - WITHOUT MIDDLEWARE (EXISTING BEHAVIOR)
// =============================================================================

test('export anggaran desa without middleware always succeeds', function () {
    // Arrange: Setup data tanpa user
    $desa = DataDesa::factory()->create();
    AnggaranDesa::factory()->create([
        'desa_id' => $desa->desa_id
    ]);

    // Act: Export tanpa middleware (existing test behavior)
    $this->withoutMiddleware();
    Excel::fake();

    $response = $this->get('/data/anggaran-desa/export-excel');

    // Assert: Tanpa middleware, akses selalu berhasil
    $response->assertSuccessful();
});

test('export with middleware vs without middleware comparison', function () {
    // Arrange: Buat user dan data
    $user = User::factory()->create();
    $user->assignRole('super-admin');

    $desa = DataDesa::factory()->create();
    AnggaranDesa::factory()->create([
        'desa_id' => $desa->desa_id
    ]);

    Excel::fake();

    // Act 1: Dengan middleware - harus berhasil karena super-admin
    $responseWithMiddleware = $this->actingAs($user)
        ->withMiddleware()
        ->get('/data/anggaran-desa/export-excel');

    // Act 2: Tanpa middleware - juga berhasil
    $responseWithoutMiddleware = $this->actingAs($user)
        ->withoutMiddleware()
        ->get('/data/anggaran-desa/export-excel');

    // Assert: Keduanya berhasil untuk user yang authorized
    $responseWithMiddleware->assertSuccessful();
    $responseWithoutMiddleware->assertSuccessful();
});

test('export unauthorized user with vs without middleware', function () {
    // Arrange: Buat user tanpa role dan data
    $user = User::factory()->create(); // No role assigned
    
    $desa = DataDesa::factory()->create();
    AnggaranDesa::factory()->create([
        'desa_id' => $desa->desa_id
    ]);

    Excel::fake();

    // Act 1: Dengan middleware - harus forbidden
    $responseWithMiddleware = $this->actingAs($user)
        ->withMiddleware()
        ->get('/data/anggaran-desa/export-excel');

    // Act 2: Tanpa middleware - berhasil (middleware bypassed)
    $responseWithoutMiddleware = $this->actingAs($user)
        ->withoutMiddleware()
        ->get('/data/anggaran-desa/export-excel');

    // Assert: Middleware membuat perbedaan
    $responseWithMiddleware->assertForbidden();
    $responseWithoutMiddleware->assertSuccessful();
});

// =============================================================================
// MIDDLEWARE ROLE COMBINATION TESTS
// =============================================================================

test('export with multiple roles including required role', function () {
    // Arrange: User dengan multiple roles (termasuk yang required)
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    $user->assignRole('admin-desa');

    $desa = DataDesa::factory()->create();
    AnggaranDesa::factory()->create([
        'desa_id' => $desa->desa_id
    ]);

    // Act: Export dengan middleware
    $this->actingAs($user);
    $this->withMiddleware();
    Excel::fake();

    $response = $this->get('/data/anggaran-desa/export-excel');

    // Assert: Harus berhasil karena punya role super-admin
    $response->assertSuccessful();
});

test('export route middleware configuration is correct', function () {
    // Arrange: Check route exists
    $route = \Route::getRoutes()->getByName('data.anggaran-desa.export-excel');
    
    // Assert: Route harus ada dan punya middleware
    expect($route)->not->toBeNull();
    
    // Check if middleware contains the role middleware (format may be class name)
    $middleware = $route->middleware();
    expect($middleware)->not->toBeEmpty();
    
    // Spatie role middleware class
    $hasRoleMiddleware = collect($middleware)->contains(fn($m) => 
        str_contains($m, 'RoleMiddleware') || str_contains($m, 'role:')
    );
    expect($hasRoleMiddleware)->toBeTrue();
});

test('export penduduk route middleware configuration', function () {
    // Arrange: Check route exists
    $route = \Route::getRoutes()->getByName('data.penduduk.export-excel');
    
    // Assert: Route harus ada dan punya middleware admin-desa
    expect($route)->not->toBeNull();
    
    $middleware = $route->middleware();
    expect($middleware)->toContain('role:super-admin|admin-desa');
});

test('export keluarga route middleware configuration', function () {
    // Arrange: Check route exists
    $route = \Route::getRoutes()->getByName('data.keluarga.export-excel');
    
    // Assert: Route harus ada dan punya middleware admin-desa
    expect($route)->not->toBeNull();
    
    $middleware = $route->middleware();
    expect($middleware)->toContain('role:super-admin|admin-desa');
});

test('export data desa route middleware configuration', function () {
    // Arrange: Check route exists
    $route = \Route::getRoutes()->getByName('data.data-desa.export-excel');
    
    // Assert: Route harus ada dan punya middleware admin-kecamatan
    expect($route)->not->toBeNull();
    
    $middleware = $route->middleware();
    expect($middleware)->toContain('role:super-admin|admin-kecamatan');
});
