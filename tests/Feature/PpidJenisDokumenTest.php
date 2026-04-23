<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Profil;
use App\Models\PpidJenisDokumen;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CompleteProfile;
use App\Http\Middleware\GlobalShareMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Database\Eloquent\SoftDeletingScope;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutMiddleware([
        Authenticate::class,
        RoleMiddleware::class,
        PermissionMiddleware::class,
        CompleteProfile::class,
        GlobalShareMiddleware::class,
    ]);

    // Sesuaikan dengan kolom tabel profil Anda
    $profil = Profil::create([
        'provinsi_id'     => '73',
        'nama_provinsi'   => 'SULAWESI SELATAN',
        'kabupaten_id'    => '73.08',
        'nama_kabupaten'  => 'BONE',
        'kecamatan_id'    => '73.08.02',
        'nama_kecamatan'  => 'Kahu',
        'alamat'          => 'Jl. Test No. 1',
        'kode_pos'        => '92767',
        'telepon'         => '082290050133',
        'email'           => 'admin@mail.com',
        'tahun_pembentukan' => '2000',
        'dasar_pembentukan' => 'xxx',
        // Tambahkan bts_wil jika memang kolom ini ada di migrasi Anda
        'bts_wil_utara'   => '0',
        'bts_wil_timur'   => '0',
        'bts_wil_selatan' => '0',
        'bts_wil_barat'   => '0',
    ]);

    view()->share([
        'profil'           => $profil,
        'browser_title'    => 'Testing OpenDK',
        'page_title'       => 'PPID',
        'page_description' => 'Daftar Jenis Dokumen',
        // OpenDK sering butuh icons di view ppid
        'icons'            => ['fa fa-file', 'fa fa-book']
    ]);

    $this->user = User::factory()->create();
    $this->tableName = (new PpidJenisDokumen())->getTable();
});

/* ===================================================================
   INDEX & VIEW
   =================================================================== */

test('halaman indeks jenis dokumen ppid dapat diakses', function () {
    $this->actingAs($this->user)
        ->get(route('ppid.jenis-dokumen.index'))
        ->assertStatus(200)
        ->assertViewIs('ppid.jenis_dokumen.index')
        ->assertViewHas('page_description', 'Daftar Jenis Dokumen');
});

test('halaman tambah jenis dokumen dapat diakses', function () {
    $this->actingAs($this->user)
        ->get(route('ppid.jenis-dokumen.create'))
        ->assertStatus(200)
        ->assertViewIs('ppid.jenis_dokumen.create')
        ->assertViewHas('page_description', 'Tambah Jenis Dokumen');
});

test('halaman edit jenis dokumen dapat diakses', function () {
    $doc = PpidJenisDokumen::create([
        'nama'   => 'Test Document',
        'slug'   => 'test-document',
        'status' => 1,
        'urut'   => 1
    ]);

    $this->actingAs($this->user)
        ->get(route('ppid.jenis-dokumen.edit', $doc->id))
        ->assertStatus(200)
        ->assertViewIs('ppid.jenis_dokumen.edit')
        ->assertViewHas('jenis')
        ->assertViewHas('page_description', 'Edit Jenis Dokumen');
});

/* ===================================================================
   CREATE - Validasi & Simpan Data
   =================================================================== */

test('admin dapat menambah jenis dokumen baru dengan data lengkap', function () {
    $data = [
        'nama'      => 'Dokumen Transparansi Anggaran',
        'deskripsi' => 'Rincian anggaran tahunan',
        'kode'      => '#3498db',
        'icon'      => 'fa fa-money',
    ];

    $this->actingAs($this->user)
        ->post(route('ppid.jenis-dokumen.store'), $data)
        ->assertRedirect(route('ppid.jenis-dokumen.index'))
        ->assertSessionHas('success', 'Jenis Dokumen berhasil ditambahkan');

    $this->assertDatabaseHas($this->tableName, [
        'nama'      => 'Dokumen Transparansi Anggaran',
        'slug'      => 'dokumen-transparansi-anggaran',
        'deskripsi' => 'Rincian anggaran tahunan',
        'kode'      => '#3498db',
        'icon'      => 'fa fa-money',
        'status'    => '1',
    ]);
});

test('sistem otomatis mengisi nilai default saat data tidak lengkap', function () {
    $data = [
        'nama' => 'Dokumen Minimal'
    ];

    $this->actingAs($this->user)
        ->post(route('ppid.jenis-dokumen.store'), $data)
        ->assertRedirect(route('ppid.jenis-dokumen.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas($this->tableName, [
        'nama'   => 'Dokumen Minimal',
        'slug'   => 'dokumen-minimal',
        'kode'   => '#ffffff',
        'icon'   => 'fa fa-file',
        'status' => '1',
    ]);
});

test('nama jenis dokumen wajib diisi', function () {
    $this->actingAs($this->user)
        ->post(route('ppid.jenis-dokumen.store'), ['nama' => ''])
        ->assertSessionHasErrors('nama');
});

test('nama tidak boleh lebih dari 150 karakter', function () {
    $this->actingAs($this->user)
        ->post(route('ppid.jenis-dokumen.store'), [
            'nama' => str_repeat('a', 151)
        ])
        ->assertSessionHasErrors('nama');
});

test('sistem mencegah duplikasi slug saat create', function () {
    PpidJenisDokumen::create([
        'nama'   => 'Existing Document',
        'slug'   => 'existing-document',
        'status' => 1,
        'urut'   => 1
    ]);

    $this->actingAs($this->user)
        ->post(route('ppid.jenis-dokumen.store'), [
            'nama' => 'Existing Document'
        ])
        ->assertSessionHasErrors('slug');
});

test('urutan otomatis diset ke posisi terakhir saat create', function () {
    PpidJenisDokumen::create(['nama' => 'First', 'slug' => 'first', 'status' => 1, 'urut' => 5]);
    PpidJenisDokumen::create(['nama' => 'Second', 'slug' => 'second', 'status' => 1, 'urut' => 10]);

    $this->actingAs($this->user)
        ->post(route('ppid.jenis-dokumen.store'), ['nama' => 'New Document'])
        ->assertSessionHas('success');

    $this->assertDatabaseHas($this->tableName, [
        'nama' => 'New Document',
        'urut' => 11
    ]);
});

/* ===================================================================
   UPDATE - Edit & Validasi
   =================================================================== */

test('admin dapat mengedit jenis dokumen', function () {
    $doc = PpidJenisDokumen::create([
        'nama'      => 'Original Name',
        'slug'      => 'original-name',
        'deskripsi' => 'Original Description',
        'kode'      => '#000000',
        'icon'      => 'fa fa-file',
        'status'    => 1,
        'urut'      => 1
    ]);

    $this->actingAs($this->user)
        ->put(route('ppid.jenis-dokumen.update', $doc->id), [
            'nama'      => 'Updated Name',
            'deskripsi' => 'New description',
            'kode'      => '#ff5733',
            'icon'      => 'fa fa-folder',
            'status'    => 1
        ])
        ->assertRedirect(route('ppid.jenis-dokumen.index'))
        ->assertSessionHas('success', 'Jenis Dokumen berhasil diubah');

    $this->assertDatabaseHas($this->tableName, [
        'id'        => $doc->id,
        'nama'      => 'Updated Name',
        'slug'      => 'updated-name',
        'deskripsi' => 'New description',
        'kode'      => '#ff5733',
        'icon'      => 'fa fa-folder'
    ]);
});

test('sistem mencegah duplikasi slug saat update', function () {
    $doc1 = PpidJenisDokumen::create(['nama' => 'First', 'slug' => 'first', 'status' => 1, 'urut' => 1]);
    $doc2 = PpidJenisDokumen::create(['nama' => 'Second', 'slug' => 'second', 'status' => 1, 'urut' => 2]);

    $this->actingAs($this->user)
        ->put(route('ppid.jenis-dokumen.update', $doc2->id), [
            'nama' => 'First'
        ])
        ->assertSessionHasErrors('slug');
});

test('update mengizinkan slug yang sama dengan data sendiri', function () {
    $doc = PpidJenisDokumen::create([
        'nama'   => 'Original',
        'slug'   => 'original',
        'status' => 1,
        'urut'   => 1
    ]);

    $this->actingAs($this->user)
        ->put(route('ppid.jenis-dokumen.update', $doc->id), [
            'nama'      => 'Original',
            'deskripsi' => 'Updated description only'
        ])
        ->assertRedirect(route('ppid.jenis-dokumen.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas($this->tableName, [
        'id'   => $doc->id,
        'nama' => 'Original',
        'slug' => 'original'
    ]);
});

/* ===================================================================
   DELETE - Hapus Data
   =================================================================== */

test('admin dapat menghapus jenis dokumen yang tidak diproteksi dengan soft delete', function () {
    $doc = PpidJenisDokumen::create([
        'nama'   => 'Deleteable Document',
        'slug'   => 'deleteable-document',
        'status' => 1,
        'urut'   => 1
    ]);

    $this->actingAs($this->user)
        ->delete(route('ppid.jenis-dokumen.destroy', $doc->id))
        ->assertRedirect()
        ->assertSessionHas('success', 'Jenis Dokumen berhasil dihapus');

    // Soft delete: data masih ada tapi deleted_at terisi
    $this->assertSoftDeleted($this->tableName, ['id' => $doc->id]);
});

test('sistem mencegah penghapusan jenis dokumen yang diproteksi', function () {
    $protectedSlugs = ['secara-berkala', 'serta-merta', 'tersedia-setiap-saat'];

    foreach ($protectedSlugs as $slug) {
        $doc = PpidJenisDokumen::create([
            'nama'   => ucwords(str_replace('-', ' ', $slug)),
            'slug'   => $slug,
            'status' => 1,
            'urut'   => 1
        ]);

        $this->actingAs($this->user)
            ->delete(route('ppid.jenis-dokumen.destroy', $doc->id))
            ->assertSessionHas('error', 'Jenis Dokumen tidak boleh dihapus!');

        $this->assertDatabaseHas($this->tableName, ['id' => $doc->id]);
    }
});

/* ===================================================================
   BULK DELETE
   =================================================================== */

test('admin dapat menghapus beberapa jenis dokumen sekaligus dengan soft delete', function () {
    $doc1 = PpidJenisDokumen::create(['nama' => 'Delete 1', 'slug' => 'delete-1', 'status' => 1, 'urut' => 1]);
    $doc2 = PpidJenisDokumen::create(['nama' => 'Delete 2', 'slug' => 'delete-2', 'status' => 1, 'urut' => 2]);
    $doc3 = PpidJenisDokumen::create(['nama' => 'Delete 3', 'slug' => 'delete-3', 'status' => 1, 'urut' => 3]);

    $this->actingAs($this->user)
        ->postJson(route('ppid.jenis-dokumen.bulk-delete'), [
            'ids' => [$doc1->id, $doc2->id, $doc3->id]
        ])
        ->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Berhasil menghapus 3 data'
        ]);

    // Soft delete: data masih ada tapi deleted_at terisi
    $this->assertSoftDeleted($this->tableName, ['id' => $doc1->id]);
    $this->assertSoftDeleted($this->tableName, ['id' => $doc2->id]);
    $this->assertSoftDeleted($this->tableName, ['id' => $doc3->id]);
});

test('bulk delete menolak jika ada data terproteksi dalam daftar', function () {
    $regular = PpidJenisDokumen::create(['nama' => 'Regular', 'slug' => 'regular', 'status' => 1, 'urut' => 1]);
    $protected = PpidJenisDokumen::create(['nama' => 'Serta Merta', 'slug' => 'serta-merta', 'status' => 1, 'urut' => 2]);

    $this->actingAs($this->user)
        ->postJson(route('ppid.jenis-dokumen.bulk-delete'), [
            'ids' => [$regular->id, $protected->id]
        ])
        ->assertStatus(422)
        ->assertJson([
            'success' => false,
            'message' => 'Beberapa data tidak dapat dihapus karena dilindungi sistem!'
        ]);

    $this->assertDatabaseHas($this->tableName, ['id' => $regular->id]);
    $this->assertDatabaseHas($this->tableName, ['id' => $protected->id]);
});

test('bulk delete validasi array tidak boleh kosong', function () {
    $this->actingAs($this->user)
        ->postJson(route('ppid.jenis-dokumen.bulk-delete'), ['ids' => []])
        ->assertStatus(422)
        ->assertJsonValidationErrors('ids');
});

test('bulk delete validasi id harus exists di database', function () {
    $this->actingAs($this->user)
        ->postJson(route('ppid.jenis-dokumen.bulk-delete'), [
            'ids' => [99999]
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors('ids.0');
});

/* ===================================================================
   STATUS TOGGLE
   =================================================================== */

test('admin dapat mengubah status dari aktif ke nonaktif', function () {
    $doc = PpidJenisDokumen::create([
        'nama'   => 'Active Document',
        'slug'   => 'active-document',
        'status' => '1',
        'urut'   => 1
    ]);

    $response = $this->actingAs($this->user)
        ->put(route('ppid.jenis-dokumen.status', $doc->id));

    $response->assertStatus(302)
        ->assertRedirect(route('ppid.jenis-dokumen.index'))
        ->assertSessionHas('success', 'Status Jenis Dokumen berhasil diubah!');

    $doc->refresh();

    $this->assertDatabaseHas($this->tableName, [
        'id'     => $doc->id,
        'status' => '0'
    ]);

    // Atau menggunakan expect untuk lebih presisi
    expect($doc->status)->toEqual('0');
});

test('admin dapat mengubah status dari nonaktif ke aktif', function () {
    $doc = PpidJenisDokumen::create([
        'nama'   => 'Inactive Document',
        'slug'   => 'inactive-document',
        'status' => '0',
        'urut'   => 1
    ]);

    $this->actingAs($this->user)
        ->put(route('ppid.jenis-dokumen.status', $doc->id))
        ->assertRedirect(route('ppid.jenis-dokumen.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas($this->tableName, [
        'id'     => $doc->id,
        'status' => '1'
    ]);
});

/* ===================================================================
   ORDERING - Drag & Drop
   =================================================================== */

test('update order validasi array tidak boleh kosong', function () {
    $this->actingAs($this->user)
        ->postJson(route('ppid.jenis-dokumen.update-order'), ['order' => []])
        ->assertStatus(422)
        ->assertJsonValidationErrors('order');
});

test('update order validasi id harus exists', function () {
    $this->actingAs($this->user)
        ->postJson(route('ppid.jenis-dokumen.update-order'), [
            'order' => [
                ['id' => 99999, 'position' => 1]
            ]
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors('order.0.id');
});

test('update order validasi position harus integer minimal 1', function () {
    $doc = PpidJenisDokumen::create(['nama' => 'Test', 'slug' => 'test', 'status' => 1, 'urut' => 1]);

    $this->actingAs($this->user)
        ->postJson(route('ppid.jenis-dokumen.update-order'), [
            'order' => [
                ['id' => $doc->id, 'position' => 0]
            ]
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors('order.0.position');
});

/* ===================================================================
   DATATABLES
   =================================================================== */

test('dapat mengambil data jenis dokumen melalui ajax datatables', function () {
    PpidJenisDokumen::create(['nama' => 'Document A', 'slug' => 'document-a', 'status' => '1',  'urut' => 1]);
    PpidJenisDokumen::create(['nama' => 'Document B', 'slug' => 'document-b', 'status' => '0', 'urut' => 2]);

    $response = $this->actingAs($this->user)
        ->get(route('ppid.jenis-dokumen.getdata'));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data',
            'recordsTotal',
            'recordsFiltered',
            'draw'
        ]);
});

test('datatables dapat filter berdasarkan jenis_dokumen_id', function () {
    $doc1 = PpidJenisDokumen::create(['nama' => 'First', 'slug' => 'first', 'status' => 1, 'urut' => 1]);
    $doc2 = PpidJenisDokumen::create(['nama' => 'Second', 'slug' => 'second', 'status' => 1, 'urut' => 2]);

    $response = $this->actingAs($this->user)
        ->get(route('ppid.jenis-dokumen.getdata', ['jenis_dokumen_id' => $doc1->id]));

    $response->assertStatus(200);

    $data = $response->json();
    expect($data['recordsFiltered'])->toBe(1);
});

test('datatables dapat filter berdasarkan status', function () {
    PpidJenisDokumen::create(['nama' => 'Active', 'slug' => 'active', 'status' => '1', 'urut' => 1]);
    PpidJenisDokumen::create(['nama' => 'Inactive', 'slug' => 'inactive', 'status' => '0', 'urut' => 2]);

    $response = $this->actingAs($this->user)
        ->get(route('ppid.jenis-dokumen.getdata', ['status' => 1]));

    $response->assertStatus(200);

    $data = $response->json();
    expect($data['recordsFiltered'])->toBe(1);
});

test('datatables default sorting berdasarkan urut ascending', function () {
    $doc1 = PpidJenisDokumen::create(['nama' => 'Third', 'slug' => 'third', 'status' => 1, 'urut' => 3]);
    $doc2 = PpidJenisDokumen::create(['nama' => 'First', 'slug' => 'first', 'status' => 1, 'urut' => 1]);
    $doc3 = PpidJenisDokumen::create(['nama' => 'Second', 'slug' => 'second', 'status' => 1, 'urut' => 2]);

    $response = $this->actingAs($this->user)
        ->get(route('ppid.jenis-dokumen.getdata'));

    $response->assertStatus(200);

    $data = $response->json('data');
    expect($data[0]['nama'])->toBe('First');
    expect($data[1]['nama'])->toBe('Second');
    expect($data[2]['nama'])->toBe('Third');
});

/* ===================================================================
   STATUS - Enum & Label
   =================================================================== */

test('status menggunakan enum dengan nilai 0 dan 1', function () {
    $inactive = PpidJenisDokumen::create(['nama' => 'Inactive', 'slug' => 'inactive', 'status' => '0', 'urut' => 1]);
    $active = PpidJenisDokumen::create(['nama' => 'Active', 'slug' => 'active', 'status' => '1', 'urut' => 2]);

    expect($inactive->status)->toEqual(0);
    expect($active->status)->toEqual(1);
});

test('status menampilkan label html di datatables', function () {
    $doc = PpidJenisDokumen::create(['nama' => 'Test', 'slug' => 'test', 'status' => 1, 'urut' => 1]);

    $response = $this->actingAs($this->user)
        ->get(route('ppid.jenis-dokumen.getdata'));

    $response->assertStatus(200);

    $data = $response->json('data');
    // Memastikan status di-render sebagai HTML label (bukan plain text)
    expect($data[0]['status'])->toContain('label');
});

test('datatables dapat melakukan global search berdasarkan nama', function () {

    PpidJenisDokumen::create([
        'nama' => 'Dokumen Keuangan',
        'slug' => 'dokumen-keuangan',
        'status' => 1,
        'urut' => 1
    ]);

    PpidJenisDokumen::create([
        'nama' => 'Dokumen SDM',
        'slug' => 'dokumen-sdm',
        'status' => 1,
        'urut' => 2
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('ppid.jenis-dokumen.getdata', [
            'search' => [
                'value' => 'Keuangan'
            ]
        ]));

    $response->assertStatus(200);

    $data = $response->json();

    expect($data['recordsFiltered'])->toBe(1);
    expect($data['data'][0]['nama'])->toBe('Dokumen Keuangan');
});
