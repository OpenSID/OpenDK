<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

test('semua setting key terdaftar dalam database', function () {
    $expectedKeys = [
        'judul_aplikasi',
        'artikel_kecamatan_perhalaman',
        'artikel_desa_perhalaman',
        'jumlah_artikel_desa',
        'tte',
        'tte_api',
        'tte_username',
        'tte_password',
        'pemeriksaan_camat',
        'pemeriksaan_sekretaris',
        'sinkronisasi_database_gabungan',
        'api_server_database_gabungan',
        'api_key_database_gabungan',
        'api_key_opendk',
        'layanan_opendesa_token',
        'google_recaptcha',
        'jenis_peta',
        'map_box',
    ];

    // cek apakah key ada yang tidak ada di database
    foreach ($expectedKeys as $key) {
        $this->assertDatabaseHas('das_setting', ['key' => $key]);
    }
});
