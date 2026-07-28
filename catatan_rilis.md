Di rilis versi v2607.0.1 di versi ini terdapat modul komentar pada artikel dan perbaikan lain yang diminta Komunitas.

Terimakasih [isi disini] yang telah berkontribusi langsung mengembangkan aplikasi OpenDK.


#### FITUR

1. [#1616](https://github.com/OpenSID/OpenDK/issues/1616) Penambahan fitur backup dan restore asset storage.
2. [#1648](https://github.com/OpenSID/OpenDK/issues/1648) Penambahan informasi yang lebih lengkap pada details pembangunan.
3. [#1646](https://github.com/OpenSID/OpenDK/issues/1646) Penambahan Terapkan Password History (10 Kata Sandi Terakhir).

#### BUG

1. [#1652](https://github.com/OpenSID/OpenDK/issues/1652) Perbaikan  fungsi unduh prosedur.
2. [#1653](https://github.com/OpenSID/OpenDK/issues/1653) Perbaikan  fungsi halaman public regulasi tidak ditampilkan dengan benar.
3. [#1654](https://github.com/OpenSID/OpenDK/issues/1654) Perbaikan  fungsi dokumen saat ini tidak di tampilkan dengan benar.
6. [#1647](https://github.com/OpenSID/OpenDK/issues/1647) Perbaikan  fungsi lihat penduduk details agar hanya menapilkan tanpa field.
7. [#1655](https://github.com/OpenSID/OpenDK/issues/1655) Perbaikan  fungsi Faq yang tampil pada halaman publik.
8. [#1682](https://github.com/OpenSID/OpenDK/issues/1682) Perbaikan  statistik penduduk di halaman public masih belum sesuai.
9. [#1656](https://github.com/OpenSID/OpenDK/issues/1656) Perbaikan tampilan widget event, media terkait, serta layout detail event.


#### TEKNIS

1. [#1670](https://github.com/OpenSID/OpenDK/issues/1670) Penyesuaian sembunyikan tombol unggah pada pengaturan daftar tema.
2. [#1651](https://github.com/OpenSID/OpenDK/issues/1651) Penyesuaian susun ulang akses untuk group pengguna.
3. [#1657](https://github.com/OpenSID/OpenDK/issues/1657) Penambahan data dummy seeder untuk demo OpenDK.
4. [#1674](https://github.com/OpenSID/OpenDK/issues/1674) Generate & publish OpenAPI spec untuk OpenDK + dokumentasi runbook integrasi.
5. [#1673](https://github.com/OpenSID/OpenDK/issues/1673) Contract tests consumer-driven antara OpenSID (consumer) dan OpenDK (provider).

#### CATATAN TAMBAHAN

# Jalankan migrasi dan seeder setelah diinstalasi.

php artisan migrate  
php artisan db:seed --class=RoleSpatieSeeder.

# Jalankan seeder demo
php artisan db:seed --class=DemoDatabaseSeeder