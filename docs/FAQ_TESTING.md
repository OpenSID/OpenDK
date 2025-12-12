# FAQ - Testing dengan Pest 4

Frequently Asked Questions (Pertanyaan yang Sering Diajukan) tentang testing dengan Pest 4 di OpenDK.

## 📋 Daftar Isi

- [Umum](#umum)
- [Setup & Installation](#setup--installation)
- [Menulis Test](#menulis-test)
- [Menjalankan Test](#menjalankan-test)
- [Database & Models](#database--models)
- [HTTP & API Testing](#http--api-testing)
- [Browser Testing](#browser-testing)
- [Debugging](#debugging)
- [Performance](#performance)
- [Best Practices](#best-practices)

---

## Umum

### Q: Apa itu Pest dan kenapa kita pakai Pest dibanding PHPUnit?

**A:** Pest adalah testing framework yang dibangun di atas PHPUnit dengan syntax yang lebih modern dan elegan. Alasan pakai Pest:

1. **Syntax lebih simple:**
   ```php
   // PHPUnit
   public function testUserCanLogin() {
       $this->assertTrue(true);
   }
   
   // Pest
   test('user dapat login', function () {
       expect(true)->toBeTrue();
   });
   ```

2. **Lebih readable** - Code test lebih mudah dibaca dan dipahami
3. **Less boilerplate** - Tidak perlu banyak setup code
4. **Better expectations** - API expectations lebih ekspresif
5. **Built for Laravel** - Plugin Laravel integration yang sempurna

### Q: Apakah Pest 4 backward compatible dengan PHPUnit?

**A:** Ya! Pest dibangun di atas PHPUnit, jadi:
- Semua assertion PHPUnit tetap bisa dipakai
- Existing PHPUnit tests bisa jalan bersama Pest tests
- Bisa gradual migration dari PHPUnit ke Pest

```php
// PHPUnit style (masih bisa)
$this->assertEquals(10, $result);

// Pest style (recommended)
expect($result)->toBe(10);
```

### Q: Apa bedanya Unit, Feature, dan Browser testing?

**A:**

| Type | Scope | Speed | When to Use |
|------|-------|-------|-------------|
| **Unit** | Single method/function | Fast | Test business logic, services, helpers |
| **Feature** | Multiple components | Medium | Test API, controllers, integration |
| **Browser** | Full application flow | Slow | Test critical user flows, E2E |

```php
// Unit: Test satu function
test('calculateTotal menghitung dengan benar', function () {
    expect(calculateTotal([10, 20, 30]))->toBe(60);
});

// Feature: Test endpoint
test('API artikel mengembalikan list', function () {
    $response = $this->getJson('/api/artikel');
    $response->assertStatus(200);
});

// Browser: Test user flow
test('user dapat login dan akses dashboard', function () {
    $this->browse(function ($browser) {
        $browser->visit('/login')
            ->type('email', 'user@test.com')
            ->press('Login')
            ->assertPathIs('/dashboard');
    });
});
```

---

## Setup & Installation

### Q: Bagaimana cara setup testing environment?

**A:** Follow langkah ini:

1. **Copy .env untuk testing:**
   ```bash
   cp .env .env.testing
   ```

2. **Edit .env.testing:**
   ```env
   APP_ENV=testing
   DB_CONNECTION=mysql
   DB_DATABASE=opendk_testing
   CACHE_DRIVER=array
   SESSION_DRIVER=array
   MAIL_MAILER=array
   ```

3. **Buat database testing:**
   ```bash
   mysql -u root -e "CREATE DATABASE opendk_testing"
   ```

4. **Jalankan migration:**
   ```bash
   php artisan migrate --env=testing
   ```

5. **Test setup:**
   ```bash
   ./vendor/bin/pest
   ```

### Q: Error "Pest not found" saat jalankan test?

**A:** Pastikan Pest sudah terinstall:

```bash
# Check apakah Pest ada
composer show pestphp/pest

# Jika belum ada, install
composer require --dev pestphp/pest:^4.0

# Verify
./vendor/bin/pest --version
```

### Q: Bisa pakai SQLite in-memory untuk testing?

**A:** Ya, bahkan recommended untuk speed! Edit `.env.testing`:

```env
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

Keuntungan:
- ✅ Lebih cepat (in-memory)
- ✅ Tidak perlu setup database terpisah
- ✅ Auto cleanup setiap test

Kekurangan:
- ❌ Beberapa MySQL specific features mungkin berbeda
- ❌ Tidak cocok untuk test complex queries

---

## Menulis Test

### Q: test() vs it() - Mana yang harus dipakai?

**A:** Keduanya sama, pilih yang lebih natural:

```php
// Gunakan test() untuk statement
test('user dapat login', function () {
    // ...
});

// Gunakan it() untuk behavior
it('mengembalikan error jika email invalid', function () {
    // ...
});

// Pilih satu style untuk konsistensi (recommended: test())
```

### Q: Bagaimana cara test private/protected methods?

**A:** **Jangan test private methods secara langsung!** Test lewat public interface:

```php
// ❌ Bad - Test private method
test('private method works', function () {
    $reflection = new ReflectionClass(MyClass::class);
    $method = $reflection->getMethod('privateMethod');
    $method->setAccessible(true);
    // ...
});

// ✅ Good - Test via public method
test('public method menggunakan private method dengan benar', function () {
    $obj = new MyClass();
    $result = $obj->publicMethod(); // Internally calls private method
    expect($result)->toBe('expected');
});
```

**Prinsip:** Jika private method penting, mungkin seharusnya public atau terpisah.

### Q: Bagaimana struktur test yang baik?

**A:** Gunakan **AAA Pattern** (Arrange-Act-Assert):

```php
test('user dapat membuat artikel', function () {
    // Arrange - Setup data
    $user = User::factory()->create();
    $kategori = ArtikelKategori::factory()->create();
    $data = [
        'judul' => 'Test Artikel',
        'id_kategori' => $kategori->id,
        'isi' => 'Konten artikel'
    ];
    
    // Act - Eksekusi action
    $response = $this->actingAs($user)
        ->post('/artikel', $data);
    
    // Assert - Verifikasi hasil
    $response->assertStatus(201);
    $this->assertDatabaseHas('artikel', [
        'judul' => 'Test Artikel'
    ]);
});
```

### Q: Bagaimana cara test method yang melempar Exception?

**A:** Gunakan `throws()`:

```php
test('melempar exception jika data tidak valid', function () {
    $service = new ArtikelService();
    
    $service->create([]); // Data kosong
})->throws(ValidationException::class);

// Atau dengan message
test('exception dengan message spesifik', function () {
    $service = new ArtikelService();
    
    $service->create([]);
})->throws(ValidationException::class, 'Judul tidak boleh kosong');
```

---

## Menjalankan Test

### Q: Bagaimana cara jalankan test spesifik?

**A:** Ada beberapa cara:

```bash
# 1. Jalankan file spesifik
./vendor/bin/pest tests/Feature/ArtikelTest.php

# 2. Jalankan dengan filter nama
./vendor/bin/pest --filter="user dapat login"

# 3. Jalankan berdasarkan group
./vendor/bin/pest --group=artikel

# 4. Jalankan directory
./vendor/bin/pest tests/Unit
```

### Q: Test berjalan lambat, bagaimana mempercepat?

**A:** Beberapa cara:

```bash
# 1. Parallel execution
./vendor/bin/pest --parallel

# 2. Skip browser tests
./vendor/bin/pest --exclude-group=browser

# 3. Gunakan SQLite in-memory
# Edit .env.testing:
DB_CONNECTION=sqlite
DB_DATABASE=:memory:

# 4. Profile untuk cari test lambat
./vendor/bin/pest --profile --top=10

# 5. Run only changed tests
./vendor/bin/pest --dirty
```

### Q: Apa arti output warna di Pest?

**A:**
- 🟢 **Green dot (.)** - Test passed
- 🔴 **Red F** - Test failed
- 🟡 **Yellow S** - Test skipped
- ⚠️ **Yellow I** - Test incomplete
- 🟣 **Purple T** - Test todo

### Q: Bagaimana cara debug test yang gagal?

**A:**

```bash
# 1. Verbose output
./vendor/bin/pest -v

# 2. Stop pada failure pertama
./vendor/bin/pest --stop-on-failure

# 3. Tambahkan dump/dd di test
test('debug', function () {
    $user = User::first();
    dump($user);  // Print and continue
    dd($user);    // Print and die
});

# 4. Gunakan Ray (jika terinstall)
test('debug dengan ray', function () {
    $user = User::first();
    ray($user);
});
```

---

## Database & Models

### Q: Data test tidak ter-rollback setelah test selesai?

**A:** Pastikan menggunakan `DatabaseTransactions` trait:

```php
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

test('create artikel', function () {
    Artikel::create([...]); // Akan di-rollback otomatis
});
```

Atau di `tests/Pest.php` untuk apply ke semua test:

```php
pest()->extend(Tests\TestCase::class)
    ->use(DatabaseTransactions::class)
    ->in('Feature');
```

### Q: Kapan pakai factory()->create() vs factory()->make()?

**A:**

```php
// create() - Saves to database
$user = User::factory()->create();
expect($user->exists)->toBeTrue(); // ✅ True
$this->assertDatabaseHas('users', ['id' => $user->id]); // ✅ Pass

// make() - Doesn't save to database
$user = User::factory()->make();
expect($user->exists)->toBeFalse(); // ✅ True
$this->assertDatabaseHas('users', ['id' => $user->id]); // ❌ Fail

// Gunakan make() jika tidak perlu data di database
// Gunakan create() jika perlu data tersimpan
```

### Q: Bagaimana cara test dengan relationship?

**A:**

```php
// Factory dengan relationship
test('artikel memiliki kategori', function () {
    $artikel = Artikel::factory()
        ->for(ArtikelKategori::factory())
        ->create();
    
    expect($artikel->kategori)->toBeInstanceOf(ArtikelKategori::class);
});

// Has many relationship
test('user memiliki banyak artikel', function () {
    $user = User::factory()
        ->has(Artikel::factory()->count(3))
        ->create();
    
    expect($user->artikel)->toHaveCount(3);
});

// Manual setup
test('artikel belong to kategori', function () {
    $kategori = ArtikelKategori::factory()->create();
    $artikel = Artikel::factory()->create([
        'id_kategori' => $kategori->id
    ]);
    
    expect($artikel->kategori->id)->toBe($kategori->id);
});
```

### Q: Bagaimana cara test soft deletes?

**A:**

```php
test('artikel dapat di-soft delete', function () {
    $artikel = Artikel::factory()->create();
    
    $artikel->delete();
    
    // Check soft deleted
    expect($artikel->trashed())->toBeTrue();
    
    // Assert in database
    $this->assertSoftDeleted('artikel', [
        'id' => $artikel->id
    ]);
    
    // Data masih ada dengan deleted_at
    $this->assertDatabaseHas('artikel', [
        'id' => $artikel->id
    ]);
});

test('dapat restore artikel yang di-soft delete', function () {
    $artikel = Artikel::factory()->create();
    $artikel->delete();
    
    $artikel->restore();
    
    expect($artikel->trashed())->toBeFalse();
    $this->assertNotSoftDeleted('artikel', [
        'id' => $artikel->id
    ]);
});
```

---

## HTTP & API Testing

### Q: Apa bedanya get() vs getJson()?

**A:**

```php
// get() - Regular HTTP request
$response = $this->get('/artikel');
$response->assertStatus(200)
    ->assertViewIs('artikel.index');  // Expect HTML response

// getJson() - JSON API request
$response = $this->getJson('/api/artikel');
$response->assertStatus(200)
    ->assertJson(['data' => [...]]);  // Expect JSON response

// getJson() otomatis set header:
// Accept: application/json
// Content-Type: application/json
```

### Q: Bagaimana cara test dengan authentication?

**A:**

```php
// 1. Menggunakan actingAs()
test('authenticated user dapat akses dashboard', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)
        ->get('/dashboard');
    
    $response->assertStatus(200);
});

// 2. Dengan guard spesifik
test('admin dapat akses admin panel', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    
    $response = $this->actingAs($admin, 'admin')
        ->get('/admin/dashboard');
    
    $response->assertStatus(200);
});

// 3. API dengan Bearer token
test('API dengan token', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    
    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->getJson('/api/user');
    
    $response->assertStatus(200);
});
```

### Q: Bagaimana cara test API pagination?

**A:**

```php
test('API pagination berfungsi dengan benar', function () {
    Artikel::factory()->count(25)->create();
    
    $response = $this->getJson('/api/artikel?page=2&per_page=10');
    
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data',
            'meta' => [
                'pagination' => [
                    'total',
                    'per_page',
                    'current_page',
                    'last_page'
                ]
            ],
            'links'
        ])
        ->assertJsonPath('meta.pagination.current_page', 2)
        ->assertJsonPath('meta.pagination.per_page', 10)
        ->assertJsonCount(10, 'data');
});
```

### Q: Bagaimana cara test validation errors?

**A:**

```php
test('validasi required fields', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)
        ->postJson('/api/artikel', []);
    
    $response->assertStatus(422)  // Unprocessable Entity
        ->assertJsonValidationErrors(['judul', 'isi', 'id_kategori']);
});

test('validasi error message spesifik', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)
        ->postJson('/api/artikel', [
            'judul' => ''  // Empty
        ]);
    
    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'judul' => 'Judul tidak boleh kosong'
        ]);
});
```

---

## Browser Testing

### Q: Bagaimana cara setup browser testing?

**A:**

1. **Install Playwright:**
   ```bash
   npm install
   npx playwright install
   ```

2. **Konfigurasi di tests/Pest.php:**
   ```php
   pest()->extend(Tests\TestCase::class)
       ->in('Browser')
       ->beforeEach(function () {
           config(['app.url' => env('APP_URL', 'http://opendk.test')]);
       });
   ```

3. **Jalankan browser test:**
   ```bash
   ./vendor/bin/pest --group=browser
   ```

### Q: Browser test error "Page not found"?

**A:** Pastikan:

1. **Development server berjalan:**
   ```bash
   php artisan serve
   # Atau
   laragon start
   ```

2. **APP_URL configured:**
   ```php
   // .env.testing
   APP_URL=http://opendk.test
   ```

3. **Check di test:**
   ```php
   test('check url', function () {
       $this->browse(function ($browser) {
           $browser->visit('/')
               ->dump();  // Debug current page
       });
   });
   ```

### Q: Bagaimana cara screenshot pada failure?

**A:**

```php
test('screenshot on failure', function () {
    $this->browse(function ($browser) {
        $browser->visit('/login')
            ->screenshot('before-login')  // Manual screenshot
            ->type('email', 'test@example.com')
            ->press('Login')
            ->screenshot('after-login');  // Manual screenshot
    });
});

// Screenshot otomatis pada failure
// Configure di playwright.config.js:
{
    screenshot: 'only-on-failure',
    video: 'retain-on-failure'
}
```

---

## Debugging

### Q: Test failed tapi tidak tahu kenapa?

**A:** Debugging steps:

```bash
# 1. Verbose output
./vendor/bin/pest -v

# 2. Tambah dump di test
test('debug', function () {
    $user = User::first();
    dump($user);  // Continue after dump
    
    $response = $this->get('/artikel');
    dump($response->content());  // Dump HTML response
});

# 3. Atau dd untuk stop
test('debug', function () {
    $user = User::first();
    dd($user);  // Dump and die
});

# 4. Check database state
test('debug database', function () {
    dd(Artikel::all());  // Dump all records
    dd(DB::table('artikel')->get());  // Raw query
});
```

### Q: Bagaimana cara debug query SQL?

**A:**

```php
use Illuminate\Support\Facades\DB;

test('debug SQL queries', function () {
    // Enable query log
    DB::enableQueryLog();
    
    // Run code yang mau di-debug
    $artikel = Artikel::with('kategori')->first();
    
    // Dump queries
    dd(DB::getQueryLog());
});

// Output:
// [
//     ["query" => "select * from artikel where id = ?", "bindings" => [1], "time" => 0.5],
//     ["query" => "select * from kategori where id = ?", "bindings" => [1], "time" => 0.3]
// ]
```

### Q: Bagaimana cara test dengan breakpoint?

**A:**

```php
// 1. Install Xdebug

// 2. Tambahkan breakpoint di code atau test
test('debug dengan xdebug', function () {
    $user = User::first();
    
    xdebug_break();  // Breakpoint manual
    
    $response = $this->get('/artikel');
});

// 3. Jalankan dengan debug
./vendor/bin/pest --filter="debug dengan xdebug"

// 4. Attach debugger di VS Code/PHPStorm
```

---

## Performance

### Q: Bagaimana cara optimize test speed?

**A:**

1. **Gunakan SQLite in-memory:**
   ```env
   # .env.testing
   DB_CONNECTION=sqlite
   DB_DATABASE=:memory:
   ```

2. **Parallel testing:**
   ```bash
   ./vendor/bin/pest --parallel --processes=4
   ```

3. **Skip slow tests saat development:**
   ```php
   test('slow integration test', function () {
       // ...
   })->group('slow');
   
   // Skip dengan:
   ./vendor/bin/pest --exclude-group=slow
   ```

4. **Gunakan factory state untuk reduce queries:**
   ```php
   // ❌ Slow - Multiple queries
   $users = User::factory()->count(10)->create();
   foreach ($users as $user) {
       $user->artikel()->create([...]);
   }
   
   // ✅ Fast - Bulk insert
   $users = User::factory()
       ->count(10)
       ->has(Artikel::factory()->count(3))
       ->create();
   ```

### Q: Test coverage menurunkan performance?

**A:** Ya, coverage analysis memang lambat. Solusi:

```bash
# Development (tanpa coverage)
./vendor/bin/pest

# Pre-commit (dengan coverage)
./vendor/bin/pest --coverage --min=70

# CI (coverage lengkap)
./vendor/bin/pest --coverage --coverage-html=coverage
```

---

## Best Practices

### Q: Berapa banyak assertion dalam satu test?

**A:** **Idealnya satu konsep, tapi bisa multiple assertions:**

```php
// ✅ Good - Test satu konsep dengan multiple assertions
test('user registration membuat user baru', function () {
    $response = $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password'
    ]);
    
    // Multiple assertions untuk satu konsep
    $response->assertStatus(201);
    $response->assertRedirect('/dashboard');
    $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    expect(User::count())->toBe(1);
});

// ❌ Bad - Test multiple konsep
test('user management', function () {
    // Create
    $user = User::factory()->create();
    
    // Update
    $user->update(['name' => 'Updated']);
    
    // Delete
    $user->delete();
    
    // Multiple concepts dalam satu test
});
```

### Q: Kapan harus pakai mock/fake?

**A:**

**Gunakan mock/fake untuk external services:**

```php
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Queue;

test('mail fake untuk test email', function () {
    Mail::fake();  // Don't send real emails
    
    $user = User::factory()->create();
    $user->sendWelcomeEmail();
    
    Mail::assertSent(WelcomeMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

test('storage fake untuk test upload', function () {
    Storage::fake('public');
    
    $file = UploadedFile::fake()->image('test.jpg');
    $response = $this->post('/upload', ['image' => $file]);
    
    Storage::disk('public')->assertExists('images/' . $file->hashName());
});
```

**Jangan mock internal classes (test real implementation):**

```php
// ❌ Bad - Mock internal service
test('artikel service', function () {
    $mock = Mockery::mock(ArtikelService::class);
    $mock->shouldReceive('create')->andReturn(true);
    
    // Testing mock, bukan implementation
});

// ✅ Good - Test real implementation
test('artikel service', function () {
    $service = new ArtikelService();
    $result = $service->create([...]);
    
    expect($result)->toBeTrue();
});
```

### Q: Bagaimana naming convention untuk test?

**A:**

```php
// ✅ Good - Descriptive dan dalam Bahasa Indonesia
test('user dapat login dengan kredensial yang valid')
test('sistem menolak login dengan password salah')
test('admin dapat menghapus artikel milik user lain')
test('slug artikel di-generate otomatis dari judul')

// ❌ Bad - Tidak jelas
test('test login')
test('test 1')
test('works')
test('artikel')
```

**Format recommended:**
- `[Subject] dapat/tidak dapat [Action]` - untuk permission
- `[Subject] [Action] dengan [Condition]` - untuk behavior
- `[System] [Action] ketika [Condition]` - untuk system behavior

### Q: Haruskah test private methods?

**A:** **Tidak! Test lewat public interface:**

**Alasan:**
1. Private methods adalah implementation detail
2. Test seharusnya test behavior, bukan implementation
3. Jika private method penting, mungkin seharusnya public atau class terpisah

```php
class ArtikelService
{
    public function create(array $data): Artikel
    {
        $slug = $this->generateSlug($data['judul']);
        // ...
    }
    
    private function generateSlug(string $judul): string
    {
        return Str::slug($judul);
    }
}

// ❌ Bad - Test private method
test('generateSlug works', function () {
    // Reflection magic to access private method
});

// ✅ Good - Test via public method
test('create artikel generates slug dari judul', function () {
    $service = new ArtikelService();
    $artikel = $service->create(['judul' => 'Test Artikel']);
    
    expect($artikel->slug)->toBe('test-artikel');
});
```

---

## Troubleshooting Umum

### Q: Class 'Tests\TestCase' not found

**A:** Pastikan autoload configured:

```bash
# Regenerate autoload
composer dump-autoload

# Verify TestCase exists
cat tests/TestCase.php

# Clear cache
php artisan config:clear
php artisan cache:clear
```

### Q: Memory limit exceeded

**A:**

```bash
# 1. Increase memory limit
php -d memory_limit=512M ./vendor/bin/pest

# 2. Atau di php.ini
memory_limit = 512M

# 3. Optimize test (use DatabaseTransactions, cleanup)
```

### Q: Too many connections error

**A:**

```php
// Di tests/Pest.php
afterEach(function () {
    DB::disconnect();  // Close connection after each test
});

// Atau gunakan SQLite
// .env.testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

---

## Resources

### Dokumentasi Internal
- [TESTING_DENGAN_PEST4.md](./TESTING_DENGAN_PEST4.md)
- [PEST_CHEATSHEET.md](./PEST_CHEATSHEET.md)
- [CONTOH_TESTING_OPENDK.md](./CONTOH_TESTING_OPENDK.md)
- [WORKFLOW_TESTING.md](./WORKFLOW_TESTING.md)

### External Resources
- [Pest Documentation](https://pestphp.com)
- [Laravel Testing Documentation](https://laravel.com/docs/testing)
- [PHPUnit Documentation](https://phpunit.de)

---

**Punya pertanyaan lain?** 
- Buat issue di GitHub
- Diskusi dengan tim
- Update FAQ ini

**Last Updated:** November 2025  
**Version:** 1.0
