# Workflow Testing dengan Pest 4 - Tim OpenDK

Panduan workflow testing untuk tim development OpenDK agar testing berjalan konsisten dan efisien.

## 📋 Daftar Isi

- [Persiapan Environment](#persiapan-environment)
- [Workflow Development](#workflow-development)
- [Code Review Checklist](#code-review-checklist)
- [Continuous Integration](#continuous-integration)
- [Testing Strategy](#testing-strategy)
- [Dokumentasi Test](#dokumentasi-test)
- [Troubleshooting Common Issues](#troubleshooting-common-issues)

---

## Persiapan Environment

### 1. Setup Local Environment

```bash
# Clone repository
git clone https://github.com/OpenSID/opendk.git
cd opendk

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env.testing
php artisan key:generate

# Setup database testing
# Edit .env.testing untuk database testing
```

### 2. Konfigurasi Database Testing

Edit file `.env.testing`:

```env
APP_ENV=testing
APP_DEBUG=true
APP_URL=http://opendk.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=opendk_testing
DB_USERNAME=root
DB_PASSWORD=

# Atau gunakan SQLite untuk speed
# DB_CONNECTION=sqlite
# DB_DATABASE=:memory:

CACHE_DRIVER=array
QUEUE_CONNECTION=sync
SESSION_DRIVER=array
MAIL_MAILER=array
```

### 3. Jalankan Migration untuk Testing

```bash
# Buat database testing
mysql -u root -e "CREATE DATABASE IF NOT EXISTS opendk_testing"

# Jalankan migration
php artisan migrate --env=testing

# Seed data jika diperlukan
php artisan db:seed --env=testing
```

### 4. Verify Setup

```bash
# Check Pest version
./vendor/bin/pest --version

# Jalankan test contoh
./vendor/bin/pest tests/Feature/ExampleTest.php

# Jika berhasil, setup sudah benar
```

---

## Workflow Development

### 1. Sebelum Menulis Code (TDD Approach)

**Test-Driven Development (TDD)** - Recommended:

```bash
# 1. Buat branch baru untuk fitur
git checkout -b feature/artikel-comment

# 2. Tulis test terlebih dahulu
# File: tests/Feature/ArtikelCommentTest.php
```

```php
<?php

use App\Models\User;
use App\Models\Artikel;

test('user dapat menambahkan comment pada artikel', function () {
    $user = User::factory()->create();
    $artikel = Artikel::factory()->create();

    $response = $this->actingAs($user)
        ->post("/artikel/{$artikel->id}/comment", [
            'content' => 'Test comment'
        ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('artikel_comments', [
        'artikel_id' => $artikel->id,
        'user_id' => $user->id,
        'content' => 'Test comment'
    ]);
})->group('artikel', 'comment');
```

```bash
# 3. Jalankan test (akan gagal karena fitur belum ada)
./vendor/bin/pest tests/Feature/ArtikelCommentTest.php

# 4. Implementasi fitur sampai test passing
# - Buat migration
# - Buat model
# - Buat controller
# - Buat routes

# 5. Jalankan test lagi sampai passing
./vendor/bin/pest tests/Feature/ArtikelCommentTest.php
```

### 2. Development Workflow

```bash
# A. Setelah menulis/mengubah code, jalankan test
./vendor/bin/pest

# B. Jika ada test yang gagal, fix code
# Jalankan test spesifik untuk debugging
./vendor/bin/pest --filter="user dapat menambahkan comment"

# C. Setelah semua test passing, jalankan semua test
./vendor/bin/pest

# D. Check code style
./vendor/bin/pint

# E. Commit changes
git add .
git commit -m "feat: tambah fitur comment artikel dengan test"

# F. Push ke remote
git push origin feature/artikel-comment
```

### 3. Menulis Test untuk Bug Fix

```bash
# 1. Buat branch untuk bug fix
git checkout -b fix/artikel-slug-duplicate

# 2. Tulis test yang mereproduksi bug
```

```php
test('slug artikel harus unique', function () {
    Artikel::factory()->create(['slug' => 'artikel-test']);

    $response = $this->actingAs($user)
        ->post('/artikel', [
            'judul' => 'Artikel Test',  // akan generate slug yang sama
            // ...
        ]);

    // Test ini akan gagal jika bug masih ada
    $artikel = Artikel::where('judul', 'Artikel Test')->first();
    expect($artikel->slug)->not->toBe('artikel-test');
})->group('artikel', 'bug-fix');
```

```bash
# 3. Fix bug di code

# 4. Jalankan test untuk verify fix
./vendor/bin/pest --filter="slug artikel harus unique"

# 5. Commit dan push
git add .
git commit -m "fix: handle duplicate slug artikel"
git push origin fix/artikel-slug-duplicate
```

### 4. Refactoring dengan Test

```bash
# 1. Pastikan semua test passing sebelum refactor
./vendor/bin/pest

# 2. Refactor code
# Contoh: Extract method, rename variable, etc.

# 3. Jalankan test setelah refactor
./vendor/bin/pest

# 4. Jika test passing, refactor berhasil
# Jika gagal, revert atau fix refactoring

# 5. Commit
git commit -m "refactor: extract method untuk validasi artikel"
```

---

## Code Review Checklist

### Untuk Author (Yang Submit PR)

**Sebelum Create Pull Request:**

- [ ] Semua test passing
  ```bash
  ./vendor/bin/pest
  ```

- [ ] Code style sudah di-format
  ```bash
  ./vendor/bin/pint
  ```

- [ ] Test coverage mencukupi (minimal 80%)
  ```bash
  ./vendor/bin/pest --coverage --min=80
  ```

- [ ] Tidak ada test yang di-skip tanpa alasan
  ```bash
  # Cek apakah ada test dengan ->skip()
  grep -r "->skip()" tests/
  ```

- [ ] Test menggunakan DatabaseTransactions
  ```php
  uses(DatabaseTransactions::class);
  ```

- [ ] Test di-group dengan benar
  ```php
  test('...')->group('artikel', 'api');
  ```

### Untuk Reviewer

**Checklist saat Review PR:**

- [ ] Test ada untuk fitur baru
- [ ] Test ada untuk bug fix
- [ ] Test naming descriptive dan jelas
- [ ] Test menggunakan factory untuk data
- [ ] Test tidak saling bergantung (isolated)
- [ ] Assertions spesifik dan bermakna
- [ ] Tidak ada hardcoded values yang seharusnya dynamic
- [ ] Edge cases sudah di-test

**Contoh Good vs Bad:**

```php
// ❌ Bad - Test tidak jelas
test('test 1', function () {
    $user = User::find(1);
    expect($user)->not->toBeNull();
});

// ✅ Good - Test jelas dan lengkap
test('dapat membuat user dengan email valid', function () {
    $user = User::factory()->create([
        'email' => 'valid@example.com'
    ]);

    expect($user->email)->toBe('valid@example.com')
        ->and($user->exists)->toBeTrue();
})->group('user', 'validation');
```

---

## Continuous Integration

### 1. GitHub Actions Workflow

File: `.github/workflows/tests.yml`

```yaml
name: Tests

on:
  push:
    branches: [ dev-pest, master ]
  pull_request:
    branches: [ dev-pest, master ]

jobs:
  test:
    runs-on: ubuntu-latest

    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ROOT_PASSWORD: password
          MYSQL_DATABASE: opendk_testing
        ports:
          - 3306:3306
        options: --health-cmd="mysqladmin ping" --health-interval=10s --health-timeout=5s --health-retries=3

    steps:
    - uses: actions/checkout@v3

    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.3'
        extensions: mbstring, xml, ctype, json, bcmath, pdo, mysql
        coverage: xdebug

    - name: Install Dependencies
      run: composer install --prefer-dist --no-progress

    - name: Copy .env
      run: php -r "file_exists('.env') || copy('.env.example', '.env');"

    - name: Generate key
      run: php artisan key:generate

    - name: Run Migrations
      run: php artisan migrate --env=testing --force

    - name: Run Pest Tests
      run: ./vendor/bin/pest --ci --parallel

    - name: Run Pest with Coverage
      run: ./vendor/bin/pest --coverage --min=70

    - name: Upload Coverage
      uses: codecov/codecov-action@v3
      with:
        files: ./coverage.xml
```

### 2. Pre-commit Hook (Optional)

File: `.git/hooks/pre-commit`

```bash
#!/bin/bash

echo "Running Pest tests..."

# Jalankan test
./vendor/bin/pest

if [ $? -ne 0 ]; then
    echo "Tests failed. Commit aborted."
    exit 1
fi

echo "All tests passed!"

# Check code style
echo "Checking code style..."
./vendor/bin/pint --test

if [ $? -ne 0 ]; then
    echo "Code style issues found. Run './vendor/bin/pint' to fix."
    exit 1
fi

echo "Code style OK!"
exit 0
```

Aktifkan hook:
```bash
chmod +x .git/hooks/pre-commit
```

### 3. CI Best Practices

```bash
# Gunakan --ci flag untuk CI environment
./vendor/bin/pest --ci

# Parallel untuk speed
./vendor/bin/pest --parallel --processes=4

# Retry untuk flaky tests
./vendor/bin/pest --retry=2

# Coverage minimum threshold
./vendor/bin/pest --coverage --min=70
```

---

## Testing Strategy

### 1. Test Pyramid

```
        /\
       /  \        E2E/Browser Tests (10%)
      /    \       - Critical user flows
     /------\      - Login, checkout, etc.
    /        \
   /          \    Integration/Feature Tests (30%)
  /            \   - API endpoints
 /              \  - Controller actions
/----------------\ Unit Tests (60%)
                   - Services
                   - Helpers
                   - Models
```

### 2. What to Test

**✅ Test These:**
- Business logic
- API endpoints
- Validations
- Authorization
- Critical user flows
- Edge cases
- Error handling

**❌ Don't Test These:**
- Framework code (Laravel sudah ditest)
- Third-party packages
- Getter/setter sederhana
- Private methods (test lewat public interface)

### 3. Coverage Target

```bash
# Target coverage per jenis test
Unit Tests:     80-100%
Feature Tests:  70-90%
Browser Tests:  Critical flows only

# Check coverage
./vendor/bin/pest --coverage
```

### 4. Test Organization

```
tests/
├── Unit/                   # Isolated unit tests
│   ├── Services/
│   ├── Helpers/
│   └── Models/
│
├── Feature/                # Integration tests
│   ├── Api/
│   ├── Admin/
│   └── Public/
│
└── Browser/                # E2E tests
    ├── Auth/
    ├── Admin/
    └── CriticalFlows/
```

---

## Dokumentasi Test

### 1. Naming Convention

```php
// ✅ Good - Descriptive dan menggunakan bahasa Indonesia
test('user dapat login dengan kredensial yang valid')
test('sistem menolak login dengan password salah')
test('admin dapat approve artikel yang pending')

// ❌ Bad - Tidak jelas
test('test login')
test('test 1')
test('works')
```

### 2. Organize dengan describe()

```php
describe('Artikel Management', function () {
    describe('Create Artikel', function () {
        test('admin dapat membuat artikel baru');
        test('operator tidak dapat membuat artikel');
        test('validasi required fields');
    });

    describe('Update Artikel', function () {
        test('author dapat update artikelnya sendiri');
        test('author tidak dapat update artikel orang lain');
    });

    describe('Delete Artikel', function () {
        test('admin dapat delete artikel apapun');
        test('author hanya dapat delete artikelnya sendiri');
    });
});
```

### 3. Comment untuk Test Complex

```php
test('menghitung total anggaran dengan benar', function () {
    // Given: 3 desa dengan anggaran berbeda
    DataDesa::factory()->create(['anggaran' => 100_000_000]);
    DataDesa::factory()->create(['anggaran' => 150_000_000]);
    DataDesa::factory()->create(['anggaran' => 200_000_000]);

    // When: Menghitung total anggaran kecamatan
    $total = app(AnggaranService::class)->calculateTotal();

    // Then: Total sesuai dengan penjumlahan
    expect($total)->toBe(450_000_000);
})->group('anggaran', 'calculation');
```

### 4. Group dengan Konsisten

```php
// Format: ->group('module', 'action', 'type')

test('...')->group('artikel', 'create', 'validation');
test('...')->group('artikel', 'update', 'authorization');
test('...')->group('datadesa', 'export', 'integration');
test('...')->group('auth', 'login', 'security');
```

---

## Troubleshooting Common Issues

### Issue 1: Database Transactions Tidak Rollback

**Problem:**
```php
test('create artikel', function () {
    Artikel::create([...]); // Data tidak di-rollback
});
```

**Solution:**
```php
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

test('create artikel', function () {
    Artikel::create([...]); // Data akan di-rollback otomatis
});
```

### Issue 2: Test Bergantung pada Test Lain

**Problem:**
```php
test('create user', function () {
    $this->user = User::create([...]);
});

test('update user', function () {
    $this->user->update([...]); // Error: $this->user tidak ada
});
```

**Solution:**
```php
test('create user', function () {
    $user = User::factory()->create();
    expect($user)->toBeInstanceOf(User::class);
});

test('update user', function () {
    $user = User::factory()->create(); // Setup sendiri
    $user->update(['name' => 'Updated']);
    expect($user->fresh()->name)->toBe('Updated');
});
```

### Issue 3: Test Lambat

**Problem:**
Test berjalan sangat lambat (> 1 menit).

**Solution:**
```bash
# 1. Gunakan parallel testing
./vendor/bin/pest --parallel

# 2. Skip browser tests untuk development
./vendor/bin/pest --exclude-group=browser

# 3. Gunakan SQLite in-memory
# .env.testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:

# 4. Profile untuk cari test yang lambat
./vendor/bin/pest --profile --top=10
```

### Issue 4: Factory Relationship Error

**Problem:**
```php
$artikel = Artikel::factory()->create();
// Error: kategori_id required
```

**Solution:**
```php
// Option 1: Create dengan relationship
$artikel = Artikel::factory()->create([
    'id_kategori' => ArtikelKategori::factory()
]);

// Option 2: Setup beforeEach
beforeEach(function () {
    $this->kategori = ArtikelKategori::factory()->create();
});

test('create artikel', function () {
    $artikel = Artikel::factory()->create([
        'id_kategori' => $this->kategori->id
    ]);
});
```

### Issue 5: Authentication di Browser Test

**Problem:**
```php
test('akses dashboard', function () {
    $this->browse(function ($browser) {
        $browser->visit('/dashboard'); // Redirect ke login
    });
});
```

**Solution:**
```php
test('akses dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->browse(function ($browser) {
        $browser->visit('/dashboard')
            ->assertSee('Dashboard');
    });
});
```

### Issue 6: File Upload di Test

**Problem:**
File upload tidak berfungsi di test.

**Solution:**
```php
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('upload gambar', function () {
    Storage::fake('public');
    $file = UploadedFile::fake()->image('test.jpg', 1920, 1080);

    $response = $this->post('/upload', ['image' => $file]);

    $response->assertStatus(200);
    Storage::disk('public')->assertExists('images/' . $file->hashName());
});
```

---

## Cheat Sheet Commands

```bash
# Run all tests
./vendor/bin/pest

# Run specific directory
./vendor/bin/pest tests/Feature
./vendor/bin/pest tests/Unit

# Run with filter
./vendor/bin/pest --filter="artikel"

# Run by group
./vendor/bin/pest --group=artikel
./vendor/bin/pest --exclude-group=browser

# Parallel execution
./vendor/bin/pest --parallel

# With coverage
./vendor/bin/pest --coverage --min=80

# Profile slow tests
./vendor/bin/pest --profile

# Stop on first failure
./vendor/bin/pest --stop-on-failure

# Watch mode
./vendor/bin/pest --watch

# CI mode
./vendor/bin/pest --ci --parallel
```

---

## Resources

### Internal Documentation
- [TESTING_DENGAN_PEST4.md](./TESTING_DENGAN_PEST4.md) - Dokumentasi lengkap Pest 4
- [PEST_CHEATSHEET.md](./PEST_CHEATSHEET.md) - Quick reference
- [CONTOH_TESTING_OPENDK.md](./CONTOH_TESTING_OPENDK.md) - Contoh-contoh praktis

### External Resources
- [Pest Documentation](https://pestphp.com)
- [Laravel Testing](https://laravel.com/docs/testing)
- [PHPUnit Documentation](https://phpunit.de)

---

## Team Agreement

### Testing Guidelines untuk Tim OpenDK:

1. **Setiap fitur baru harus ada test**
   - Minimal happy path dan validation

2. **Bug fix harus disertai test**
   - Test yang mereproduksi bug

3. **PR tidak akan di-merge jika:**
   - Ada test yang gagal
   - Coverage < 70%
   - Code style tidak sesuai

4. **Review PR:**
   - Reviewer wajib check test
   - Diskusikan jika test kurang memadai

5. **Run test sebelum commit:**
   ```bash
   ./vendor/bin/pest && ./vendor/bin/pint
   ```

---

**Maintained by:** Tim Pengembang OpenDK  
**Last Updated:** November 2025  
**Version:** 1.0
