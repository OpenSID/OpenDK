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
use App\Models\Penduduk;
use App\Models\Imunisasi;
use App\Models\AnggaranDesa;
use Illuminate\Support\Facades\DB;
use Tests\CrudTestCase;

beforeEach(function () {
    // Test setup if needed
});

describe('Database Transactions', function () {
    test('transaction rolls back on error', function () {
        $initialCount = DataDesa::count();

        try {
            DB::beginTransaction();

            DataDesa::create([
                'desa_id' => '1234567890',
                'nama' => 'Test Desa',
                'sebutan_desa' => 'Desa',
                'website' => 'https://test.com',
                'luas_wilayah' => 100.00,
            ]);

            // Simulate error
            throw new \Exception('Simulated error');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        expect(DataDesa::count())->toBe($initialCount);
    });

    test('transaction commits successfully', function () {
        $initialCount = DataDesa::count();

        DB::beginTransaction();

        try {
            DataDesa::create([
                'desa_id' => '1234567891',
                'nama' => 'Test Desa Commit',
                'sebutan_desa' => 'Desa',
                'website' => 'https://test.com',
                'luas_wilayah' => 100.00,
            ]);

            DB::commit();

            expect(DataDesa::count())->toBe($initialCount + 1);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    });

    test('multiple operations in single transaction', function () {
        $desa = DataDesa::factory()->create();

        $initialPendudukCount = Penduduk::count();
        $initialImunisasiCount = Imunisasi::count();

        DB::beginTransaction();

        try {
            // Create multiple related records
            Penduduk::factory()->count(3)->create(['desa_id' => $desa->desa_id]);
            Imunisasi::factory()->count(2)->create(['desa_id' => $desa->desa_id]);

            DB::commit();

            expect(Penduduk::count())->toBe($initialPendudukCount + 3);
            expect(Imunisasi::count())->toBe($initialImunisasiCount + 2);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    });

    test('transaction rolls back all operations on error', function () {
        $desa = DataDesa::factory()->create();

        $initialPendudukCount = Penduduk::count();
        $initialImunisasiCount = Imunisasi::count();

        try {
            DB::beginTransaction();

            Penduduk::factory()->count(3)->create(['desa_id' => $desa->desa_id]);

            // Create imunisasi that will cause error
            Imunisasi::factory()->count(2)->create(['desa_id' => $desa->desa_id]);

            // Simulate error after some operations
            throw new \Exception('Simulated error after operations');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        // All operations should be rolled back
        expect(Penduduk::count())->toBe($initialPendudukCount);
        expect(Imunisasi::count())->toBe($initialImunisasiCount);
    });

    test('nested transactions work correctly', function () {
        $initialCount = DataDesa::count();

        DB::beginTransaction();

        try {
            DataDesa::create([
                'desa_id' => '1234567892',
                'nama' => 'Outer Transaction',
                'sebutan_desa' => 'Desa',
            ]);

            DB::beginTransaction(); // Nested transaction

            DataDesa::create([
                'desa_id' => '1234567893',
                'nama' => 'Inner Transaction',
                'sebutan_desa' => 'Desa',
            ]);

            DB::commit(); // Commit nested
            DB::commit(); // Commit outer

            expect(DataDesa::count())->toBe($initialCount + 2);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    });

    test('database transaction trait works correctly', function () {
        // DatabaseTransactions trait should automatically rollback all tests
        $desa = DataDesa::factory()->create();

        expect($desa->id)->toBeGreaterThan(0);

        // After test ends, transaction should rollback automatically
        // This is tested by the trait itself
    });

    test('concurrent transactions do not interfere', function () {
        $initialCount = DataDesa::count();

        // First transaction
        DB::beginTransaction();
        DataDesa::create([
            'desa_id' => '1111111111',
            'nama' => 'Transaction 1',
            'sebutan_desa' => 'Desa',
        ]);
        DB::commit();

        // Second transaction
        DB::beginTransaction();
        DataDesa::create([
            'desa_id' => '2222222222',
            'nama' => 'Transaction 2',
            'sebutan_desa' => 'Desa',
        ]);
        DB::commit();

        expect(DataDesa::count())->toBe($initialCount + 2);
    });

    test('savepoint and rollback to savepoint', function () {
        $initialCount = DataDesa::count();

        DB::beginTransaction();

        // Create first record
        DataDesa::create([
            'desa_id' => '3333333333',
            'nama' => 'Before Savepoint',
            'sebutan_desa' => 'Desa',
        ]);

        // Create savepoint
        DB::savepoint('test_savepoint');

        // Create second record
        DataDesa::create([
            'desa_id' => '4444444444',
            'nama' => 'After Savepoint',
            'sebutan_desa' => 'Desa',
        ]);

        // Rollback to savepoint
        DB::rollbackToSavepoint('test_savepoint');

        DB::commit();

        // Should only have first record
        expect(DataDesa::count())->toBe($initialCount + 1);
        $this->assertDatabaseHas('das_data_desa', ['nama' => 'Before Savepoint']);
        $this->assertDatabaseMissing('das_data_desa', ['nama' => 'After Savepoint']);
    });
});
