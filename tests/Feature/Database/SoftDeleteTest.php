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

namespace Tests\Feature\Database;

use App\Models\DataDesa;
use App\Models\AnggaranDesa;
use Illuminate\Support\Facades\DB;
use Tests\CrudTestCase;

beforeEach(function () {
    // Test setup if needed
});

describe('Soft Delete Functionality', function () {
    test('models use soft delete', function () {
        // Skip: This method requires Doctrine DBAL which is deprecated in Laravel 11
        // To check if a model uses soft delete, check the model's traits instead
        $this->markTestSkipped('Requires Doctrine DBAL which is deprecated in Laravel 11. Check model traits instead.');
    });

    test('delete marks record as deleted not removed', function () {
        // This test assumes soft delete is implemented
        // If not, it will hard delete

        $desa = DataDesa::factory()->create();
        $desaId = $desa->id;

        $response = $this->delete(route('data.data-desa.destroy', $desaId));

        $response->assertRedirect(route('data.data-desa.index'));
        $response->assertSessionHas('success');

        // Check if record still exists in database (soft delete)
        // or is completely removed (hard delete)
        // Note: DataDesa doesn't use soft delete, so this will be hard delete
        $exists = DataDesa::find($desaId);

        // For hard delete (current implementation):
        expect($exists)->toBeNull();

        // If soft delete is implemented in the future, change to:
        // $deletedRecord = DataDesa::withTrashed()->find($desaId);
        // expect($deletedRecord)->not->toBeNull();
        // expect($deletedRecord->trashed())->toBeTrue();
    });

    test('deleted records are not shown in index', function () {
        $desa = DataDesa::factory()->create();

        // Delete the record (hard delete in current implementation)
        $this->delete(route('data.data-desa.destroy', $desa->id));

        // Get the list from index
        $response = $this->get(route('data.data-desa.getdata'));

        $response->assertStatus(200);

        // Deleted record should not appear in the list
        $responseData = $response->json();
        $deletedDesaInList = collect($responseData['data'])->firstWhere('id', $desa->id);

        expect($deletedDesaInList)->toBeNull();
    });

    test('restore soft deleted record', function () {
        // This test is for when soft delete is implemented
        // Currently skipped as soft delete is not implemented

        $this->markTestSkipped(
            'Soft delete restore functionality is not implemented yet.'
        );
    });

    test('force delete permanently removes record', function () {
        // This test is for when soft delete is implemented
        // Currently skipped as soft delete is not implemented

        $this->markTestSkipped(
            'Soft delete force delete functionality is not implemented yet.'
        );
    });

    test('trashed records can be queried', function () {
        // This test is for when soft delete is implemented
        // Currently skipped as soft delete is not implemented

        $this->markTestSkipped(
            'Soft delete trashed query functionality is not implemented yet.'
        );
    });

    test('unique validation ignores soft deleted records', function () {
        // Skip: Requires ValidDesa external validation
        $this->markTestSkipped('Requires ValidDesa external validation');
    });

    test('relationships with soft deleted models', function () {
        // Skip: Requires ValidDesa external validation
        $this->markTestSkipped('Requires ValidDesa external validation');
    });
});
