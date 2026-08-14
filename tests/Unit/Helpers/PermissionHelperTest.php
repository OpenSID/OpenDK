<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace Tests\Unit\Helpers;

use Tests\TestCase;

describe('Permission Helper Function', function () {
    test('permission_name returns translated name for access.dashboard', function () {
        $result = permission_name('access.dashboard');
        expect($result)->toBe('Beranda');
    });

    test('permission_name returns translated name for access.informasi', function () {
        $result = permission_name('access.informasi');
        expect($result)->toBe('Informasi');
    });

    test('permission_name returns translated name for access.informasi.prosedur', function () {
        $result = permission_name('access.informasi.prosedur');
        expect($result)->toBe('Informasi - Prosedur');
    });

    test('permission_name returns translated name for access.data.penduduk', function () {
        $result = permission_name('access.data.penduduk');
        expect($result)->toBe('Data - Penduduk');
    });

    test('permission_name returns translated name for access.setting.role', function () {
        $result = permission_name('access.setting.role');
        expect($result)->toBe('Pengaturan - Role');
    });

    test('permission_name returns original name if translation not found', function () {
        $result = permission_name('nonexistent.permission');
        expect($result)->toBe('nonexistent.permission');
    });

    test('permission_name returns original if permission key is empty', function () {
        $result = permission_name('');
        expect($result)->toBe('');
    });
});