#### [v22.05.pasca]

Di rilis v22.05.pasca, menyediakan komunikasi pesan Antara Aplikasi OpendDK dengan OpenSId. Rilis ini juga berisi perbaikan lain yang diminta Komunitas OpenDK.

Terima kasih pada [totoprayogo1916] yang terus berkontribusi. Terima kasih pula pada [wongjapan] yang baru mulai berkontribusi.



#### Penambahan Fitur
1. [#354](https://github.com/OpenSID/OpenDK/issues/354) Tambahkan tombol list/show data penduduk dapat diperluas hingga 500 baris.
2. [#355](https://github.com/OpenSID/OpenDK/issues/355) Perjelas status kehamilan pada tampilan detail data penduduk.
3. [#356](https://github.com/OpenSID/OpenDK/issues/356) Sediakan tombol kembali pada form detail data penduduk.
4. [#332](https://github.com/OpenSID/OpenDK/issues/332) Tambahkan fitur akfif/non-aktifkan FAQ di web.
6. [#139](https://github.com/OpenSID/OpenDK/issues/139) Tambahkan jumlah karakter pada isian tipologi sejarah.
7. [#294](https://github.com/OpenSID/OpenDK/issues/294) Ambil data tingkat pendidikan dari data penduduk hasil sinkronisasi penduduk.
8. [#313](https://github.com/OpenSID/OpenDK/issues/313) Sediakan group pengguna kontributor berita/artikel kecamatan.
9. [#310](https://github.com/OpenSID/OpenDK/issues/310) Tambahkan sinkronisasi profil desa OpenSID ke OpenDK.
10. [#218](https://github.com/OpenSID/OpenDK/issues/218) Sediakan kotak pesan untuk komunikasi dengan desa.
11. [#352](https://github.com/OpenSID/OpenDK/issues/352) Tambahkan Scrollbar Pada Menu List Nama Desa

#### Perbaikan BUG
1. [#353](https://github.com/OpenSID/OpenDK/issues/353) Perbaiki sikronisasi data penduduk OpenSID ke OpenDK.
2. [#370](https://github.com/OpenSID/OpenDK/issues/370) Perbaiki XHRrequest return 404 pada halaman ubah data umum.
3. [#374](https://github.com/OpenSID/OpenDK/pull/374) Sesuaikan format angka pada statistik.
4. [#361](https://github.com/OpenSID/OpenDK/issues/361) Perbaiki error migrasi 2022_03_09_134418_create_permission_tables.
5. [#365](https://github.com/OpenSID/OpenDK/issues/365) Perbaiki table das_setting tidak ada saat composer install.
6. [#385](https://github.com/OpenSID/OpenDK/issues/385) Perbaiki link demo OpenDK.
7. [#395](https://github.com/OpenSID/OpenDK/issues/395) Perbaiki nama desa yang terpotong karena terlalu panjang di menu desa.
8. [#394](https://github.com/OpenSID/OpenDK/issues/394) Perbaiki form upload file hilang pada module event ketika gagal upload.

#### TEKNIS
1. Perbaiki error jquery(...).validate not function.
2. Perbaiki nama class yang tidak terbaca di LINUX.