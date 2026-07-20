# Integration Testing & Contract Tests

Dokumen ini menjelaskan cara menjalankan integration tests, contract tests (via OpenAPI spec), dan E2E tests secara lokal untuk OpenDK.

## Prasyarat

- PHP 8.4
- Composer
- Node.js 20+
- MySQL/MariaDB
- Docker & docker-compose (opsional, untuk E2E)

## 1. Build & Setup Lokal

```bash
# Clone & install dependencies
git clone <repo-url> && cd OpenDK
cp .env.example .env
composer install
npm install

# Setup database
php artisan key:generate
php artisan jwt:secret
touch storage/installed

# Buat database & migrate
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS opendk_testing"
php artisan migrate

# Import data testing (jika ada)
# tar -xzf database/database_test.sql.tar.gz -C database
# mysql -u root -p opendk_testing < database/database_test.sql

# Seed data awal
php artisan db:seed --class=Database\\Seeders\\DummyDataSeeder
```

## 2. Generate OpenAPI Spec

OpenAPI spec (openapi/openapi.yaml) adalah source of truth untuk contract tests.

### Prasyarat Database

Scribe perlu database yang aktif dengan data minimal agar dapat mengeksekusi response calls.
Untuk hasil maksimal, gunakan MySQL dengan data testing:

```bash
# Setup database
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS opendk_testing"
tar -xzf database/database_test.sql.tar.gz -C database
mysql -u root -p opendk_testing < database/database_test.sql

# Konfigurasi .env untuk testing
cp .env.example .env
# Edit DB_DATABASE=opendk_testing, DB_USERNAME=root, DB_PASSWORD=...
```

### Generate Spec

```bash
# Generate spec dari route definitions
php artisan scribe:generate
php artisan scribe:copy-openapi

# Atau sekali jalan via composer script:
composer generate-openapi
```

Validasi hasil generate:

```bash
composer validate-openapi
```

Contoh output sukses:

```
OpenAPI spec is valid (3.0.3)
Title: OpenDK Kecamatan API Documentation
Version: 1.0.0
Paths: 54
  POST /api/v1/auth/login - Get a JWT via given credentials.
  POST /api/v1/penduduk - Hapus Data Penduduk Sesuai OpenSID
  GET /api/frontend/v1/artikel - Display a listing of articles...
  ...
```

### Catatan: Endpoint yang Memerlukan Database Terisi

Beberapa endpoint **OpenSID data submission** (`laporan-apbdes`, `laporan-penduduk`,
`pembangunan`, `program-bantuan`) menggunakan `CekDesa` validation rule yang
melakukan query ke tabel `profil` saat instantiasi FormRequest.
Endpoint ini hanya muncul di spec jika database memiliki data profil kecamatan.
Di CI (GitHub Actions), database test sudah berisi data tersebut sehingga spec
lengkap.

### Melihat Dokumentasi

Setelah `php artisan scribe:generate`, dokumentasi dapat diakses di:

- **HTML**: http://localhost:8000/api-docs
- **OpenAPI raw**: http://localhost:8000/api-docs.openapi
- **Postman collection**: http://localhost:8000/api-docs.postman

## 3. Menjalankan Test

```bash
# Semua test (Unit + Feature)
php artisan test

# Test spesifik
php artisan test tests/Feature/Api

# Dengan coverage (jika xdebug terinstall)
php -d xdebug.mode=coverage vendor/bin/pest --coverage
```

### Environment Variables untuk Testing

Berkas `.env.testing` digunakan untuk environment testing.
Pastikan variabel berikut terisi:

```env
APP_ENV=testing
APP_URL=http://opendk.test
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=opendk_testing
DB_USERNAME=root
DB_PASSWORD=rahasia
JWT_SECRET=test_secret_key_for_testing_only
```

## 4. E2E dengan Playwright

E2E tests ada di direktori `tests/e2e/`.

```bash
# Setup
npx playwright install

# Jalankan semua E2E tests (headless)
npm run test:e2e

# Dengan UI mode
npx playwright test --ui

# Report
npx playwright show-report
```

### Docker Compose untuk E2E

```bash
# Start semua service (app, mysql, redis, mailhog)
docker compose up -d

# Setup database & seed
docker compose exec app php artisan migrate --seed

# Generate API docs
docker compose exec app composer generate-openapi

# Jalankan E2E
npx playwright test

# Stop
docker compose down
```

## 5. Contract Tests dengan OpenAPI Spec

OpenAPI spec (`openapi/openapi.yaml`) digunakan sebagai kontrak untuk API testing.

### Validasi Otomatis di CI

Setiap PR akan menjalankan:

1. **generate-openapi** -- regenerate spec dari kode
2. **validate-openapi** -- validasi YAML & struktur OpenAPI
3. **contract-check** -- verify bahwa spec up-to-date dengan kode

Lihat [.github/workflows/openapi.yml](../.github/workflows/openapi.yml).

### Cara Menambahkan Endpoint Baru ke Spec

1. Definisikan route di `routes/api-frontend.php` atau `routes/api.php`
2. Tambahkan PHPDoc annotations di controller method:

```php
/**
 * @group Artikel
 *
 * Daftar artikel publik
 *
 * @queryParam page int Page number. Example: 1
 * @queryParam per_page int Items per page. Example: 10
 * @response {
 *   "data": [{"id": 1, "judul": "..."}]
 * }
 */
public function index() { ... }
```

3. Generate ulang spec:

```bash
composer generate-openapi
```

## 6. API Key & Token Management

### Mendapatkan API Key

API key disimpan di tabel `setting_aplikasi` dengan key `api_key_opendk`.

```sql
-- Melihat API key
SELECT * FROM setting_aplikasi WHERE `key` = 'api_key_opendk';

-- Membuat API key baru (hash:sha256)
INSERT INTO setting_aplikasi (`key`, `value`, `keterangan`)
VALUES ('api_key_opendk', hash('sha256', 'my-api-key-123'), 'API Key untuk OpenSID');
```

### Rotate API Key

```bash
# Generate key baru
NEW_KEY=$(openssl rand -hex 32)
HASHED_KEY=$(echo -n "$NEW_KEY" | sha256sum | cut -d' ' -f1)

# Update di database
mysql -u root -p opendk -e "UPDATE setting_aplikasi SET value='$HASHED_KEY' WHERE \`key\`='api_key_opendk'"

# Catat key mentah (hanya muncul sekali)
echo "New API Key: $NEW_KEY"
```

### Menggunakan API Key

API Key dikirim sebagai Bearer token:

```bash
curl -H "Authorization: Bearer <api-key>" http://localhost:8000/api/v1/penduduk
```

### Scribe Auth Key untuk Response Calls

Set `SCRIBE_AUTH_KEY` di `.env` agar Scribe bisa menghasilkan contoh response
untuk endpoint yang memerlukan autentikasi:

```env
SCRIBE_AUTH_KEY=your-api-key-here
```

## 7. Debugging

```bash
# Melihat daftar route
php artisan route:list --path=api

# Melihat detail route
php artisan route:list --path=api/v1/penduduk

# Test endpoint langsung
curl -s http://localhost:8000/api/frontend/v1/artikel | jq .

# Cek log
tail -f storage/logs/laravel.log
```

## 8. CI Pipeline

Pipeline CI terdiri dari beberapa workflow:

| Workflow | File | Trigger |
|---|---|---|
| OpenAPI Validate | `.github/workflows/openapi.yml` | PR ke master/dev |
| Laravel Test | `.github/workflows/test.yml` | PR ke master/rilis-dev |
| Composer | `.github/workflows/composer.yml` | PR (jika composer.json berubah) |
| Pint | `.github/workflows/pint.yml` | PR |
| Deploy | `.github/workflows/deploy_*.yml` | Release |

## Referensi

- [Scribe Documentation](https://scribe.knuckles.wtf/laravel/)
- [OpenAPI Specification](https://spec.openapis.org/oas/v3.0.3)
- [Playwright Docs](https://playwright.dev/docs/intro)
- [Pest PHP Testing](https://pestphp.com/docs/)
