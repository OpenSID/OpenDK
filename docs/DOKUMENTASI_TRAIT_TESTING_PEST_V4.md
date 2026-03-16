# Panduan Penggunaan Trait Testing (Pest v4) - OpenDK

Dokumen ini menjelaskan arsitektur testing berbasis **trait** yang diimplementasikan untuk mendukung **Pest v4**. Pendekatan ini lebih mengutamakan *composition* daripada *inheritance* yang berlebihan pada `TestCase.php`.

---

## 🛠 Trait yang Tersedia

### 1. `Tests\Traits\WithDatabaseSetup`
Mengelola isolasi database antar test.
- **Mekanisme**: Menggunakan `Illuminate\Foundation\Testing\DatabaseTransactions`.
- **Keamanan**: Trait ini **SANGAT AMAN** karena tidak melakukan pembersihan data (`RefreshDatabase`), melainkan hanya membungkus setiap test dalam transaksi database yang akan di-*rollback* secara otomatis.

### 2. `Tests\Traits\WithUserAuthentication`
Menyediakan helper untuk proses autentikasi.
- **Method**:
    - `$this->actingAsUser($user = null)`: Login sebagai user biasa. Mengambil user pertama dari DB atau membuat baru via factory jika tidak ada.
    - `$this->actingAsAdmin($admin = null)`: Login sebagai administrator. (Saat ini mengikuti pola `actingAsUser`).

### 3. `Tests\Traits\WithSettingAplikasi`
Digunakan untuk manipulasi konfigurasi aplikasi saat runtime testing.
- **Method**:
    - `$this->setDefaultApplicationConfig()`: Mematikan fitur `sinkronisasi_database_gabungan` agar test berjalan lebih cepat dan terisolasi.

---

## 🚀 Cara Penggunaan

Gunakan fungsi `uses()` di dalam file test Pest Anda untuk menyertakan trait yang dibutuhkan.

### Contoh Implementasi

```php
<?php

use Tests\Traits\WithDatabaseSetup;
use Tests\Traits\WithUserAuthentication;
use Tests\Traits\WithSettingAplikasi;

// Mendaftarkan trait
uses(
    WithDatabaseSetup::class,
    WithUserAuthentication::class,
    WithSettingAplikasi::class
);

beforeEach(function () {
    // Jalankan setup yang dibutuhkan
    $this->actingAsAdmin();
    $this->setDefaultApplicationConfig();
});

test('halaman dashboard admin dapat diakses', function () {
    $response = $this->get('/admin/dashboard');

    $response->assertStatus(200);
});

test('perubahan data dibungkus dalam transaksi', function () {
    \App\Models\User::factory()->create(['name' => 'Test Persistence']);
    
    $this->assertDatabaseHas('users', ['name' => 'Test Persistence']);
    // Data ini akan hilang setelah test ini selesai karena DatabaseTransactions
});
```

---

## 💡 Prinsip Testing di OpenDK

1. **Minimal TestCase.php**: Jangan tambahkan logic bisnis atau auth global ke `tests/TestCase.php`. Biarkan file tersebut sesederhana mungkin.
2. **Composition over Inheritance**: Jika Anda membutuhkan logic setup yang baru dan reusable, buatlah trait baru di folder `tests/Traits/`.
3. **No RefreshDatabase**: Hindari penggunaan `RefreshDatabase` karena akan menghapus data di database lokal pengembang. Gunakan selalu `WithDatabaseSetup` (DatabaseTransactions).
4. **Test Independence**: Setiap test harus bisa berjalan secara mandiri tanpa bergantung pada urutan eksekusi test lain.
