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

namespace Tests\Feature\Settings;

use App\Models\SettingAplikasi;
use Tests\CrudTestCase;

beforeEach(function () {
    // Initialize test data for setting aplikasi using Eloquent model
    SettingAplikasi::updateOrCreate(
        ['key' => 'website_title'],
        [
            'value' => 'Default Website Title',
            'kategori' => 'umum',
            'type' => 'text',
            'description' => 'Website Title Setting',
            'option' => json_encode([]),
        ]
    );
    
    SettingAplikasi::updateOrCreate(
        ['key' => 'website_description'],
        [
            'value' => 'Default Website Description',
            'kategori' => 'umum',
            'type' => 'textarea',
            'description' => 'Website Description Setting',
            'option' => json_encode([]),
        ]
    );
});

describe('Setting Aplikasi CRUD', function () {
    test('index displays setting aplikasi list view', function () {
        $response = $this->get(route('setting.aplikasi.index'));

        $response->assertStatus(200);
        $response->assertViewIs('setting.aplikasi.index');
        $response->assertViewHas('page_title', 'Pengaturan Aplikasi');
        $response->assertViewHas('page_description', 'Daftar Pengaturan Aplikasi');
    });

    test('edit displays edit form', function () {
        $setting = SettingAplikasi::where('key', 'website_title')->first();

        $response = $this->get(route('setting.aplikasi.edit', $setting->id));

        $response->assertStatus(200);
        $response->assertViewIs('setting.aplikasi.edit');
        $response->assertViewHas('aplikasi', $setting);
        $response->assertViewHas('page_description', 'Ubah Pengaturan Aplikasi');
    });

    test('update updates setting successfully', function () {
        $setting = SettingAplikasi::where('key', 'website_title')->first();

        $updateData = [
            'value' => 'Updated Website Title',
        ];

        $response = $this->put(route('setting.aplikasi.update', $setting->id), $updateData);

        $response->assertRedirect(route('setting.aplikasi.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('das_setting', [
            'id' => $setting->id,
            'value' => 'Updated Website Title',
        ]);
    });

    test('update fails with invalid data', function () {
        $setting = SettingAplikasi::where('key', 'website_title')->first();

        $invalidData = [
            'value' => '',
        ];

        $response = $this->put(route('setting.aplikasi.update', $setting->id), $invalidData);

        $response->assertSessionHasErrors('value');
    });

    test('settings are loaded correctly', function () {
        $response = $this->get(route('setting.aplikasi.index'));

        $response->assertStatus(200);
        $response->assertViewHas('settings');
    });

    test('settings with kategori surat are excluded', function () {
        // Create surat setting using Eloquent model
        SettingAplikasi::updateOrCreate(
            ['key' => 'surat_setting'],
            [
                'value' => 'Surat Value',
                'kategori' => 'surat',
                'type' => 'text',
                'description' => 'Surat Setting',
                'option' => json_encode([]),
            ]
        );

        $response = $this->get(route('setting.aplikasi.index'));

        $response->assertStatus(200);

        $settings = $response->viewData('settings');
        $suratSettings = $settings->filter(function ($setting) {
            return $setting->kategori === 'surat';
        });

        expect($suratSettings->count())->toBe(0);
    });

    test('validation requires value field', function () {
        $setting = SettingAplikasi::where('key', 'website_title')->first();

        $invalidData = [
            'value' => null,
        ];

        $response = $this->put(route('setting.aplikasi.update', $setting->id), $invalidData);

        $response->assertSessionHasErrors('value');
    });

    test('update clears cache after successful update', function () {
        $setting = SettingAplikasi::where('key', 'website_title')->first();

        // Set cache
        \Illuminate\Support\Facades\Cache::put('setting', 'cached_value', 3600);

        $updateData = [
            'value' => 'New Value',
        ];

        $response = $this->put(route('setting.aplikasi.update', $setting->id), $updateData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Cache should be cleared after update
        $this->assertNull(\Illuminate\Support\Facades\Cache::get('setting'));
    });
});
