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

use App\Jobs\PendudukQueueJob;
use App\Models\DataDesa;
use App\Models\Penduduk;
use Illuminate\Support\Facades\Exceptions;

beforeEach(function () {
    Exceptions::fake();
});

test('tidak error saat hapus_penduduk kosong', function () {
    (new PendudukQueueJob(['hapus_penduduk' => []]))->handle();

    Exceptions::assertNothingReported();
});

test('menghapus penduduk sesuai desa dan id_pend_desa', function () {
    $desa = DataDesa::factory()->create(['desa_id' => '33.24.03.2008']);
    $desaLain = DataDesa::factory()->create(['desa_id' => '33.24.03.2009']);

    $dihapus = Penduduk::factory()->create(['desa_id' => $desa->desa_id, 'id_pend_desa' => 2499099148]);
    $tetap = Penduduk::factory()->create(['desa_id' => $desaLain->desa_id, 'id_pend_desa' => 2499099148]);

    (new PendudukQueueJob(['hapus_penduduk' => [
        ['id_pend_desa' => 2499099148, 'desa_id' => $desa->desa_id],
    ]]))->handle();

    Exceptions::assertNothingReported();
    expect(Penduduk::find($dihapus->id))->toBeNull()
        ->and(Penduduk::find($tetap->id))->not->toBeNull();
});

test('hanya menghapus pasangan desa dan id_pend_desa dalam batch lintas desa', function () {
    $desaA = DataDesa::factory()->create(['desa_id' => '33.24.03.2008']);
    $desaB = DataDesa::factory()->create(['desa_id' => '33.24.03.2009']);

    $a1 = Penduduk::factory()->create(['desa_id' => $desaA->desa_id, 'id_pend_desa' => 1]);
    $a2 = Penduduk::factory()->create(['desa_id' => $desaA->desa_id, 'id_pend_desa' => 2]);
    $b1 = Penduduk::factory()->create(['desa_id' => $desaB->desa_id, 'id_pend_desa' => 1]);
    $b2 = Penduduk::factory()->create(['desa_id' => $desaB->desa_id, 'id_pend_desa' => 2]);

    (new PendudukQueueJob(['hapus_penduduk' => [
        ['id_pend_desa' => 1, 'desa_id' => $desaA->desa_id],
        ['id_pend_desa' => 2, 'desa_id' => $desaB->desa_id],
    ]]))->handle();

    expect(Penduduk::find($a1->id))->toBeNull()
        ->and(Penduduk::find($b2->id))->toBeNull()
        ->and(Penduduk::find($a2->id))->not->toBeNull()
        ->and(Penduduk::find($b1->id))->not->toBeNull();
});

test('melempar ulang exception agar job tercatat gagal', function () {
    // Nilai bersarang ditolak whereIn sehingga query delete gagal
    $job = new PendudukQueueJob(['hapus_penduduk' => [
        ['desa_id' => '33.24.03.2008', 'id_pend_desa' => ['bukan', 'skalar']],
    ]]);

    expect(fn () => $job->handle())->toThrow(InvalidArgumentException::class);
});
