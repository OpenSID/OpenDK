# Pest 4 Cheatsheet - Quick Reference

## 📋 Daftar Isi
- [Test Syntax](#test-syntax)
- [Expectations](#expectations)
- [Laravel Specific](#laravel-specific)
- [Database](#database)
- [HTTP Testing](#http-testing)
- [Authentication](#authentication)
- [Hooks](#hooks)
- [Groups & Filters](#groups--filters)
- [Commands](#commands)

---

## Test Syntax

### Membuat Test

```php
// Basic test
test('deskripsi test', function () {
    expect(true)->toBeTrue();
});

// Alternative syntax
it('dapat melakukan sesuatu', function () {
    expect(1 + 1)->toBe(2);
});

// Test dengan dataset
test('validasi input', function ($input, $expected) {
    expect(validateInput($input))->toBe($expected);
})->with([
    ['valid', true],
    ['invalid', false],
]);
```

### Skip & Only

```php
// Skip test
test('skip test ini')->skip();
test('skip dengan alasan')->skip('Fitur belum selesai');
test('skip kondisional')->skip(fn() => !extension_loaded('redis'));

// Only (hanya jalankan test ini)
test('fokus ke test ini')->only();

// Todo
test('implementasi nanti')->todo();
```

---

## Expectations

### Boolean & Null

```php
expect($value)->toBeTrue();
expect($value)->toBeFalse();
expect($value)->toBeNull();
expect($value)->not->toBeNull();
expect($value)->toBeTruthy();    // truthy value
expect($value)->toBeFalsy();     // falsy value
```

### Types

```php
expect($value)->toBeString();
expect($value)->toBeInt();
expect($value)->toBeFloat();
expect($value)->toBeBool();
expect($value)->toBeArray();
expect($value)->toBeObject();
expect($value)->toBeResource();
expect($value)->toBeCallable();
expect($value)->toBeIterable();
expect($value)->toBeNumeric();
expect($value)->toBeScalar();
```

### Comparison

```php
expect($value)->toBe(10);              // ===
expect($value)->toEqual($expected);    // ==
expect($value)->toBeGreaterThan(5);
expect($value)->toBeGreaterThanOrEqual(5);
expect($value)->toBeLessThan(20);
expect($value)->toBeLessThanOrEqual(20);
expect($value)->toBeIn([1, 2, 3]);
expect($value)->toBeBetween(1, 10);
```

### String

```php
expect($string)->toContain('substring');
expect($string)->toStartWith('prefix');
expect($string)->toEndWith('suffix');
expect($string)->toMatch('/regex/');
expect($string)->toBeJson();
expect($string)->toBeEmpty();
expect($string)->not->toBeEmpty();
```

### Array/Collection

```php
expect($array)->toHaveCount(5);
expect($array)->toBeEmpty();
expect($array)->toContain('item');
expect($array)->toHaveKey('key');
expect($array)->toHaveKeys(['key1', 'key2']);
expect($array)->each->toBeInt();        // setiap item adalah int
expect($array)->sequence(                // validasi sequence
    fn($item) => $item->toBeString(),
    fn($item) => $item->toBeInt(),
);
```

### Objects

```php
expect($object)->toBeInstanceOf(User::class);
expect($object)->toHaveProperty('name');
expect($object)->toHaveProperties(['name', 'email']);
expect($object)->toHaveMethod('getName');
expect($object)->toHaveMethods(['getName', 'getEmail']);
```

### Chaining

```php
expect($user)
    ->toBeInstanceOf(User::class)
    ->and($user->email)->toBeString()
    ->and($user->id)->toBeInt()
    ->and($user->active)->toBeTrue();
```

---

## Laravel Specific

### Model

```php
use App\Models\User;

expect(User::first())
    ->toBeInstanceOf(User::class)
    ->and($user->name)->toBeString();

// Model events
expect(User::factory()->create())
    ->toHaveDispatchedEvent('user.created');
```

### Eloquent

```php
$user = User::factory()->create();

expect($user->exists)->toBeTrue();
expect($user->wasRecentlyCreated)->toBeTrue();
expect($user)->toBeModel();

// Relationships
expect($user->articles)->toHaveCount(5);
expect($user->articles->first())->toBeInstanceOf(Article::class);
```

### Collections

```php
$users = User::all();

expect($users)
    ->toBeInstanceOf(Collection::class)
    ->toHaveCount(10)
    ->first()->toBeInstanceOf(User::class);

expect($users->pluck('id'))
    ->each->toBeInt();
```

---

## Database

### Traits

```php
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Auto rollback (recommended)
uses(DatabaseTransactions::class);

// Fresh database setiap test
uses(RefreshDatabase::class);
```

### Assertions

```php
// Check data exists
$this->assertDatabaseHas('users', [
    'email' => 'test@example.com'
]);

// Check data not exists
$this->assertDatabaseMissing('users', [
    'email' => 'deleted@example.com'
]);

// Check count
$this->assertDatabaseCount('users', 5);

// Soft deletes
$this->assertSoftDeleted('users', [
    'id' => $user->id
]);

// Not soft deleted
$this->assertNotSoftDeleted('users', [
    'id' => $user->id
]);
```

### Factories

```php
// Create single model
$user = User::factory()->create();

// Create with attributes
$user = User::factory()->create([
    'name' => 'John Doe',
    'email' => 'john@example.com'
]);

// Create multiple
$users = User::factory()->count(10)->create();

// Create without saving
$user = User::factory()->make();

// Create with relationships
$user = User::factory()
    ->has(Article::factory()->count(3))
    ->create();

// Using state
$admin = User::factory()->admin()->create();
```

---

## HTTP Testing

### GET Requests

```php
// Regular GET
$response = $this->get('/artikel');
$response->assertStatus(200);

// JSON GET
$response = $this->getJson('/api/artikel');
$response->assertStatus(200)
    ->assertJsonStructure(['data']);
```

### POST Requests

```php
$response = $this->post('/artikel', [
    'judul' => 'Test Artikel',
    'isi' => 'Konten artikel'
]);

$response->assertStatus(201)
    ->assertRedirect('/artikel');

// JSON POST
$response = $this->postJson('/api/artikel', $data);
$response->assertStatus(201)
    ->assertJson(['success' => true]);
```

### PUT/PATCH/DELETE

```php
// PUT
$response = $this->put("/artikel/{$id}", $data);

// PATCH
$response = $this->patch("/artikel/{$id}", $data);

// DELETE
$response = $this->delete("/artikel/{$id}");
$response->assertStatus(204);
```

### Response Assertions

```php
$response = $this->get('/artikel');

// Status
$response->assertStatus(200);
$response->assertOk();                    // 200
$response->assertCreated();               // 201
$response->assertNoContent();             // 204
$response->assertNotFound();              // 404
$response->assertForbidden();             // 403
$response->assertUnauthorized();          // 401

// Redirects
$response->assertRedirect('/dashboard');
$response->assertRedirectToRoute('home');

// View
$response->assertViewIs('artikel.index');
$response->assertViewHas('artikel');
$response->assertViewHasAll(['artikel', 'categories']);

// Content
$response->assertSee('Text Content');
$response->assertSeeText('Plain Text');
$response->assertDontSee('Hidden Content');

// JSON
$response->assertJson(['success' => true]);
$response->assertJsonStructure(['data', 'meta']);
$response->assertJsonFragment(['id' => 1]);
$response->assertJsonPath('data.id', 1);
$response->assertJsonCount(10, 'data');

// Headers
$response->assertHeader('Content-Type', 'application/json');
$response->assertHeaderMissing('X-Custom-Header');

// Cookies
$response->assertCookie('name', 'value');
$response->assertCookieExpired('name');
$response->assertCookieMissing('name');

// Session
$response->assertSessionHas('key', 'value');
$response->assertSessionMissing('key');
$response->assertSessionHasErrors(['field']);
```

---

## Authentication

### Acting as User

```php
use App\Models\User;

test('user terautentikasi dapat akses dashboard', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->get('/dashboard');
    
    $response->assertStatus(200);
});

// Dengan guard spesifik
$response = $this->actingAs($user, 'api')->get('/api/user');
```

### Guest

```php
test('guest tidak dapat akses dashboard', function () {
    $response = $this->get('/dashboard');
    
    $response->assertRedirect('/login');
});
```

### API Authentication

```php
test('API dengan bearer token', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    
    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->getJson('/api/user');
        
    $response->assertStatus(200);
});
```

---

## Hooks

### beforeEach & afterEach

```php
beforeEach(function () {
    // Setup sebelum setiap test
    $this->user = User::factory()->create();
});

afterEach(function () {
    // Cleanup setelah setiap test
    Cache::flush();
});

test('example', function () {
    expect($this->user)->toBeInstanceOf(User::class);
});
```

### beforeAll & afterAll

```php
beforeAll(function () {
    // Setup sekali sebelum semua test di file ini
    DB::statement('SET FOREIGN_KEY_CHECKS=0');
});

afterAll(function () {
    // Cleanup sekali setelah semua test selesai
    DB::statement('SET FOREIGN_KEY_CHECKS=1');
});
```

---

## Groups & Filters

### Groups

```php
// Single group
test('artikel test')->group('artikel');

// Multiple groups
test('artikel API test')->group('artikel', 'api', 'slow');

// Group di level file
uses()->group('artikel', 'api');

test('test 1', function () {
    // Inherit group dari uses()
});
```

### Run by Group

```bash
# Run specific group
./vendor/bin/pest --group=artikel

# Multiple groups
./vendor/bin/pest --group=artikel,api

# Exclude group
./vendor/bin/pest --exclude-group=slow
./vendor/bin/pest --exclude-group=browser
```

---

## Commands

### Basic

```bash
# Run all tests
./vendor/bin/pest

# Run specific directory
./vendor/bin/pest tests/Feature
./vendor/bin/pest tests/Unit

# Run specific file
./vendor/bin/pest tests/Feature/ArtikelControllerTest.php

# Filter by name
./vendor/bin/pest --filter="user dapat login"
```

### Output Options

```bash
# Verbose output
./vendor/bin/pest -v
./vendor/bin/pest --verbose

# Compact output
./vendor/bin/pest --compact

# No output (CI mode)
./vendor/bin/pest --quiet
```

### Coverage

```bash
# Show coverage
./vendor/bin/pest --coverage

# Minimum coverage threshold
./vendor/bin/pest --coverage --min=80

# HTML coverage report
./vendor/bin/pest --coverage --coverage-html=coverage

# Coverage for specific directory
./vendor/bin/pest --coverage --coverage-php=coverage/coverage.php
```

### Performance

```bash
# Parallel testing
./vendor/bin/pest --parallel

# Parallel with specific processes
./vendor/bin/pest --parallel --processes=4

# Profile slow tests
./vendor/bin/pest --profile

# Show top 10 slowest tests
./vendor/bin/pest --profile --top=10
```

### Debugging

```bash
# Stop on failure
./vendor/bin/pest --stop-on-failure

# Bail (legacy)
./vendor/bin/pest --bail

# Retry failed tests
./vendor/bin/pest --retry=2

# Order tests by defects
./vendor/bin/pest --order-by=defects
```

### Watch Mode

```bash
# Auto re-run tests on file changes
./vendor/bin/pest --watch
```

### CI Mode

```bash
# CI optimized
./vendor/bin/pest --ci

# With parallel
./vendor/bin/pest --ci --parallel
```

---

## Tips & Tricks

### Custom Expectations

```php
// Di tests/Pest.php
expect()->extend('toBeValidEmail', function () {
    return $this->toMatch('/^[^\s@]+@[^\s@]+\.[^\s@]+$/');
});

// Gunakan di test
expect('user@example.com')->toBeValidEmail();
```

### Shared Setup

```php
// Di tests/Pest.php
function createAuthenticatedUser(): User
{
    return User::factory()->create();
}

// Gunakan di test
test('example', function () {
    $user = createAuthenticatedUser();
});
```

### Environment

```php
test('cek environment testing', function () {
    expect(app()->environment())->toBe('testing');
    expect(config('app.env'))->toBe('testing');
});
```

### Debugging

```php
test('debug test', function () {
    $user = User::first();
    
    // Dump
    dump($user);
    
    // Dump and die
    dd($user);
    
    // Ray (jika terinstall)
    ray($user);
});
```

---

## Common Patterns

### CRUD Testing

```php
test('dapat membuat artikel', function () {
    $user = User::factory()->create();
    $data = ['judul' => 'Test', 'isi' => 'Content'];
    
    $response = $this->actingAs($user)->post('/artikel', $data);
    
    $response->assertStatus(201);
    $this->assertDatabaseHas('artikel', $data);
});

test('dapat membaca artikel', function () {
    $artikel = Artikel::factory()->create();
    
    $response = $this->get("/artikel/{$artikel->id}");
    
    $response->assertStatus(200)
        ->assertSee($artikel->judul);
});

test('dapat update artikel', function () {
    $user = User::factory()->create();
    $artikel = Artikel::factory()->create();
    
    $response = $this->actingAs($user)
        ->put("/artikel/{$artikel->id}", ['judul' => 'Updated']);
    
    $response->assertStatus(200);
    expect($artikel->fresh()->judul)->toBe('Updated');
});

test('dapat delete artikel', function () {
    $user = User::factory()->create();
    $artikel = Artikel::factory()->create();
    
    $response = $this->actingAs($user)
        ->delete("/artikel/{$artikel->id}");
    
    $response->assertStatus(204);
    $this->assertDatabaseMissing('artikel', ['id' => $artikel->id]);
});
```

### API Testing

```php
test('API index dengan pagination', function () {
    Artikel::factory()->count(15)->create();
    
    $response = $this->getJson('/api/artikel?page=1&per_page=10');
    
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => ['*' => ['id', 'attributes']],
            'meta' => ['pagination'],
            'links'
        ])
        ->assertJsonCount(10, 'data');
});

test('API dengan filter', function () {
    Artikel::factory()->create(['status' => 'published']);
    Artikel::factory()->create(['status' => 'draft']);
    
    $response = $this->getJson('/api/artikel?filter[status]=published');
    
    $response->assertStatus(200);
    expect($response->json('data'))->toHaveCount(1);
});
```

### Validation Testing

```php
test('validasi required field', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)
        ->postJson('/api/artikel', []);
    
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['judul', 'isi']);
});

test('validasi email format', function () {
    $response = $this->postJson('/register', [
        'email' => 'invalid-email'
    ]);
    
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});
```

---

**Quick Reference untuk OpenDK Development Team**  
**Version:** Pest 4.0  
**Last Updated:** November 2025
