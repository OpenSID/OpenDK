# Panduan Pengujian Browser (E2E)

Dokumen ini menjelaskan cara menjalankan dan menulis pengujian browser (End-to-End) di OpenDK menggunakan **Pest PHP** dan plugin **Browser (Playwright)**.

## Prasyarat

Sebelum menjalankan pengujian, pastikan Anda telah menginstal driver Playwright:

```bash
npx playwright install --with-deps chromium
```

## Persiapan Lingkungan

Pengujian ini membutuhkan lingkungan khusus agar tidak mengganggu data produksi.

1.  **File `.env.testing`**: Pastikan Anda memiliki file `.env.testing`. Pengujian akan secara otomatis menggunakan file ini jika `APP_ENV=testing`.
2.  **Database**: Sangat disarankan menggunakan database terpisah untuk pengujian (misal: `opendk_test`).
3.  **Konfigurasi**: Pengujian menggunakan `SESSION_DRIVER=file` dan `CACHE_DRIVER=file` untuk memastikan persistensi sesi antar permintaan browser.

## Cara Menjalankan Pengujian

Di Windows, perintahnya berbeda tergantung apakah Anda menggunakan **Command Prompt (CMD)** atau **PowerShell**.

### Menggunakan PowerShell (Default di Windows Terminal)
```powershell
$env:APP_ENV="testing"; vendor\bin\pest --group=browser
```

### Menggunakan Command Prompt (CMD)
```cmd
set APP_ENV=testing && vendor\bin\pest --group=browser
```

### Menjalankan File Tertentu (PowerShell)
```powershell
$env:APP_ENV="testing"; vendor\bin\pest tests\Browser\DataDesaJourneyTest.php
```

### Menjalankan dengan Visual / Headed (PowerShell)
```powershell
$env:APP_ENV="testing"; $env:BROWSER_HEADLESS="false"; vendor\bin\pest tests\Browser\UserJourneyTest.php
```

## Struktur Folder Test

- `tests/Browser/`: Berisi file pengujian (`*Test.php`).
- `tests/Browser/Pages/`: Berisi **Page Objects** untuk mengkapsulasi interaksi elemen UI.
- `tests/BrowserTestCase.php`: Base class yang mengatur setup awal (seeding, truncate table, dsb).

## Fitur Khusus Pengujian

### 1. Mock Synchronization (Ambil Desa)
Untuk menghindari *deadlock* pada server single-threaded (seperti `php artisan serve`) dan ketergantungan API eksternal, kita menggunakan bypass internal.
Di dalam test:
```php
config(['app.host_pantau' => 'mock']);
```
Ini akan memicu `DataDesaController` untuk mengembalikan data statis yang ditentukan untuk testing.

### 2. Penanganan Input Mask
Beberapa field menggunakan input mask (seperti Kode Desa). Gunakan `type()` dengan delay agar input terbaca dengan benar:
```php
$browser->page()->locator('#desa_id')->type('11.01.01.2001', ['delay' => 50]);
```

### 3. Session Switching
Untuk menguji login sebagai user baru yang baru dibuat tanpa logout dari admin:
```php
$this->actingAs($newUser);
$browser = visit('/dashboard');
```

## Troubleshooting

- **Timeout**: Jika test gagal karena timeout, pastikan server lokal merespon dengan cepat atau tambah timeout di `phpunit.xml` atau langsung di pemanggilan `waitForText()`.
- **Screenshot Kegagalan**: Jika test gagal, screenshot akan disimpan secara otomatis di folder `tests/Browser/Screenshots/` (jika dikonfigurasi) atau Anda bisa melihat logs di `storage/logs/`.
