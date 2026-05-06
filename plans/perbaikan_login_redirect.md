# Rencana Perbaikan: Redirect dari /login ke Dashboard jika Sudah Login

## Masalah
User yang sudah login namun mengakses halaman `/login` tidak di-redirect ke dashboard (tetap melihat halaman login). Seharusnya middleware `guest` melakukan redirect ke halaman home.

## Analisis
1. Rute login didefinisikan oleh `Auth::routes()` di dalam grup middleware `['installed', 'xss_sanitization']`.
2. `LoginController` menggunakan middleware `guest` di constructor (kecuali logout).
3. Middleware `guest` adalah `App\Http\Middleware\RedirectIfAuthenticated`.
4. Middleware tersebut mengarahkan ke `'/'` (root) bukan ke `RouteServiceProvider::HOME` (`/dashboard`).
5. Namun masalah utama adalah user tidak di-redirect sama sekali (mungkin karena kondisi tertentu).

## Kemungkinan Penyebab
- Middleware guest redirect ke `/` bukan `/dashboard` (masih redirect tapi ke halaman yang berbeda).
- Ada kemungkinan session/autentikasi tidak terdeteksi dengan benar.
- Ada middleware lain yang mengganggu alur.

## Rencana Perbaikan

### 1. Perbaikan Middleware RedirectIfAuthenticated
**File:** `app/Http/Middleware/RedirectIfAuthenticated.php`
**Perubahan:** Ganti `return redirect('/');` dengan `return redirect(RouteServiceProvider::HOME);` (atau `redirect()->intended(RouteServiceProvider::HOME)`).

**Alasan:** Agar konsisten dengan redirect setelah login dan mengarahkan ke dashboard.

### 2. Verifikasi Middleware Guest Dijalankan
**File:** `app/Http\Controllers\Auth\LoginController.php`
**Status:** Sudah benar (`$this->middleware('guest')->except('logout');`). Tidak perlu perubahan.

### 3. Testing
Setelah perubahan, lakukan testing:
- Login sebagai user
- Akses `/login` (harus redirect ke `/dashboard`)
- Pastikan tidak menampilkan halaman login

## Catatan Tambahan
- Jika setelah perbaikan masih terjadi masalah, perlu investigasi lebih lanjut tentang session dan guard autentikasi.
- Pastikan konfigurasi session dan cookie berjalan dengan baik.

## Langkah Implementasi
1. Switch ke mode Code
2. Edit file `app/Http/Middleware/RedirectIfAuthenticated.php`
3. Commit perubahan
4. Testing