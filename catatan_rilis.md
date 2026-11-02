Di rilis versi v2611.0.0 di versi ini terdapat modul komentar pada artikel dan perbaikan lain yang diminta Komunitas.

Terimakasih [isi disini] yang telah berkontribusi langsung mengembangkan aplikasi OpenDK.


#### FITUR


#### BUG

1. [#1771](https://github.com/OpenSID/OpenDK/issues/1771) Administrator utama bisa mengubah email hanya untuk akunnya sendiri.
2. [#1770](https://github.com/OpenSID/OpenDK/issues/1770) Perbaiki foto pengurus tidak tersimpan saat tambah data baru.
3. [#1777](https://github.com/OpenSID/OpenDK/issues/1777) Sembunyikan identitas pelapor dan NIK pada API komplain.


#### TEKNIS

1. [#1774](https://github.com/OpenSID/OpenDK/issues/1774) Konsolidasi: lebarkan kolom id ke BIGINT dan perintah geser id per desa.


#### KEAMANAN

1. [#1781](https://github.com/OpenSID/OpenDK/issues/1781) Reflected XSS di halaman kategori potensi.
2. [#1782](https://github.com/OpenSID/OpenDK/issues/1782)Update user tanpa cek kepemilikan dan role mentah dapat memberi super-admin.
3. [#1783](https://github.com/OpenSID/OpenDK/issues/1783) Pembatasan edit email user hanya di sisi klien.