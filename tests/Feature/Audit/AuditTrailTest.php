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
use App\Models\SettingAplikasi;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;
use Tests\CrudTestCase;

beforeEach(function () {
    $tables = DB::select("SHOW TABLES LIKE 'activity_log'");

    if (count($tables) === 0) {
        $this->markTestSkipped('Activity log is not installed.');
    }

    Activity::query()->delete();

    $this->user = User::factory()->create();
    $this->actingAs($this->user);
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
        $tables = DB::select("SHOW TABLES LIKE 'activity_log'");
        expect(count($tables))->toBeGreaterThan(0);
    });

    test('model changes are logged', function () {
        $model = SettingAplikasi::factory()->create();

        $initialLogCount = Activity::query()->count();

        $model->update(['value' => 'Updated Value']);

        $newLogCount = Activity::query()->count();

        expect($newLogCount)->toBeGreaterThan($initialLogCount);
    });

    test('audit log contains correct data', function () {
        $model = SettingAplikasi::factory()->create();

        $model->update(['value' => 'Updated Value']);

        $log = Activity::query()
            ->where('subject_type', SettingAplikasi::class)
            ->where('subject_id', $model->id)
            ->orderByDesc('id')
            ->first();

        expect($log)->not->toBeNull();
        expect($log->description)->toContain('ubah pengaturan aplikasi');
    });

    test('causer is recorded in audit log', function () {
        $model = SettingAplikasi::factory()->create();

        $model->update(['value' => 'Updated Value']);

        $log = Activity::query()
            ->where('subject_type', SettingAplikasi::class)
            ->where('subject_id', $model->id)
            ->orderByDesc('id')
            ->first();

        expect($log->causer_id)->toBe($this->user->id);
        expect($log->causer_type)->toBe(User::class);
    });

    test('audit log can be queried by model', function () {
        $model = SettingAplikasi::factory()->create();

        $model->update(['value' => 'Updated Value 1']);
        $model->update(['value' => 'Updated Value 2']);

        $logs = Activity::query()
            ->where('subject_type', SettingAplikasi::class)
            ->where('subject_id', $model->id)
            ->get();

        expect($logs->count())->toBeGreaterThanOrEqual(2);
    });

    test('delete action is logged', function () {
        $model = SettingAplikasi::factory()->create();
        $modelId = $model->id;

        $model->delete();

        $log = Activity::query()
            ->where('subject_type', SettingAplikasi::class)
            ->where('subject_id', $modelId)
            ->orderByDesc('id')
            ->first();

        expect($log)->not->toBeNull();
        expect($log->description)->toContain('hapus pengaturan aplikasi');
    });

    test('properties are stored in audit log', function () {
        $model = SettingAplikasi::factory()->create();

        $oldValue = $model->value;
        $newValue = 'Updated Value';

        $model->update(['value' => $newValue]);

        $log = Activity::query()
            ->where('subject_type', SettingAplikasi::class)
            ->where('subject_id', $model->id)
            ->orderByDesc('id')
            ->first();

        $properties = json_decode($log->properties, true);

        expect($properties['changed_attributes']['value'])->toBe($newValue);
    });
});
