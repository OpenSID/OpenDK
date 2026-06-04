<?php

namespace Tests\Feature\Security;

use App\Models\User;
use App\Models\Themes;
use Database\Seeders\RoleSpatieSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use ZipArchive;

describe('Theme Upload Security (RCE Prevention)', function () {

    beforeEach(function () {
        Storage::fake('public');
        $this->seed(RoleSpatieSeeder::class);

        $this->superAdmin = User::factory()->create();
        $this->superAdmin->assignRole('super-admin');

        $this->adminWebsite = User::factory()->create();
        $this->adminWebsite->assignRole('administrator-website');
    });

    // ── Phase 4: Route permission ──

    test('super_admin_can_access_upload_page', function () {
        $r = $this->actingAs($this->superAdmin)->get(route('setting.themes.index'));
        $r->assertStatus(200);
    });

    test('administrator_website_forbidden_from_upload', function () {
        $file = UploadedFile::fake()->create('theme.zip', 100, 'application/zip');
        $r = $this->actingAs($this->adminWebsite)
            ->post(route('setting.themes.upload'), ['file' => $file]);
        $r->assertStatus(403);
    });

    // ── Phase 2: ZIP content scanning ──

    test('upload_with_php_in_zip_is_rejected', function () {
        $file = makeZipWithPhp();
        $r = $this->actingAs($this->superAdmin)
            ->post(route('setting.themes.upload'), ['file' => $file]);
        $r->assertJson(['status' => 'error']);
    });

    // ── BUG 3 FIX: ZIP path traversal ──

    test('upload_with_path_traversal_in_zip_is_rejected', function () {
        $file = makeZipWithPathTraversal();
        $r = $this->actingAs($this->superAdmin)
            ->post(route('setting.themes.upload'), ['file' => $file]);
        $r->assertJson(['status' => 'error']);
    });

    // ── Phase 3: Activate validates hooks ──

    test('activate_with_clean_hooks_succeeds', function () {
        // Ensure default theme exists in DB and on disk
        $hooksDir = base_path('themes/default');
        if (! is_dir($hooksDir)) {
            mkdir($hooksDir, 0755, true);
        }

        $hooksFile = $hooksDir . '/hooks.php';
        file_put_contents($hooksFile, '<?php function test_hook(): string { return "ok"; }');

        $theme = Themes::firstOrCreate(
            ['name' => 'default'],
            [
                'vendor'      => 'opendk',
                'version'     => '1.0',
                'description' => 'Default',
                'path'        => $hooksDir,
                'system'      => true,
                'active'      => 0,
            ]
        );

        $r = $this->actingAs($this->superAdmin)
            ->get(route('setting.themes.activate', $theme));
        $r->assertStatus(302);

        @unlink($hooksFile);
    });

    // OBS 1 FIX: activate() sekarang redirect 302 + flash error, bukan HTTP 500
    test('activate_with_malicious_hooks_redirects_with_error', function () {
        $hooksDir = base_path('themes/default');
        if (! is_dir($hooksDir)) {
            mkdir($hooksDir, 0755, true);
        }

        $hooksFile = $hooksDir . '/hooks.php';
        file_put_contents($hooksFile, '<?php passthru("echo x");');

        $theme = Themes::firstOrCreate(
            ['name' => 'default'],
            [
                'vendor'      => 'opendk',
                'version'     => '1.0',
                'description' => 'Default',
                'path'        => $hooksDir,
                'system'      => true,
                'active'      => 0,
            ]
        );

        $r = $this->actingAs($this->superAdmin)
            ->get(route('setting.themes.activate', $theme));

        // OBS 1 FIX: Malicious hooks now produce a friendly 302 redirect with a
        // session error flash, not an HTTP 500, improving UX without hiding the failure.
        $r->assertStatus(302);
        $r->assertSessionHas('error');

        @unlink($hooksFile);
    });

    // ── BUG 1 FIX: call_user_func / call_user_func_array bypass ──

    test('hooks_with_call_user_func_is_rejected', function () {
        $hooksDir = base_path('themes/default');
        if (! is_dir($hooksDir)) {
            mkdir($hooksDir, 0755, true);
        }

        $hooksFile = $hooksDir . '/hooks.php';
        // Previously this bypassed validation because call_user_func was not in DANGEROUS_FUNCTIONS
        file_put_contents($hooksFile, '<?php call_user_func(\'system\', \'id\');');

        $theme = Themes::firstOrCreate(
            ['name' => 'default'],
            [
                'vendor'      => 'opendk',
                'version'     => '1.0',
                'description' => 'Default',
                'path'        => $hooksDir,
                'system'      => true,
                'active'      => 0,
            ]
        );

        $r = $this->actingAs($this->superAdmin)
            ->get(route('setting.themes.activate', $theme));

        // BUG 1 FIX: call_user_func is now in DANGEROUS_FUNCTIONS → rejected
        $r->assertStatus(302);
        $r->assertSessionHas('error');

        @unlink($hooksFile);
    });

    test('hooks_with_call_user_func_array_is_rejected', function () {
        $hooksDir = base_path('themes/default');
        if (! is_dir($hooksDir)) {
            mkdir($hooksDir, 0755, true);
        }

        $hooksFile = $hooksDir . '/hooks.php';
        file_put_contents($hooksFile, '<?php call_user_func_array(\'passthru\', [\'id\']);');

        $theme = Themes::firstOrCreate(
            ['name' => 'default'],
            [
                'vendor'      => 'opendk',
                'version'     => '1.0',
                'description' => 'Default',
                'path'        => $hooksDir,
                'system'      => true,
                'active'      => 0,
            ]
        );

        $r = $this->actingAs($this->superAdmin)
            ->get(route('setting.themes.activate', $theme));

        // BUG 1 FIX: call_user_func_array is now in DANGEROUS_FUNCTIONS → rejected
        $r->assertStatus(302);
        $r->assertSessionHas('error');

        @unlink($hooksFile);
    });

    // ── BUG 2 FIX: Variable function call bypass ──

    test('hooks_with_variable_function_call_is_rejected', function () {
        $hooksDir = base_path('themes/default');
        if (! is_dir($hooksDir)) {
            mkdir($hooksDir, 0755, true);
        }

        $hooksFile = $hooksDir . '/hooks.php';
        // Previously: $f = 'system'; $f('id'); would pass validation (T_VARIABLE + '(' not detected)
        file_put_contents($hooksFile, '<?php $f = \'system\'; $f(\'id\');');

        $theme = Themes::firstOrCreate(
            ['name' => 'default'],
            [
                'vendor'      => 'opendk',
                'version'     => '1.0',
                'description' => 'Default',
                'path'        => $hooksDir,
                'system'      => true,
                'active'      => 0,
            ]
        );

        $r = $this->actingAs($this->superAdmin)
            ->get(route('setting.themes.activate', $theme));

        // BUG 2 FIX: T_VARIABLE followed by '(' is now detected → rejected
        $r->assertStatus(302);
        $r->assertSessionHas('error');

        @unlink($hooksFile);
    });

    // ── Helpers ──

    function makeZipWithPhp(): UploadedFile
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid() . '.zip';
        $zip  = new ZipArchive();
        $zip->open($path, ZipArchive::CREATE);
        $zip->addFromString('evil-theme/composer.json', '{"name":"evil"}');
        $zip->addFromString('evil-theme/theme.json', '{"api_version":"v1"}');
        $zip->addFromString('evil-theme/hooks.php', '<?php file_put_contents("test.txt","ok");');
        $zip->close();

        return new UploadedFile($path, 'theme.zip', 'application/zip', null, true);
    }

    /**
     * BUG 3 FIX: Helper — creates a ZIP with a path traversal entry.
     */
    function makeZipWithPathTraversal(): UploadedFile
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid() . '.zip';
        $zip  = new ZipArchive();
        $zip->open($path, ZipArchive::CREATE);
        $zip->addFromString('evil-theme/composer.json', '{"name":"evil"}');
        $zip->addFromString('evil-theme/theme.json', '{"api_version":"v1"}');
        // Attempt to write outside themes/extracted/ via path traversal
        $zip->addFromString('../../../storage/logs/pwned.txt', 'pwned');
        $zip->close();

        return new UploadedFile($path, 'theme.zip', 'application/zip', null, true);
    }

})->group('security', 'theme-upload');