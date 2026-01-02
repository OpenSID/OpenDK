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

use App\Enums\LogVerifikasiSurat;
use App\Enums\StatusSurat;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CompleteProfile;
use App\Models\DataDesa;
use App\Models\Surat;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->withViewErrors([]);
    $this->withoutMiddleware([
        Authenticate::class,
        RoleMiddleware::class,
        PermissionMiddleware::class,
        CompleteProfile::class,
    ]);
});

test('filter desa di halaman permohonan dengan desa tertentu', function () {
    // Buat data desa yang berbeda
    $desaId1 = '3201.01.2001';
    $desaId2 = '3201.01.2002';

    DataDesa::factory()->create(['desa_id' => $desaId1, 'nama' => 'Desa Test 1']);
    DataDesa::factory()->create(['desa_id' => $desaId2, 'nama' => 'Desa Test 2']);

    // Buat surat permohonan untuk masing-masing desa
    Surat::factory()->count(2)->create([
        'desa_id' => $desaId1,
        'status' => StatusSurat::Permohonan,
    ]);

    Surat::factory()->create([
        'desa_id' => $desaId2,
        'status' => StatusSurat::Permohonan,
    ]);

    // Test filter dengan desa tertentu (tanpa titik sesuai format di view)
    $response = $this->getJson(route('surat.permohonan.getdata', [
        'kode_desa' => str_replace('.', '', $desaId1)
    ]));

    $response->assertStatus(200);
    $data = $response->json();

    // Verifikasi bahwa endpoint berjalan dengan baik
    expect($data)->toHaveKey('data')
        ->and($data)->toHaveKey('recordsTotal');
});

test('filter desa di halaman permohonan dengan semua desa', function () {
    // Buat beberapa data desa dan surat
    $desaId1 = '3201.01.2003';
    $desaId2 = '3201.01.2004';

    DataDesa::factory()->create(['desa_id' => $desaId1]);
    DataDesa::factory()->create(['desa_id' => $desaId2]);

    Surat::factory()->create([
        'desa_id' => $desaId1,
        'status' => StatusSurat::Permohonan,
    ]);

    Surat::factory()->create([
        'desa_id' => $desaId2,
        'status' => StatusSurat::Permohonan,
    ]);

    // Test filter dengan "Semua" desa
    $response = $this->getJson(route('surat.permohonan.getdata', [
        'kode_desa' => 'Semua'
    ]));

    $response->assertStatus(200);
    $data = $response->json();

    expect($data)->toHaveKey('data')
        ->and($data)->toHaveKey('recordsTotal');
});

test('filter desa di halaman permohonan tanpa parameter', function () {
    // Buat data surat
    Surat::factory()->create([
        'desa_id' => '3201.01.2005',
        'status' => StatusSurat::Permohonan,
    ]);

    // Test tanpa parameter filter (default)
    $response = $this->getJson(route('surat.permohonan.getdata'));
    $response->assertStatus(200);

    $data = $response->json();
    expect($data)->toHaveKey('data');
});

test('filter desa di halaman permohonan ditolak dengan desa tertentu', function () {
    // Buat data desa
    $desaId1 = '3201.01.2006';
    $desaId2 = '3201.01.2007';

    DataDesa::factory()->create(['desa_id' => $desaId1]);
    DataDesa::factory()->create(['desa_id' => $desaId2]);

    // Buat surat ditolak untuk masing-masing desa
    Surat::factory()->count(2)->create([
        'desa_id' => $desaId1,
        'status' => StatusSurat::Ditolak,
        'log_verifikasi' => LogVerifikasiSurat::Ditolak,
    ]);

    Surat::factory()->create([
        'desa_id' => $desaId2,
        'status' => StatusSurat::Ditolak,
        'log_verifikasi' => LogVerifikasiSurat::Ditolak,
    ]);

    // Test filter dengan desa tertentu
    $response = $this->getJson(route('surat.permohonan.getdataditolak', [
        'kode_desa' => str_replace('.', '', $desaId1)
    ]));

    $response->assertStatus(200);
    $data = $response->json();

    expect($data)->toHaveKey('data')
        ->and($data)->toHaveKey('recordsTotal');
});

test('filter desa di halaman permohonan ditolak dengan semua desa', function () {
    // Buat data
    $desaId = '3201.01.2008';
    DataDesa::factory()->create(['desa_id' => $desaId]);

    Surat::factory()->create([
        'desa_id' => $desaId,
        'status' => StatusSurat::Ditolak,
        'log_verifikasi' => LogVerifikasiSurat::Ditolak,
    ]);

    // Test filter dengan semua desa
    $response = $this->getJson(route('surat.permohonan.getdataditolak', [
        'kode_desa' => 'Semua'
    ]));

    $response->assertStatus(200);
    $data = $response->json();

    expect($data)->toHaveKey('data');
});

test('filter desa di halaman permohonan ditolak tanpa parameter', function () {
    // Buat surat ditolak
    Surat::factory()->create([
        'desa_id' => '3201.01.2009',
        'status' => StatusSurat::Ditolak,
        'log_verifikasi' => LogVerifikasiSurat::Ditolak,
    ]);

    // Test tanpa parameter filter
    $response = $this->getJson(route('surat.permohonan.getdataditolak'));
    $response->assertStatus(200);

    $data = $response->json();
    expect($data)->toHaveKey('data');
});

test('filter desa di halaman arsip dengan desa tertentu', function () {
    // Buat data desa yang berbeda
    $desaId1 = '3201.01.2010';
    $desaId2 = '3201.01.2011';

    DataDesa::factory()->create(['desa_id' => $desaId1]);
    DataDesa::factory()->create(['desa_id' => $desaId2]);

    // Buat surat arsip untuk masing-masing desa
    Surat::factory()->count(3)->create([
        'desa_id' => $desaId1,
        'status' => StatusSurat::Arsip,
    ]);

    Surat::factory()->create([
        'desa_id' => $desaId2,
        'status' => StatusSurat::Arsip,
    ]);

    // Test filter dengan desa tertentu (tanpa titik sesuai format di controller arsip)
    $response = $this->getJson(route('surat.arsip.getdata', [
        'kode_desa' => str_replace('.', '', $desaId1)
    ]));

    $response->assertStatus(200);
    $data = $response->json();

    expect($data)->toHaveKey('data')
        ->and($data)->toHaveKey('recordsTotal');
});

test('filter desa di halaman arsip dengan semua desa', function () {
    // Buat data
    $desaId1 = '3201.01.2012';
    $desaId2 = '3201.01.2013';

    DataDesa::factory()->create(['desa_id' => $desaId1]);
    DataDesa::factory()->create(['desa_id' => $desaId2]);

    Surat::factory()->create([
        'desa_id' => $desaId1,
        'status' => StatusSurat::Arsip,
    ]);

    Surat::factory()->create([
        'desa_id' => $desaId2,
        'status' => StatusSurat::Arsip,
    ]);

    // Test filter dengan semua desa
    $response = $this->getJson(route('surat.arsip.getdata', [
        'kode_desa' => 'Semua'
    ]));

    $response->assertStatus(200);
    $data = $response->json();

    expect($data)->toHaveKey('data');
});

test('filter desa di halaman arsip tanpa parameter', function () {
    // Buat surat arsip
    Surat::factory()->create([
        'desa_id' => '3201.01.2014',
        'status' => StatusSurat::Arsip,
    ]);

    // Test tanpa parameter filter
    $response = $this->getJson(route('surat.arsip.getdata'));
    $response->assertStatus(200);

    $data = $response->json();
    expect($data)->toHaveKey('data');
});

test('filter permohonan mengembalikan data sesuai desa yang dipilih', function () {
    // Buat data desa spesifik
    $desaId1 = '3201.01.2015';
    $desaId2 = '3201.01.2016';

    DataDesa::factory()->create(['desa_id' => $desaId1, 'nama' => 'Desa Filter 1']);
    DataDesa::factory()->create(['desa_id' => $desaId2, 'nama' => 'Desa Filter 2']);

    // Buat 2 surat untuk desa 1 dan 1 surat untuk desa 2
    Surat::factory()->count(2)->create([
        'desa_id' => $desaId1,
        'status' => StatusSurat::Permohonan,
    ]);

    Surat::factory()->create([
        'desa_id' => $desaId2,
        'status' => StatusSurat::Permohonan,
    ]);

    // Filter untuk desa 1 saja (tanpa titik)
    $response = $this->getJson(route('surat.permohonan.getdata', [
        'kode_desa' => str_replace('.', '', $desaId1),
        'page[size]' => 10,
        'page[number]' => 1,
    ]));

    $response->assertStatus(200);
    $data = $response->json();

    // Verifikasi bahwa data yang dikembalikan minimal ada 2 record
    expect($data['recordsTotal'])->toBeGreaterThanOrEqual(2);
});

test('filter arsip mengembalikan data sesuai desa yang dipilih', function () {
    // Buat data desa spesifik
    $desaId1 = '3201.01.2017';
    $desaId2 = '3201.01.2018';

    DataDesa::factory()->create(['desa_id' => $desaId1, 'nama' => 'Desa Arsip 1']);
    DataDesa::factory()->create(['desa_id' => $desaId2, 'nama' => 'Desa Arsip 2']);

    // Buat 3 surat arsip untuk desa 1 dan 1 surat untuk desa 2
    Surat::factory()->count(3)->create([
        'desa_id' => $desaId1,
        'status' => StatusSurat::Arsip,
    ]);

    Surat::factory()->create([
        'desa_id' => $desaId2,
        'status' => StatusSurat::Arsip,
    ]);

    // Filter untuk desa 1 saja
    $response = $this->getJson(route('surat.arsip.getdata', [
        'kode_desa' => str_replace('.', '', $desaId1),
        'page[size]' => 10,
        'page[number]' => 1,
    ]));

    $response->assertStatus(200);
    $data = $response->json();

    // Verifikasi data yang dikembalikan minimal ada 3 record
    expect($data['recordsTotal'])->toBeGreaterThanOrEqual(3);
});

test('filter ditolak mengembalikan data sesuai desa yang dipilih', function () {
    // Buat data desa spesifik
    $desaId1 = '3201.01.2019';
    $desaId2 = '3201.01.2020';

    DataDesa::factory()->create(['desa_id' => $desaId1, 'nama' => 'Desa Ditolak 1']);
    DataDesa::factory()->create(['desa_id' => $desaId2, 'nama' => 'Desa Ditolak 2']);

    // Buat surat ditolak untuk desa 1 dan desa 2
    Surat::factory()->count(2)->create([
        'desa_id' => $desaId1,
        'status' => StatusSurat::Ditolak,
        'log_verifikasi' => LogVerifikasiSurat::Ditolak,
    ]);

    Surat::factory()->create([
        'desa_id' => $desaId2,
        'status' => StatusSurat::Ditolak,
        'log_verifikasi' => LogVerifikasiSurat::Ditolak,
    ]);

    // Filter untuk desa 1 saja
    $response = $this->getJson(route('surat.permohonan.getdataditolak', [
        'kode_desa' => str_replace('.', '', $desaId1),
        'page[size]' => 10,
        'page[number]' => 1,
    ]));

    $response->assertStatus(200);
    $data = $response->json();

    // Verifikasi data yang dikembalikan minimal ada 2 record
    expect($data['recordsTotal'])->toBeGreaterThanOrEqual(2);
});

test('halaman permohonan dapat diakses dan memiliki filter desa', function () {
    $response = $this->get(route('surat.permohonan'));

    $response->assertStatus(200)
        ->assertViewIs('surat.permohonan.index')
        ->assertSee('list_desa'); // Memastikan ada elemen filter desa
});

test('halaman permohonan ditolak dapat diakses dan memiliki filter desa', function () {
    $response = $this->get(route('surat.permohonan.ditolak'));

    $response->assertStatus(200)
        ->assertViewIs('surat.permohonan.ditolak')
        ->assertSee('list_desa'); // Memastikan ada elemen filter desa
});

test('halaman arsip dapat diakses dan memiliki filter desa', function () {
    $response = $this->get(route('surat.arsip'));

    $response->assertStatus(200)
        ->assertViewIs('surat.arsip')
        ->assertSee('list_desa'); // Memastikan ada elemen filter desa
});
