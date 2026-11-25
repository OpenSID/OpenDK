Di rilis versi v2611.0.0 di versi ini terdapat modul komentar pada artikel dan perbaikan lain yang diminta Komunitas.

Terimakasih [isi disini] yang telah berkontribusi langsung mengembangkan aplikasi OpenDK.


#### FITUR

1. [#1790](https://github.com/OpenSID/OpenDK/issues/1790) Fix: Tema default belum bisa diunggah karena theme.json belum ada.

#### BUG

1. [#1771](https://github.com/OpenSID/OpenDK/issues/1771) Administrator utama bisa mengubah email hanya untuk akunnya sendiri.
2. [#1770](https://github.com/OpenSID/OpenDK/issues/1770) Perbaiki foto pengurus tidak tersimpan saat tambah data baru.
3. [#1777](https://github.com/OpenSID/OpenDK/issues/1777) Sembunyikan identitas pelapor dan NIK pada API komplain.
4. [#1789](https://github.com/OpenSID/OpenDK/pull/1789) Desa dengan nama lebih dari satu kata tidak ditemukan (404) saat menggunakan database lokal.
5. [#1793](https://github.com/OpenSID/OpenDK/issues/1793) Fix: Perubahan data umum tidak langsung tampil pada API profil.
6. [#1802](https://github.com/OpenSID/OpenDK/issues/1802) Perbaikan Detail komplain slug tidak ada jadi 404, dan hapus 3 route user yatim.
7. [#1800](https://github.com/OpenSID/OpenDK/issues/1800)  Cegah 500 pada respons non-JSON dan tambah timeout di ApiService.
8. [#1801](https://github.com/OpenSID/OpenDK/issues/1801) Route /api/desa dan /testEmail menghasilkan 500 untuk pengunjung publik.
9. [#1805](https://github.com/OpenSID/OpenDK/issues/1805) Tambahkan timeout dan penanganan error pada pemanggilan HTTP di luar ApiService.
10. [#1795](https://github.com/OpenSID/OpenDK/issues/1795) Fix: Impor Toilet & Sanitasi berhasil tetapi redirect error 500 (nama route salah).
11. [#1796](https://github.com/OpenSID/OpenDK/issues/1796) Menghapus Jabatan Camat/Sekretaris membuat seluruh aplikasi error 500.
12. [#1797](https://github.com/OpenSID/OpenDK/issues/1797) Persentase belanja barang/jasa salah dan division by zero di AnggaranRealisasiChartService.
13. [#1798](https://github.com/OpenSID/OpenDK/issues/1798) Perbaikan Cache listDesa terus diperpanjang dan tidak membedakan parameter $all.
14. [#1799](https://github.com/OpenSID/OpenDK/issues/1799) Perbaikan getFeeds() error jika item RSS tidak punya author dan gagal per feed.
15. [#1813](https://github.com/OpenSID/OpenDK/issues/1813) Perbaikan Batasi unduh dokumen hanya untuk dokumen terbit dan belum kedaluwarsa.


#### TEKNIS

1. [#1774](https://github.com/OpenSID/OpenDK/issues/1774) Konsolidasi: lebarkan kolom id ke BIGINT dan perintah geser id per desa.
2. [#1780](https://github.com/OpenSID/OpenDK/issues/1780) Audit HTTP verb route: GET mengubah state dan verb tidak sesuai aksi.


#### KEAMANAN

1. [#1781](https://github.com/OpenSID/OpenDK/issues/1781) Reflected XSS di halaman kategori potensi.
2. [#1782](https://github.com/OpenSID/OpenDK/issues/1782)Update user tanpa cek kepemilikan dan role mentah dapat memberi super-admin.
3. [#1783](https://github.com/OpenSID/OpenDK/issues/1783) Pembatasan edit email user hanya di sisi klien.