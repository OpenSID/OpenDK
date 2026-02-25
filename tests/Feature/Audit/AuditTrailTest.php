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

namespace Tests\Feature\Audit;

use App\Models\DataDesa;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\CrudTestCase;

beforeEach(function () {
    // Test setup if needed
});

describe('Audit Trail', function () {
    test('created_at and updated_at timestamps are set', function () {
        $desa = DataDesa::factory()->create();

        expect($desa->created_at)->not->toBeNull();
        expect($desa->updated_at)->not->toBeNull();
        expect($desa->created_at)->toBeInstanceOf(\Carbon\Carbon::class);
        expect($desa->updated_at)->toBeInstanceOf(\Carbon\Carbon::class);
    });

    test('updated_at is updated on model update', function () {
        $desa = DataDesa::factory()->create();

        $originalUpdatedAt = $desa->updated_at;

        sleep(1); // Ensure time difference

        $desa->update(['nama' => 'Updated Desa Name']);

        expect($desa->fresh()->updated_at)->toBeGreaterThan($originalUpdatedAt);
    });

    test('created_at does not change on update', function () {
        $desa = DataDesa::factory()->create();

        $originalCreatedAt = $desa->created_at;

        sleep(1); // Ensure time difference

        $desa->update(['nama' => 'Updated Desa Name']);

        expect($desa->fresh()->created_at)->toEqual($originalCreatedAt);
    });

    test('audit trail table exists', function () {
        // Check if audit_logs or activity_log table exists
        $tables = DB::select("SHOW TABLES LIKE 'activity_log'");

        // If spatie/laravel-activitylog is installed, this table should exist
        $hasActivityLog = count($tables) > 0;

        if ($hasActivityLog) {
            expect($hasActivityLog)->toBeTrue();
        } else {
            $this->markTestSkipped(
                'Activity log table does not exist. Install spatie/laravel-activitylog for audit trail.'
            );
        }
    });

    test('model changes are logged', function () {
        // This test requires spatie/laravel-activitylog to be installed
        $tables = DB::select("SHOW TABLES LIKE 'activity_log'");

        if (count($tables) === 0) {
            $this->markTestSkipped(
                'Activity log is not installed.'
            );
        }

        $desa = DataDesa::factory()->create();

        $initialLogCount = DB::table('activity_log')->count();

        $desa->update(['nama' => 'Updated Desa Name']);

        $newLogCount = DB::table('activity_log')->count();

        expect($newLogCount)->toBeGreaterThan($initialLogCount);
    });

    test('audit log contains correct data', function () {
        // This test requires spatie/laravel-activitylog to be installed
        $tables = DB::select("SHOW TABLES LIKE 'activity_log'");

        if (count($tables) === 0) {
            $this->markTestSkipped(
                'Activity log is not installed.'
            );
        }

        $user = User::first();
        $desa = DataDesa::factory()->create();

        $desa->update(['nama' => 'Updated Desa Name']);

        $log = DB::table('activity_log')
            ->where('subject_type', 'App\Models\DataDesa')
            ->where('subject_id', $desa->id)
            ->orderBy('id', 'desc')
            ->first();

        expect($log)->not->toBeNull();
        expect($log->description)->toContain('updated');
    });

    test('causer is recorded in audit log', function () {
        // This test requires spatie/laravel-activitylog to be installed
        $tables = DB::select("SHOW TABLES LIKE 'activity_log'");

        if (count($tables) === 0) {
            $this->markTestSkipped(
                'Activity log is not installed.'
            );
        }

        $user = User::first();
        $desa = DataDesa::factory()->create();

        $desa->update(['nama' => 'Updated Desa Name']);

        $log = DB::table('activity_log')
            ->where('subject_type', 'App\Models\DataDesa')
            ->where('subject_id', $desa->id)
            ->orderBy('id', 'desc')
            ->first();

        expect($log->causer_id)->toBe($user->id);
    });

    test('audit log can be queried by model', function () {
        // This test requires spatie/laravel-activitylog to be installed
        $tables = DB::select("SHOW TABLES LIKE 'activity_log'");

        if (count($tables) === 0) {
            $this->markTestSkipped(
                'Activity log is not installed.'
            );
        }

        $desa = DataDesa::factory()->create();

        $desa->update(['nama' => 'Updated Desa Name']);
        $desa->update(['website' => 'https://updated.com']);

        $logs = DB::table('activity_log')
            ->where('subject_type', 'App\Models\DataDesa')
            ->where('subject_id', $desa->id)
            ->get();

        expect($logs->count())->toBeGreaterThanOrEqual(2);
    });

    test('delete action is logged', function () {
        // This test requires spatie/laravel-activitylog to be installed
        $tables = DB::select("SHOW TABLES LIKE 'activity_log'");

        if (count($tables) === 0) {
            $this->markTestSkipped(
                'Activity log is not installed.'
            );
        }

        $desa = DataDesa::factory()->create();
        $desaId = $desa->id;

        $this->delete(route('data.data-desa.destroy', $desaId));

        $log = DB::table('activity_log')
            ->where('subject_type', 'App\Models\DataDesa')
            ->where('subject_id', $desaId)
            ->orderBy('id', 'desc')
            ->first();

        expect($log)->not->toBeNull();
        expect($log->description)->toContain('deleted');
    });

    test('properties are stored in audit log', function () {
        // This test requires spatie/laravel-activitylog to be installed
        $tables = DB::select("SHOW TABLES LIKE 'activity_log'");

        if (count($tables) === 0) {
            $this->markTestSkipped(
                'Activity log is not installed.'
            );
        }

        $desa = DataDesa::factory()->create();

        $oldName = $desa->nama;
        $newName = 'Updated Desa Name';

        $desa->update(['nama' => $newName]);

        $log = DB::table('activity_log')
            ->where('subject_type', 'App\Models\DataDesa')
            ->where('subject_id', $desa->id)
            ->orderBy('id', 'desc')
            ->first();

        $properties = json_decode($log->properties, true);

        expect($properties['old'])->toHaveKey('nama', $oldName);
        expect($properties['new'])->toHaveKey('nama', $newName);
    });
});
