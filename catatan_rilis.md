Di rilis versi v2609.0.0 di versi ini terdapat modul komentar pada artikel dan perbaikan lain yang diminta Komunitas.

Terimakasih [isi disini] yang telah berkontribusi langsung mengembangkan aplikasi OpenDK.


#### FITUR

1. [#1725](https://github.com/OpenSID/OpenDK/issues/1725) Penambahan fungsi pratinjau file pdf untuk kebutuhan OpenDK tidak di dukung.

#### BUG

1. [#1718](https://github.com/OpenSID/OpenDK/issues/1718) Bug pada Modul Potensi – OpenDK v2608.0.2.
2. [#1724](https://github.com/OpenSID/OpenDK/issues/1724) eta Letak Geografis kosong (Bounds are not valid) karena $data_umum tidak dikirim ke view gabungan.
3. [#1721](https://github.com/OpenSID/OpenDK/issues/1721) Halaman Unduhan Dokumen hanya menampilkan data sesuai default pagination API (10 data).
4. [#1731](https://github.com/OpenSID/OpenDK/issues/1731) Perbaikan menambah anggota penduduk untuk data suplemen tidak menampilkan list penduduk & keluarga.
5. [#1732](https://github.com/OpenSID/OpenDK/issues/1732) Berkas download dokumen laporan-penduduk pada data tidak sikron dari OpenSID.
6. [#1727](https://github.com/OpenSID/OpenDK/issues/1727) Perbaikan Tampilan Thumbnail Galeri dan Pratinjau Gambar Dinamis.

#### TEKNIS

1. [#1713](https://github.com/OpenSID/OpenDK/issues/1713) File di storage/app/public 404 karena direktori upload dibuat permission 0700 (Flysystem directory visibility).
2. [#58](https://github.com/OpenSID/wiki-keamanan/issues/58) Insecure deserialization via Crypt::decrypt() (unserialize=true) on LogViewer user params.
3. [#57](https://github.com/OpenSID/wiki-keamanan/issues/57) CRITICAL: RCE via Theme Hook include_once — backtick execution operator bypasses ThemeHooksValidator (theme.php).