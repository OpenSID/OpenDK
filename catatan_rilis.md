#### [v22.03.01]

Di rilis v22.03.01, menyediakan perbaikan lain yang diminta Komunitas OpenDK.

Terima kasih pada [untuk diisi] yang baru mulai berkontribusi.

#### Penambahan Fitur
1. [#288](https://github.com/OpenSID/OpenDK/issues/288) Penambahan fitur FAQS pada halaman website.

#### Perbaikan BUG
1. [#289](https://github.com/OpenSID/OpenDK/issues/289) Perbaiki pesan error Class 'Barryvdh\Debugbar\ServiceProvider' not found sewaktu install/update composer dengan opsi --no-dev…aktu install/update composer dengan opsi --no-dev
2. [#321](https://github.com/OpenSID/OpenDK/issues/321) Perbaiki tampilan header yang menutup bagian berita desa.
3. [#286](https://github.com/OpenSID/OpenDK/issues/286) Perbaiki gagal import penduduk.
4. [#331](https://github.com/OpenSID/OpenDK/issues/331) Perbaiki penempatan notifikasi sistem komplain.
5. [#335](https://github.com/OpenSID/OpenDK/issues/335) Perbaiki notifikasi hapus event yang tidak sesuai.
6. [#337](https://github.com/OpenSID/OpenDK/issues/337) Perbaiki tidak bisa hapus data desa jika dimodul lain sudah digunakan.
7. [#344](https://github.com/OpenSID/OpenDK/issues/344) Perbaiki tombol ubah dan hapus serta proses impor data modul kesehatan (AKI & AKB , epidemi penyakit).

#### TEKNIS
1. Hapus composer scripts cs-check & cs-fix yg sudah tidak digunakan.
2. Penyesuaian routes.
3. [#70](https://github.com/OpenSID/OpenDK/issues/70) Refactor Autentikasi dengan packages native laravel.
