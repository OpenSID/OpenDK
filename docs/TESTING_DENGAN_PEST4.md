# Dokumentasi Testing dengan Pest 4

## Daftar Isi
- [Pengenalan](#pengenalan)
- [Instalasi dan Setup](#instalasi-dan-setup)
- [Struktur Direktori Testing](#struktur-direktori-testing)
- [Jenis-Jenis Testing](#jenis-jenis-testing)
- [Menulis Test dengan Pest 4](#menulis-test-dengan-pest-4)
- [Menjalankan Test](#menjalankan-test)
- [Best Practices](#best-practices)
- [Troubleshooting](#troubleshooting)

---

## Pengenalan

Pest adalah testing framework untuk PHP yang dibangun di atas PHPUnit dengan syntax yang lebih elegan dan ekspresif. Pest 4 adalah versi terbaru yang membawa peningkatan performa dan fitur-fitur baru.

### Keunggulan Pest 4
- ✅ Syntax yang lebih simple dan mudah dibaca
- ✅ Performa lebih cepat
- ✅ Support penuh untuk PHP 8.3+
- ✅ Integrasi sempurna dengan Laravel
- ✅ Browser testing dengan Playwright
- ✅ Parallel testing untuk eksekusi lebih cepat

### Package Pest yang Digunakan
```json
{
    "pestphp/pest": "^4.0",
    "pestphp/pest-plugin-laravel": "^4.0",
    "pestphp/pest-plugin-browser": "^4.0"
}
```

---

## Instalasi dan Setup

### Requirement
- PHP >= 8.3
- Laravel 11
- Composer

### Package Sudah Terinstall
Pest 4 sudah terinstall di aplikasi ini. Untuk melihat versi:
```bash
./vendor/bin/pest --version
```

### Konfigurasi Pest

File konfigurasi utama ada di `tests/Pest.php`:
```php
<?php

// Extend TestCase untuk semua feature tests
pest()->extend(Tests\TestCase::class)
    ->in('Feature');

// Extend TestCase untuk browser tests dengan konfigurasi khusus
pest()->extend(Tests\TestCase::class)
    ->in('Browser')
    ->beforeEach(function () {
        config(['app.url' => env('APP_URL', 'http://opendk.test')]);
    });
```

### Konfigurasi PHPUnit

File `phpunit.xml` berisi konfigurasi environment untuk testing:
```xml
<php>
    <server name="APP_ENV" value="testing"/>
    <server name="APP_URL" value="http://opendk.test"/>
    <server name="BCRYPT_ROUNDS" value="4"/>
    <server name="CACHE_DRIVER" value="array"/>
    <server name="MAIL_MAILER" value="array"/>
    <server name="QUEUE_CONNECTION" value="sync"/>
    <server name="SESSION_DRIVER" value="array"/>
    <server name="TELESCOPE_ENABLED" value="false"/>
    <server name="PLAYWRIGHT_TIMEOUT" value="30000"/>
</php>
```

---

## Struktur Direktori Testing

```
tests/
├── Pest.php                    # Konfigurasi Pest
├── TestCase.php                # Base TestCase
├── CreatesApplication.php      # Bootstrap Laravel app
├── CrudTestCase.php           # Helper untuk CRUD testing
│
├── Unit/                       # Unit tests
│   ├── ExampleTest.php
│   ├── OtpServiceTest.php
│   └── TwoFactorServiceTest.php
│
├── Feature/                    # Feature/Integration tests
│   ├── ExampleTest.php
│   ├── ArtikelControllerTest.php
│   ├── DataUmumControllerTest.php
│   └── [42 file test lainnya]
│
├── Browser/                    # Browser/E2E tests
│   ├── ExampleBrowserTest.php
│   ├── LoginTest.php
│   ├── DashboardTest.php
│   ├── HomepageTest.php
│   └── AccessibilityTest.php
│
└── e2e/                        # Playwright E2E tests
    └── [test files]
```

---

## Jenis-Jenis Testing

### 1. Unit Testing

Unit test digunakan untuk menguji fungsi atau method secara terisolasi.

**Lokasi:** `tests/Unit/`

**Contoh:**
```php
<?php

use App\Services\OtpService;
use App\Models\User;

test('dapat generate OTP token untuk user', function () {
    $user = User::factory()->create();
    $otpService = new OtpService();
    
    $result = $otpService->generateAndSend(
        $user,
        'email',
        'test@example.com',
        '2fa_activation'
    );
    
    expect($result['sent'])->toBeTrue()
        ->and($result['token'])->not->toBeNull();
});
```

### 2. Feature Testing

Feature test menguji fitur aplikasi secara keseluruhan, termasuk HTTP request/response.

**Lokasi:** `tests/Feature/`

**Contoh:**
```php
<?php

use App\Models\Artikel;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

test('dapat mengambil semua artikel via API', function () {
    Artikel::factory()->count(5)->create();
    
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
                        'status'
                    ]
                ]
            ]
        ]);
});

test('dapat membuat artikel baru', function () {
    $user = User::factory()->create();
    
    $this->actingAs($user)
        ->postJson('/api/artikel', [
            'judul' => 'Test Artikel',
            'isi' => 'Konten artikel test',
            'id_kategori' => 1
        ])
        ->assertStatus(201);
        
    $this->assertDatabaseHas('artikel', [
        'judul' => 'Test Artikel'
    ]);
});
```

### 3. Browser Testing

Browser test menguji aplikasi melalui browser menggunakan Playwright.

**Lokasi:** `tests/Browser/`

**Contoh:**
```php
<?php

test('user dapat login ke aplikasi', function () {
    $user = User::first();
    
    $this->browse(function ($browser) use ($user) {
        $browser->visit('/login')
            ->type('email', $user->email)
            ->type('password', 'password')
            ->press('Login')
            ->assertPathIs('/dashboard')
            ->assertSee('Dashboard');
    });
})->group('browser');

test('dapat mengakses halaman pengaturan', function () {
    $user = User::first();
    
    $this->actingAs($user)
        ->visit('/setting/aplikasi')
        ->assertSee('Pengaturan Aplikasi');
})->group('browser', 'settings');
```

---

## Menulis Test dengan Pest 4

### Syntax Dasar

#### Test Function
```php
test('deskripsi test', function () {
    // test code
});
```

#### It Function (Alternatif)
```php
it('dapat melakukan sesuatu', function () {
    // test code
});
```

### Expectations

Pest 4 menyediakan API expectations yang ekspresif:

```php
// Boolean expectations
expect($value)->toBeTrue();
expect($value)->toBeFalse();

// Null expectations
expect($value)->toBeNull();
expect($value)->not->toBeNull();

// Type expectations
expect($value)->toBeString();
expect($value)->toBeInt();
expect($value)->toBeArray();
expect($value)->toBeInstanceOf(User::class);

// Comparison expectations
expect($value)->toBe(10);
expect($value)->toEqual($expected);
expect($value)->toBeGreaterThan(5);
expect($value)->toBeLessThan(20);

// String expectations
expect($string)->toContain('substring');
expect($string)->toStartWith('prefix');
expect($string)->toEndWith('suffix');

// Array/Collection expectations
expect($array)->toHaveCount(5);
expect($array)->toContain('item');
expect($array)->toHaveKey('key');

// Laravel specific expectations
expect($user)->toBeInstanceOf(User::class);
expect($collection)->toHaveCount(10);
```

### Chaining Expectations

```php
expect($user)
    ->toBeInstanceOf(User::class)
    ->and($user->email)->toBeString()
    ->and($user->id)->toBeInt();
```

### Dataset (Data Providers)

Gunakan dataset untuk menjalankan test dengan berbagai input:

```php
test('email validation', function ($email, $valid) {
    $result = validateEmail($email);
    expect($result)->toBe($valid);
})->with([
    ['test@example.com', true],
    ['invalid-email', false],
    ['test@domain', false],
    ['user@mail.co.id', true],
]);
```

### Hooks

#### beforeEach & afterEach
```php
beforeEach(function () {
    // Setup sebelum setiap test
    $this->user = User::factory()->create();
});

afterEach(function () {
    // Cleanup setelah setiap test
});
```

#### beforeAll & afterAll
```php
beforeAll(function () {
    // Setup sekali sebelum semua test
});

afterAll(function () {
    // Cleanup sekali setelah semua test
});
```

### Groups

Kelompokkan test untuk eksekusi selektif:

```php
test('test artikel', function () {
    // test code
})->group('artikel', 'api');

test('test browser', function () {
    // test code
})->group('browser', 'slow');
```

Jalankan test berdasarkan group:
```bash
./vendor/bin/pest --group=artikel
./vendor/bin/pest --group=browser
```

### Skip & Only

```php
// Skip test
test('test yang diskip', function () {
    // code
})->skip();

// Skip dengan kondisi
test('test yang diskip di CI', function () {
    // code
})->skip(fn() => env('CI') === true);

// Hanya jalankan test ini (untuk debugging)
test('test yang difokuskan', function () {
    // code
})->only();
```

### Todo

Tandai test yang belum diimplementasi:

```php
test('fitur yang belum diimplementasi')->todo();
```

### Database Transactions

Gunakan trait untuk auto rollback database:

```php
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

test('membuat data artikel', function () {
    Artikel::create([...]);
    // Data akan di-rollback setelah test
});
```

### Factories

Gunakan factory untuk membuat test data:

```php
test('user dapat membuat artikel', function () {
    $user = User::factory()->create();
    $artikel = Artikel::factory()->create(['user_id' => $user->id]);
    
    expect($artikel->user)->toBe($user);
});

// Factory dengan atribut custom
test('user admin dapat approve artikel', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    
    expect($admin->role)->toBe('admin');
});
```

### HTTP Testing

```php
test('API endpoint artikel', function () {
    $response = $this->getJson('/api/artikel');
    
    $response->assertStatus(200)
        ->assertJsonStructure(['data', 'meta', 'links']);
});

test('create artikel via API', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)
        ->postJson('/api/artikel', [
            'judul' => 'Test',
            'isi' => 'Content'
        ]);
        
    $response->assertStatus(201)
        ->assertJson(['data' => ['attributes' => ['judul' => 'Test']]]);
});
```

### Authentication

```php
test('user terautentikasi dapat akses dashboard', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->get('/dashboard');
    
    $response->assertStatus(200)
        ->assertSee('Dashboard');
});
```

---

## Menjalankan Test

### Basic Commands

```bash
# Menjalankan semua test
./vendor/bin/pest

# Menjalankan test di direktori tertentu
./vendor/bin/pest tests/Unit
./vendor/bin/pest tests/Feature
./vendor/bin/pest tests/Browser

# Menjalankan file test tertentu
./vendor/bin/pest tests/Feature/ArtikelControllerTest.php

# Menjalankan test dengan nama spesifik
./vendor/bin/pest --filter="dapat mengambil semua artikel"
```

### Group & Tag

```bash
# Menjalankan test berdasarkan group
./vendor/bin/pest --group=artikel
./vendor/bin/pest --group=browser
./vendor/bin/pest --group=api

# Exclude group tertentu
./vendor/bin/pest --exclude-group=browser
./vendor/bin/pest --exclude-group=slow
```

### Output & Reporting

```bash
# Verbose output
./vendor/bin/pest --verbose
./vendor/bin/pest -v

# Compact output
./vendor/bin/pest --compact

# Test coverage
./vendor/bin/pest --coverage

# Test coverage dengan minimum threshold
./vendor/bin/pest --coverage --min=80

# Generate HTML coverage report
./vendor/bin/pest --coverage --coverage-html=coverage
```

### Parallel Testing

```bash
# Jalankan test secara parallel
./vendor/bin/pest --parallel

# Parallel dengan jumlah proses tertentu
./vendor/bin/pest --parallel --processes=4
```

### Profiling

```bash
# Tampilkan test yang paling lambat
./vendor/bin/pest --profile

# Tampilkan top 10 test terlambat
./vendor/bin/pest --profile --top=10
```

### Watch Mode (Auto Re-run)

```bash
# Auto re-run test saat file berubah
./vendor/bin/pest --watch
```

### Continuous Integration

```bash
# Mode CI (fail fast, no colors)
./vendor/bin/pest --ci

# Dengan retry untuk test yang gagal
./vendor/bin/pest --retry=2
```

---

## Best Practices

### 1. Naming Convention

Gunakan naming yang deskriptif:

```php
// ❌ Bad
test('test 1', function () {});

// ✅ Good
test('user dapat login dengan kredensial yang valid', function () {});
test('sistem menolak login dengan password salah', function () {});
```

### 2. Arrange-Act-Assert Pattern

Struktur test dengan pola AAA:

```php
test('user dapat membuat artikel', function () {
    // Arrange - Setup
    $user = User::factory()->create();
    $data = ['judul' => 'Test', 'isi' => 'Content'];
    
    // Act - Eksekusi
    $response = $this->actingAs($user)->post('/artikel', $data);
    
    // Assert - Verifikasi
    $response->assertStatus(201);
    $this->assertDatabaseHas('artikel', ['judul' => 'Test']);
});
```

### 3. Gunakan Factories

```php
// ❌ Bad
test('create user', function () {
    $user = new User();
    $user->name = 'Test User';
    $user->email = 'test@example.com';
    $user->password = Hash::make('password');
    $user->save();
});

// ✅ Good
test('create user', function () {
    $user = User::factory()->create();
});
```

### 4. Database Transactions

Selalu gunakan DatabaseTransactions untuk menghindari data kotor:

```php
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

test('create data', function () {
    // Data akan di-rollback otomatis
});
```

### 5. Group Test

Kelompokkan test yang sejenis:

```php
test('artikel index')->group('artikel', 'api');
test('artikel show')->group('artikel', 'api');
test('artikel create')->group('artikel', 'api', 'write');
test('artikel update')->group('artikel', 'api', 'write');
test('artikel delete')->group('artikel', 'api', 'write');
```

### 6. Test Isolation

Setiap test harus independen:

```php
// ❌ Bad - Test bergantung satu sama lain
test('create artikel', function () {
    $this->artikel = Artikel::create([...]);
});

test('update artikel', function () {
    $this->artikel->update([...]); // Bergantung pada test sebelumnya
});

// ✅ Good - Test independen
test('create artikel', function () {
    $artikel = Artikel::factory()->create();
    expect($artikel)->toBeInstanceOf(Artikel::class);
});

test('update artikel', function () {
    $artikel = Artikel::factory()->create(); // Setup sendiri
    $artikel->update(['judul' => 'Updated']);
    expect($artikel->judul)->toBe('Updated');
});
```

### 7. Expectations yang Spesifik

```php
// ❌ Bad
test('API returns data', function () {
    $response = $this->get('/api/artikel');
    expect($response->status())->toBe(200);
});

// ✅ Good
test('API returns artikel list dengan struktur yang benar', function () {
    $response = $this->getJson('/api/artikel');
    
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['type', 'id', 'attributes']
            ],
            'meta',
            'links'
        ]);
});
```

### 8. Test Data yang Realistis

```php
// ❌ Bad
$user = User::factory()->create(['name' => 'a']);

// ✅ Good
$user = User::factory()->create([
    'name' => 'John Doe',
    'email' => 'john.doe@example.com'
]);
```

### 9. Dokumentasi dengan Comments

```php
test('user dapat reset password dengan token valid', function () {
    // Given: User dengan token reset password yang valid
    $user = User::factory()->create();
    $token = Password::createToken($user);
    
    // When: User submit form reset password dengan token valid
    $response = $this->post('/reset-password', [
        'email' => $user->email,
        'token' => $token,
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123'
    ]);
    
    // Then: Password berhasil direset
    $response->assertRedirect('/login');
    expect(Hash::check('newpassword123', $user->fresh()->password))
        ->toBeTrue();
});
```

### 10. Gunakan Custom Expectations

Buat custom expectations di `tests/Pest.php` untuk assertions yang sering digunakan:

```php
// tests/Pest.php
expect()->extend('toBeValidEmail', function () {
    return $this->toMatch('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/');
});

// Gunakan di test
test('validasi email', function () {
    expect('user@example.com')->toBeValidEmail();
});
```

---

## Troubleshooting

### Problem: Test Gagal dengan Database Error

**Solusi:**
1. Pastikan database testing sudah dikonfigurasi di `phpunit.xml` atau `.env.testing`
2. Jalankan migration untuk database testing:
   ```bash
   php artisan migrate --env=testing
   ```
3. Gunakan `DatabaseTransactions` trait

### Problem: Browser Test Tidak Berjalan

**Solusi:**
1. Install Playwright browsers:
   ```bash
   npx playwright install
   ```
2. Pastikan APP_URL sudah dikonfigurasi dengan benar
3. Cek apakah development server berjalan

### Problem: Test Berjalan Lambat

**Solusi:**
1. Gunakan parallel testing:
   ```bash
   ./vendor/bin/pest --parallel
   ```
2. Exclude browser test jika tidak diperlukan:
   ```bash
   ./vendor/bin/pest --exclude-group=browser
   ```
3. Gunakan in-memory SQLite untuk test:
   ```xml
   <!-- phpunit.xml -->
   <server name="DB_CONNECTION" value="sqlite"/>
   <server name="DB_DATABASE" value=":memory:"/>
   ```

### Problem: Memory Limit Exceeded

**Solusi:**
1. Increase memory limit:
   ```bash
   php -d memory_limit=512M ./vendor/bin/pest
   ```
2. Gunakan DatabaseTransactions untuk cleanup
3. Hindari membuat terlalu banyak data dalam satu test

### Problem: Test Gagal di CI tapi Berhasil di Local

**Solusi:**
1. Pastikan environment CI sama dengan local
2. Gunakan retry untuk test yang flaky:
   ```bash
   ./vendor/bin/pest --retry=2
   ```
3. Set timeout yang cukup untuk CI
4. Debug dengan verbose output:
   ```bash
   ./vendor/bin/pest --verbose
   ```

---

## Referensi

- [Pest Official Documentation](https://pestphp.com)
- [Pest Plugin Laravel](https://pestphp.com/docs/plugins/laravel)
- [Pest Plugin Browser](https://pestphp.com/docs/plugins/browser)
- [Laravel Testing Documentation](https://laravel.com/docs/testing)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)

---

## Kontribusi

Jika menemukan bug atau ingin menambahkan test baru:

1. Buat test yang mereproduksi bug
2. Fix bug
3. Pastikan semua test passing
4. Submit pull request

```bash
# Sebelum commit, pastikan semua test passing
./vendor/bin/pest

# Jalankan code style check
./vendor/bin/pint
```

---

**Dibuat untuk:** OpenDK  
**Versi Pest:** 4.0  
**Tanggal:** November 2025  
**Maintainer:** Tim Pengembang OpenDesa
