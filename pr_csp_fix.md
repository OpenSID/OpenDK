# Fix: Hapus keyword duplikat pada directive `object-src` di pengaturan Content Security Policy (CSP)

## Apa yang diselesaikan oleh PR ini?
PR ini memperbaiki issue pada *console browser* di environment production di mana terdapat error syntax pada _header_ Content Security Policy (CSP):
`The Content-Security-Policy directive 'object-src' contains the keyword 'none' alongside with other source expressions.`

Sebelumnya, pada file middleware `SecurityHeaders.php`, directive di-set menjadi `object-src 'none' ".$localDomain."`. Sesuai standar spesifikasi web, jika keyword `'none'` digunakan, ia harus berdiri sendiri dan tidak boleh digabungkan dengan *source expression* lainnya (seperti `$localDomain`). Penggabungan ini membuat browser mengabaikan aturan tersebut dan memunculkan pesan error.

## Perubahan yang dilakukan:
- Menghapus variabel `".$localDomain."` dari directive `object-src` di `app/Http/Middleware/SecurityHeaders.php`.
- Directive `object-src` sekarang murni menggunakan nilai `'none'` (yang merupakan *best practice* keamanan untuk memblokir _embed/object_ eksternal seperti Flash atau Java Applets).

## Dampak Perubahan:
- Menghilangkan pesan error merah CSP di _console browser_.
- Memastikan aturan keamanan _object-src_ benar-benar dihormati oleh browser.
- Tidak ada _test_ yang terdampak/perlu diubah karena pengecekan spesifik _header_ CSP belum ada di test suite.

## Cara Pengujian (Testing Instructions):
1. _Pull branch_ ini.
2. Buka aplikasi di browser.
3. Buka *Developer Tools* -> *Console*.
4. Pastikan tidak ada lagi error yang menyebutkan `directive 'object-src' contains the keyword 'none'`.
