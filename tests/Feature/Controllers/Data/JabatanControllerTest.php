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
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckActionPermission;
use App\Http\Middleware\CompleteProfile;
use App\Models\Jabatan;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

beforeEach(function () {
    $this->withoutMiddleware([
        Authenticate::class,
        RoleMiddleware::class,
        PermissionMiddleware::class,
        CheckActionPermission::class,
        CompleteProfile::class,
    ]);
});

describe('destroy', function () {
    test('menolak_hapus_jabatan_camat', function () {
        $jabatan = Jabatan::factory()->camat()->create();

        $response = $this->delete(route('data.jabatan.destroy', $jabatan->id));

        $response->assertRedirect(route('data.jabatan.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('ref_jabatan', ['id' => $jabatan->id]);
    });

    test('menolak_hapus_jabatan_sekretaris', function () {
        $jabatan = Jabatan::factory()->sekretaris()->create();

        $response = $this->delete(route('data.jabatan.destroy', $jabatan->id));

        $response->assertRedirect(route('data.jabatan.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('ref_jabatan', ['id' => $jabatan->id]);
    });

    test('menghapus_jabatan_lainnya', function () {
        $jabatan = Jabatan::factory()->jabatanLainnya()->create();

        $response = $this->delete(route('data.jabatan.destroy', $jabatan->id));

        $response->assertRedirect(route('data.jabatan.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('ref_jabatan', ['id' => $jabatan->id]);
    });
});

test('aplikasi_tetap_berjalan_saat_jabatan_camat_dan_sekretaris_tidak_ada', function () {
    // Simulasikan data jabatan inti yang hilang tanpa terhalang FK das_pengurus.
    Jabatan::whereIn('jenis', [JenisJabatan::Camat, JenisJabatan::Sekretaris])
        ->update(['jenis' => JenisJabatan::JabatanLainnya]);

    $this->get(route('data.jabatan.index'))->assertOk();
});
