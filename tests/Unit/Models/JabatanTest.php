<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

use App\Enums\JenisJabatan;
use App\Models\Jabatan;

describe('isJabatanInti', function () {
    test('jabatan_camat_dan_sekretaris_adalah_jabatan_inti', function () {
        expect(Jabatan::factory()->camat()->make()->isJabatanInti())->toBeTrue();
        expect(Jabatan::factory()->sekretaris()->make()->isJabatanInti())->toBeTrue();
    });

    test('jabatan_lainnya_bukan_jabatan_inti', function () {
        expect(Jabatan::factory()->jabatanLainnya()->make()->isJabatanInti())->toBeFalse();
    });
});

describe('sebutan', function () {
    test('mengembalikan_nama_jabatan_dari_database', function () {
        Jabatan::where('jenis', JenisJabatan::Camat)->update(['jenis' => JenisJabatan::JabatanLainnya]);
        Jabatan::factory()->camat()->create(['nama' => 'Kepala Distrik']);

        expect(Jabatan::sebutan(JenisJabatan::Camat, 'Camat'))->toBe('Kepala Distrik');
    });

    test('mengembalikan_nilai_bawaan_jika_jabatan_tidak_ada', function () {
        Jabatan::where('jenis', JenisJabatan::Sekretaris)->update(['jenis' => JenisJabatan::JabatanLainnya]);

        expect(Jabatan::sebutan(JenisJabatan::Sekretaris, 'Sekretaris'))->toBe('Sekretaris');
    });
});
