# Contoh Testing untuk OpenDK

Dokumentasi ini berisi contoh-contoh praktis testing untuk berbagai fitur di aplikasi OpenDK menggunakan Pest 4.

## 📋 Daftar Isi

- [Setup Awal](#setup-awal)
- [Testing CRUD Artikel](#testing-crud-artikel)
- [Testing API](#testing-api)
- [Testing Export Data](#testing-export-data)
- [Testing Authentication & Authorization](#testing-authentication--authorization)
- [Testing File Upload](#testing-file-upload)
- [Testing Data Desa](#testing-data-desa)
- [Testing Dashboard](#testing-dashboard)
- [Testing Browser/E2E](#testing-browsere2e)
- [Testing Services](#testing-services)
- [Testing dengan Dataset](#testing-dengan-dataset)

---

## Setup Awal

### File: `tests/Pest.php`

```php
<?php

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\DatabaseTransactions::class)
    ->in('Feature');

pest()->extend(Tests\TestCase::class)
    ->in('Unit');

pest()->extend(Tests\TestCase::class)
    ->in('Browser')
    ->beforeEach(function () {
        config(['app.url' => env('APP_URL', 'http://opendk.test')]);
    });

// Custom expectations untuk OpenDK
expect()->extend('toBeValidSlug', function () {
    return $this->toMatch('/^[a-z0-9]+(?:-[a-z0-9]+)*$/');
});

expect()->extend('toBeValidDesaCode', function () {
    return $this->toMatch('/^\d{10}$/');
});

// Helper functions
function createAdmin(): \App\Models\User
{
    return \App\Models\User::factory()->create(['role' => 'admin']);
}

function createOperator(): \App\Models\User
{
    return \App\Models\User::factory()->create(['role' => 'operator']);
}
```

---

## Testing CRUD Artikel

### File: `tests/Feature/ArtikelTest.php`

```php
<?php

use App\Models\User;
use App\Models\Artikel;
use App\Models\ArtikelKategori;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

describe('Artikel Management', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->kategori = ArtikelKategori::factory()->create();
        Storage::fake('public');
    });

    test('admin dapat melihat halaman index artikel', function () {
        Artikel::factory()->count(5)->create();

        $response = $this->actingAs($this->user)
            ->get('/admin/artikel');

        $response->assertStatus(200)
            ->assertViewIs('artikel.index')
            ->assertViewHas('artikel');
    })->group('artikel', 'admin');

    test('dapat membuat artikel baru', function () {
        $gambar = UploadedFile::fake()->image('artikel.jpg');

        $response = $this->actingAs($this->user)
            ->post('/admin/artikel', [
                'judul' => 'Artikel Test',
                'id_kategori' => $this->kategori->id,
                'isi' => 'Konten artikel test',
                'gambar' => $gambar,
                'status' => 'published'
            ]);

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('artikel', [
            'judul' => 'Artikel Test',
            'id_kategori' => $this->kategori->id
        ]);

        Storage::disk('public')->assertExists('artikel/' . $gambar->hashName());
    })->group('artikel', 'create');

    test('slug artikel di-generate otomatis', function () {
        $response = $this->actingAs($this->user)
            ->post('/admin/artikel', [
                'judul' => 'Artikel Test Slug',
                'id_kategori' => $this->kategori->id,
                'isi' => 'Konten',
                'status' => 'published'
            ]);

        $artikel = Artikel::where('judul', 'Artikel Test Slug')->first();

        expect($artikel->slug)
            ->toBeValidSlug()
            ->toBe('artikel-test-slug');
    })->group('artikel', 'slug');

    test('dapat update artikel', function () {
        $artikel = Artikel::factory()->create([
            'judul' => 'Artikel Lama'
        ]);

        $response = $this->actingAs($this->user)
            ->put("/admin/artikel/{$artikel->id}", [
                'judul' => 'Artikel Baru',
                'id_kategori' => $this->kategori->id,
                'isi' => 'Konten update',
                'status' => 'published'
            ]);

        $response->assertRedirect()
            ->assertSessionHas('success');

        expect($artikel->fresh()->judul)->toBe('Artikel Baru');
    })->group('artikel', 'update');

    test('dapat hapus artikel', function () {
        $artikel = Artikel::factory()->create();

        $response = $this->actingAs($this->user)
            ->delete("/admin/artikel/{$artikel->id}");

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('artikel', [
            'id' => $artikel->id
        ]);
    })->group('artikel', 'delete');

    test('validasi required fields saat create artikel', function () {
        $response = $this->actingAs($this->user)
            ->post('/admin/artikel', []);

        $response->assertSessionHasErrors(['judul', 'isi', 'id_kategori']);
    })->group('artikel', 'validation');

    test('tidak dapat membuat artikel dengan kategori tidak valid', function () {
        $response = $this->actingAs($this->user)
            ->post('/admin/artikel', [
                'judul' => 'Test',
                'id_kategori' => 99999,
                'isi' => 'Content',
                'status' => 'published'
            ]);

        $response->assertSessionHasErrors(['id_kategori']);
    })->group('artikel', 'validation');
});
```

---

## Testing API

### File: `tests/Feature/Api/ArtikelApiTest.php`

```php
<?php

use App\Models\Artikel;
use App\Models\ArtikelKategori;

describe('Artikel API', function () {
    beforeEach(function () {
        $this->kategori = ArtikelKategori::factory()->create();
    });

    test('dapat mengambil list artikel', function () {
        Artikel::factory()->count(15)->create([
            'status' => 'published'
        ]);

        $response = $this->getJson('/api/frontend/v1/artikel');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'judul',
                            'slug',
                            'isi',
                            'gambar',
                            'status',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ],
                'meta' => [
                    'pagination' => [
                        'total',
                        'count',
                        'per_page',
                        'current_page',
                        'total_pages'
                    ]
                ],
                'links'
            ]);
    })->group('api', 'artikel');

    test('pagination API berfungsi dengan benar', function () {
        Artikel::factory()->count(25)->create(['status' => 'published']);

        $response = $this->getJson('/api/frontend/v1/artikel?page[number]=2&page[size]=10');

        $response->assertStatus(200)
            ->assertJsonPath('meta.pagination.current_page', 2)
            ->assertJsonPath('meta.pagination.per_page', 10)
            ->assertJsonCount(10, 'data');
    })->group('api', 'artikel', 'pagination');

    test('dapat filter artikel berdasarkan kategori', function () {
        $kategoriLain = ArtikelKategori::factory()->create();
        
        Artikel::factory()->count(5)->create([
            'id_kategori' => $this->kategori->id,
            'status' => 'published'
        ]);
        
        Artikel::factory()->count(3)->create([
            'id_kategori' => $kategoriLain->id,
            'status' => 'published'
        ]);

        $response = $this->getJson("/api/frontend/v1/artikel?filter[kategori]={$this->kategori->id}");

        $response->assertStatus(200);
        
        $data = $response->json('data');
        expect($data)->toHaveCount(5);
        
        foreach ($data as $item) {
            expect($item['attributes']['id_kategori'])->toBe($this->kategori->id);
        }
    })->group('api', 'artikel', 'filter');

    test('hanya menampilkan artikel published', function () {
        Artikel::factory()->count(5)->create(['status' => 'published']);
        Artikel::factory()->count(3)->create(['status' => 'draft']);

        $response = $this->getJson('/api/frontend/v1/artikel');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        expect($data)->toHaveCount(5);
        
        foreach ($data as $item) {
            expect($item['attributes']['status'])->toBe('published');
        }
    })->group('api', 'artikel');

    test('dapat mengambil detail artikel', function () {
        $artikel = Artikel::factory()->create([
            'status' => 'published'
        ]);

        $response = $this->getJson("/api/frontend/v1/artikel/{$artikel->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.type', 'artikel')
            ->assertJsonPath('data.id', (string) $artikel->id)
            ->assertJsonPath('data.attributes.judul', $artikel->judul);
    })->group('api', 'artikel');

    test('return 404 untuk artikel tidak ditemukan', function () {
        $response = $this->getJson('/api/frontend/v1/artikel/99999');

        $response->assertStatus(404);
    })->group('api', 'artikel');
});
```

---

## Testing Export Data

### File: `tests/Feature/DataDesaExportTest.php`

```php
<?php

use App\Models\DataDesa;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DataDesaExport;

describe('Export Data Desa', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        DataDesa::factory()->count(10)->create();
    });

    test('dapat export data desa ke excel', function () {
        Excel::fake();

        $response = $this->actingAs($this->user)
            ->get('/admin/data-desa/export');

        $response->assertStatus(200);

        Excel::assertDownloaded('data-desa.xlsx', function (DataDesaExport $export) {
            return $export->collection()->count() === 10;
        });
    })->group('export', 'datadesa');

    test('export excel memiliki header yang benar', function () {
        Excel::fake();

        $this->actingAs($this->user)
            ->get('/admin/data-desa/export');

        Excel::assertDownloaded('data-desa.xlsx', function (DataDesaExport $export) {
            $headings = $export->headings();
            
            expect($headings)->toContain('Kode Desa')
                ->toContain('Nama Desa')
                ->toContain('Kecamatan')
                ->toContain('Jumlah Penduduk');
            
            return true;
        });
    })->group('export', 'datadesa');

    test('dapat export dengan filter', function () {
        Excel::fake();

        $kecamatan = 'Kecamatan Test';
        DataDesa::factory()->count(5)->create(['kecamatan' => $kecamatan]);

        $response = $this->actingAs($this->user)
            ->get("/admin/data-desa/export?kecamatan={$kecamatan}");

        $response->assertStatus(200);

        Excel::assertDownloaded('data-desa.xlsx', function (DataDesaExport $export) use ($kecamatan) {
            $data = $export->collection();
            
            foreach ($data as $item) {
                expect($item->kecamatan)->toBe($kecamatan);
            }
            
            return true;
        });
    })->group('export', 'datadesa', 'filter');
});
```

---

## Testing Authentication & Authorization

### File: `tests/Feature/AuthTest.php`

```php
<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

describe('Authentication', function () {
    test('user dapat login dengan kredensial yang benar', function () {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    })->group('auth', 'login');

    test('user tidak dapat login dengan password salah', function () {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    })->group('auth', 'login');

    test('user dapat logout', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    })->group('auth', 'logout');

    test('guest diarahkan ke halaman login', function () {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    })->group('auth', 'middleware');

    test('user terautentikasi dapat akses dashboard', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/dashboard');

        $response->assertStatus(200)
            ->assertViewIs('dashboard.index');
    })->group('auth', 'dashboard');
});

describe('Authorization', function () {
    test('admin dapat akses halaman pengaturan', function () {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->get('/setting/aplikasi');

        $response->assertStatus(200);
    })->group('auth', 'authorization', 'admin');

    test('operator tidak dapat akses halaman pengaturan', function () {
        $operator = User::factory()->create(['role' => 'operator']);

        $response = $this->actingAs($operator)
            ->get('/setting/aplikasi');

        $response->assertStatus(403);
    })->group('auth', 'authorization', 'operator');

    test('user dapat edit profil sendiri', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get("/user/profile/{$user->id}/edit");

        $response->assertStatus(200);
    })->group('auth', 'authorization', 'profile');

    test('user tidak dapat edit profil user lain', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($user)
            ->get("/user/profile/{$otherUser->id}/edit");

        $response->assertStatus(403);
    })->group('auth', 'authorization', 'profile');
});
```

---

## Testing File Upload

### File: `tests/Feature/FileUploadTest.php`

```php
<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

describe('File Upload', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        Storage::fake('public');
    });

    test('dapat upload gambar artikel', function () {
        $file = UploadedFile::fake()->image('artikel.jpg', 1920, 1080);

        $response = $this->actingAs($this->user)
            ->post('/admin/upload/artikel', [
                'image' => $file
            ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        Storage::disk('public')->assertExists('artikel/' . $file->hashName());
    })->group('upload', 'image');

    test('validasi tipe file gambar', function () {
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->actingAs($this->user)
            ->post('/admin/upload/artikel', [
                'image' => $file
            ]);

        $response->assertSessionHasErrors(['image']);
    })->group('upload', 'validation');

    test('validasi ukuran maksimal file', function () {
        $file = UploadedFile::fake()->image('large.jpg')->size(5000); // 5MB

        $response = $this->actingAs($this->user)
            ->post('/admin/upload/artikel', [
                'image' => $file
            ]);

        $response->assertSessionHasErrors(['image']);
    })->group('upload', 'validation');

    test('dapat upload dokumen', function () {
        $file = UploadedFile::fake()->create('dokumen.pdf', 100);

        $response = $this->actingAs($this->user)
            ->post('/admin/upload/dokumen', [
                'file' => $file
            ]);

        $response->assertStatus(200);
        Storage::disk('public')->assertExists('dokumen/' . $file->hashName());
    })->group('upload', 'document');

    test('dapat hapus file yang diupload', function () {
        $file = UploadedFile::fake()->image('artikel.jpg');
        
        Storage::disk('public')->put('artikel/' . $file->hashName(), $file);

        $response = $this->actingAs($this->user)
            ->delete('/admin/upload/artikel/' . $file->hashName());

        $response->assertStatus(200);
        Storage::disk('public')->assertMissing('artikel/' . $file->hashName());
    })->group('upload', 'delete');
});
```

---

## Testing Data Desa

### File: `tests/Feature/DataDesaTest.php`

```php
<?php

use App\Models\DataDesa;
use App\Models\User;

describe('Data Desa Management', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    test('dapat melihat list data desa', function () {
        DataDesa::factory()->count(10)->create();

        $response = $this->actingAs($this->user)
            ->get('/admin/data-desa');

        $response->assertStatus(200)
            ->assertViewHas('dataDesa');
    })->group('datadesa', 'list');

    test('dapat filter data desa berdasarkan kecamatan', function () {
        DataDesa::factory()->count(5)->create(['kecamatan' => 'Kecamatan A']);
        DataDesa::factory()->count(3)->create(['kecamatan' => 'Kecamatan B']);

        $response = $this->actingAs($this->user)
            ->get('/admin/data-desa?kecamatan=Kecamatan A');

        $response->assertStatus(200);
        
        $dataDesa = $response->viewData('dataDesa');
        expect($dataDesa)->toHaveCount(5);
    })->group('datadesa', 'filter');

    test('kode desa harus 10 digit', function () {
        $response = $this->actingAs($this->user)
            ->post('/admin/data-desa', [
                'kode_desa' => '123',
                'nama_desa' => 'Desa Test',
                'kecamatan' => 'Kecamatan Test'
            ]);

        $response->assertSessionHasErrors(['kode_desa']);
    })->group('datadesa', 'validation');

    test('dapat membuat data desa dengan kode valid', function () {
        $response = $this->actingAs($this->user)
            ->post('/admin/data-desa', [
                'kode_desa' => '1234567890',
                'nama_desa' => 'Desa Test',
                'kecamatan' => 'Kecamatan Test',
                'jumlah_penduduk' => 1000
            ]);

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('data_desa', [
            'kode_desa' => '1234567890',
            'nama_desa' => 'Desa Test'
        ]);
    })->group('datadesa', 'create');

    test('kode desa harus unique', function () {
        DataDesa::factory()->create(['kode_desa' => '1234567890']);

        $response = $this->actingAs($this->user)
            ->post('/admin/data-desa', [
                'kode_desa' => '1234567890',
                'nama_desa' => 'Desa Baru',
                'kecamatan' => 'Kecamatan Baru'
            ]);

        $response->assertSessionHasErrors(['kode_desa']);
    })->group('datadesa', 'validation');

    test('dapat update jumlah penduduk', function () {
        $desa = DataDesa::factory()->create(['jumlah_penduduk' => 1000]);

        $response = $this->actingAs($this->user)
            ->put("/admin/data-desa/{$desa->id}", [
                'kode_desa' => $desa->kode_desa,
                'nama_desa' => $desa->nama_desa,
                'kecamatan' => $desa->kecamatan,
                'jumlah_penduduk' => 1500
            ]);

        $response->assertRedirect();
        expect($desa->fresh()->jumlah_penduduk)->toBe(1500);
    })->group('datadesa', 'update');
});
```

---

## Testing Dashboard

### File: `tests/Feature/DashboardTest.php`

```php
<?php

use App\Models\User;
use App\Models\Artikel;
use App\Models\DataDesa;

describe('Dashboard', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    test('dashboard menampilkan statistik yang benar', function () {
        Artikel::factory()->count(10)->create();
        DataDesa::factory()->count(5)->create();

        $response = $this->actingAs($this->user)
            ->get('/dashboard');

        $response->assertStatus(200)
            ->assertViewHas('totalArtikel', 10)
            ->assertViewHas('totalDesa', 5);
    })->group('dashboard', 'statistics');

    test('dashboard menampilkan artikel terbaru', function () {
        $artikelTerbaru = Artikel::factory()->count(5)->create([
            'created_at' => now()
        ]);

        Artikel::factory()->count(3)->create([
            'created_at' => now()->subDays(7)
        ]);

        $response = $this->actingAs($this->user)
            ->get('/dashboard');

        $response->assertStatus(200);
        
        $artikelDiDashboard = $response->viewData('artikelTerbaru');
        expect($artikelDiDashboard)->toHaveCount(5);
    })->group('dashboard', 'content');

    test('dashboard menampilkan grafik data penduduk', function () {
        DataDesa::factory()->count(10)->create();

        $response = $this->actingAs($this->user)
            ->get('/dashboard/chart-penduduk');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'labels',
                'datasets' => [
                    '*' => ['label', 'data']
                ]
            ]);
    })->group('dashboard', 'chart');
});
```

---

## Testing Browser/E2E

### File: `tests/Browser/LoginTest.php`

```php
<?php

use App\Models\User;

test('user dapat login melalui form login', function () {
    $user = User::factory()->create([
        'email' => 'test@opendk.test',
        'password' => bcrypt('password123')
    ]);

    $this->browse(function ($browser) {
        $browser->visit('/login')
            ->assertSee('Login')
            ->type('email', 'test@opendk.test')
            ->type('password', 'password123')
            ->press('Login')
            ->waitForLocation('/dashboard')
            ->assertPathIs('/dashboard')
            ->assertSee('Dashboard');
    });
})->group('browser', 'auth');

test('menampilkan error untuk kredensial yang salah', function () {
    User::factory()->create([
        'email' => 'test@opendk.test',
        'password' => bcrypt('password123')
    ]);

    $this->browse(function ($browser) {
        $browser->visit('/login')
            ->type('email', 'test@opendk.test')
            ->type('password', 'wrongpassword')
            ->press('Login')
            ->waitForText('kredensial')
            ->assertSee('kredensial');
    });
})->group('browser', 'auth');
```

### File: `tests/Browser/ArtikelBrowserTest.php`

```php
<?php

use App\Models\User;
use App\Models\ArtikelKategori;

test('admin dapat membuat artikel melalui form', function () {
    $user = User::factory()->create();
    $kategori = ArtikelKategori::factory()->create();

    $this->actingAs($user)->browse(function ($browser) use ($kategori) {
        $browser->visit('/admin/artikel/create')
            ->assertSee('Buat Artikel')
            ->type('judul', 'Artikel Test Browser')
            ->select('id_kategori', $kategori->id)
            ->type('isi', 'Konten artikel test dari browser')
            ->select('status', 'published')
            ->press('Simpan')
            ->waitForLocation('/admin/artikel')
            ->assertPathIs('/admin/artikel')
            ->assertSee('Artikel Test Browser');
    });
})->group('browser', 'artikel');
```

---

## Testing Services

### File: `tests/Unit/OtpServiceTest.php`

```php
<?php

use App\Models\User;
use App\Models\OtpToken;
use App\Services\OtpService;
use Illuminate\Support\Facades\Mail;

describe('OTP Service', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->otpService = new OtpService();
        Mail::fake();
    });

    test('dapat generate OTP token', function () {
        $result = $this->otpService->generateAndSend(
            $this->user,
            'email',
            $this->user->email,
            '2fa_activation'
        );

        expect($result['sent'])->toBeTrue()
            ->and($result['token'])->toBeInstanceOf(OtpToken::class);

        $this->assertDatabaseHas('otp_tokens', [
            'user_id' => $this->user->id,
            'channel' => 'email',
            'purpose' => '2fa_activation'
        ]);
    })->group('service', 'otp');

    test('OTP token memiliki format yang benar', function () {
        $result = $this->otpService->generateAndSend(
            $this->user,
            'email',
            $this->user->email,
            '2fa_activation'
        );

        $token = $result['token'];

        expect($token->token)
            ->toBeString()
            ->toHaveLength(6)
            ->toMatch('/^\d{6}$/');
    })->group('service', 'otp');

    test('dapat verify OTP token yang valid', function () {
        $result = $this->otpService->generateAndSend(
            $this->user,
            'email',
            $this->user->email,
            '2fa_activation'
        );

        $verified = $this->otpService->verify(
            $this->user,
            $result['token']->token,
            '2fa_activation'
        );

        expect($verified)->toBeTrue();
    })->group('service', 'otp');

    test('OTP token expired setelah waktu tertentu', function () {
        $token = OtpToken::create([
            'user_id' => $this->user->id,
            'token' => '123456',
            'channel' => 'email',
            'identifier' => $this->user->email,
            'purpose' => '2fa_activation',
            'expires_at' => now()->subMinutes(10)
        ]);

        $verified = $this->otpService->verify(
            $this->user,
            '123456',
            '2fa_activation'
        );

        expect($verified)->toBeFalse();
    })->group('service', 'otp');
});
```

---

## Testing dengan Dataset

### File: `tests/Feature/ValidationTest.php`

```php
<?php

use App\Models\User;

describe('Email Validation', function () {
    test('email validation dengan berbagai format', function ($email, $valid) {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/user/update-email', [
                'email' => $email
            ]);

        if ($valid) {
            $response->assertStatus(200);
        } else {
            $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
        }
    })->with([
        ['user@example.com', true],
        ['user@domain.co.id', true],
        ['user.name@example.com', true],
        ['user+tag@example.com', true],
        ['invalid-email', false],
        ['@example.com', false],
        ['user@', false],
        ['user example@test.com', false],
    ])->group('validation', 'email');
});

describe('Kode Desa Validation', function () {
    test('kode desa validation dengan berbagai input', function ($kodeDesa, $valid) {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/data-desa', [
                'kode_desa' => $kodeDesa,
                'nama_desa' => 'Test Desa',
                'kecamatan' => 'Test Kecamatan'
            ]);

        if ($valid) {
            $response->assertStatus(201);
        } else {
            $response->assertStatus(422)
                ->assertJsonValidationErrors(['kode_desa']);
        }
    })->with([
        ['1234567890', true],      // 10 digit valid
        ['0987654321', true],      // 10 digit valid
        ['123456789', false],      // 9 digit
        ['12345678901', false],    // 11 digit
        ['abcdefghij', false],     // non-numeric
        ['12345-6789', false],     // dengan karakter khusus
        ['', false],               // empty
    ])->group('validation', 'kodedesa');
});

describe('Slug Generation', function () {
    test('slug generation dengan berbagai input', function ($input, $expected) {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/artikel', [
                'judul' => $input,
                'isi' => 'Test content',
                'id_kategori' => 1
            ]);

        if ($response->status() === 201) {
            $slug = $response->json('data.attributes.slug');
            expect($slug)->toBe($expected);
        }
    })->with([
        ['Artikel Test', 'artikel-test'],
        ['Artikel Test 123', 'artikel-test-123'],
        ['ARTIKEL UPPERCASE', 'artikel-uppercase'],
        ['Artikel   Spasi   Banyak', 'artikel-spasi-banyak'],
        ['Artikel & Special @ Characters!', 'artikel-special-characters'],
    ])->group('validation', 'slug');
});
```

---

## Tips & Best Practices

### 1. Gunakan Factory untuk Test Data

```php
// ✅ Good
$user = User::factory()->create();
$artikel = Artikel::factory()->count(10)->create();

// ❌ Avoid
$user = new User();
$user->name = 'Test';
$user->email = 'test@test.com';
// ...
```

### 2. Kelompokkan Test yang Berkaitan

```php
describe('User Management', function () {
    test('dapat membuat user')->group('user', 'create');
    test('dapat update user')->group('user', 'update');
    test('dapat hapus user')->group('user', 'delete');
});
```

### 3. Gunakan beforeEach untuk Setup

```php
beforeEach(function () {
    $this->user = User::factory()->create();
    $this->kategori = ArtikelKategori::factory()->create();
});
```

### 4. Test Isolation

Setiap test harus independen dan tidak bergantung pada test lain.

### 5. Naming yang Jelas

```php
// ✅ Good
test('user dapat login dengan kredensial yang valid')
test('sistem menolak login dengan password salah')

// ❌ Avoid
test('test login')
test('test 1')
```

---

**Catatan:** Semua contoh di atas menggunakan Pest 4 syntax dan best practices untuk aplikasi OpenDK.

**Last Updated:** November 2025  
**Version:** 1.0
