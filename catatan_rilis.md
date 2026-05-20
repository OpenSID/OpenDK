Di rilis versi v2606.0.0 di versi ini terdapat modul komentar pada artikel dan perbaikan lain yang diminta Komunitas.

Terimakasih [isi disini] yang telah berkontribusi langsung mengembangkan aplikasi OpenDK.


#### FITUR

1. [#1498](https://github.com/OpenSID/OpenDK/issues/1497) Halaman public setelah login mengarah ke halaman dashboard
2. [#1539](https://github.com/OpenSID/OpenDK/issues/1539) Tambahkan tanggal terbit pada postingan artike

#### BUG

1. [#1512](https://github.com/OpenSID/OpenDK/issues/1512) Perbaikan error ketika membuka artikel yang memiliki judul sangat panjang (lebih dari 191 karakter)
2. [#1519](https://github.com/OpenSID/OpenDK/issues/1519) Perbaikan Tampilan Kolom aksi di artikel berubah ketika ada artikel dengan judul yang panjang (191 karakter)
3. [#1520](https://github.com/OpenSID/OpenDK/issues/1520) Perbaikan Sorting tanggal terbit artikel tidak berjalan dengan semestinya
4. [#1542](https://github.com/OpenSID/OpenDK/issues/1542) Perbaikan tampilan sumber dana di pembagunan
5. [#1545](https://github.com/OpenSID/OpenDK/issues/1545) Perbaikan Sort, search , pagination, dan filter desa tidak berfungsi di menu data -> pembangunan

#### TEKNIS

1. [#1505](https://github.com/OpenSID/OpenDK/issues/1505) Penyesuaian Permission Access
2. [#1514](https://github.com/OpenSID/OpenDK/issues/1514) Fix WAF Blocking di datatables dengan ubah GET ke POST di Menu Data->Kecamatan
3. [#1515](https://github.com/OpenSID/OpenDK/issues/1515) Fix WAF Blocking di datatables dengan ubah GET ke POST di Menu Data -> Kependudukan
4. [#1523](https://github.com/OpenSID/OpenDK/issues/1523) Fix WAF Blocking di datatables dengan ubah GET ke POST di Menu Data -> Program Bantuan dan Data -> Pembangunan
5. [#1524](https://github.com/OpenSID/OpenDK/issues/1523) Fix WAF Blocking di datatables dengan ubah GET ke POST di Menu Data -> Finansial
6. [#1521](https://github.com/OpenSID/OpenDK/issues/1521) Fix WAF Blocking di datatables dengan ubah GET ke POST di Menu Data -> Kesehatan
7. [#1522](https://github.com/OpenSID/OpenDK/issues/1522) Fix WAF Blocking di datatables dengan ubah GET ke POST di Menu Data -> Pendidikan
8. [#1527](https://github.com/OpenSID/OpenDK/issues/1527) Fix WAF Blocking di datatables dengan ubah GET ke POST di Menu Pengaturan
9. [#1527](https://github.com/OpenSID/OpenDK/issues/1527) Fix WAF Blocking di datatables dengan ubah GET ke POST di Menu Admin SIKEMA -> Daftar Keluhan
10. [#1485](https://github.com/OpenSID/OpenDK/issues/1485) Perbaikan Dependecy bot Security
