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

namespace Tests\Feature\Content;

use App\Models\Navigation;
use Tests\CrudTestCase;

beforeEach(function () {
    // Test setup if needed
});

describe('Navigation CRUD', function () {
    test('index displays navigation list view', function () {
        $response = $this->get(route('setting.navigation.index'));

        $response->assertStatus(200);
        $response->assertViewIs('setting.navigation.index');
        $response->assertViewHas('page_title', 'Navigasi');
        $response->assertViewHas('page_description', 'Daftar Navigasi');
    });

    test('store creates new navigation successfully', function () {
        $validData = [
            'name' => 'Menu Baru',
            'slug' => 'menu-baru',
            'parent_id' => 0,
            'type' => 1,
            'url' => '/menu-baru',
            'order' => 1,
            'status' => 1,
        ];

        $response = $this->post(route('setting.navigation.store'), $validData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('das_navigation', [
            'name' => 'Menu Baru',
            'slug' => 'menu-baru',
        ]);
    });

    test('store fails with invalid data', function () {
        $invalidData = [
            'name' => '',
            'slug' => '',
            'type' => '',
            'url' => '',
        ];

        $response = $this->post(route('setting.navigation.store'), $invalidData);

        $response->assertSessionHasErrors(['name', 'type', 'url']);
    });

    test('update updates navigation successfully', function () {
        $navigation = Navigation::create([
            'name' => 'Original Menu',
            'slug' => 'original-menu',
            'parent_id' => 0,
            'type' => 1,
            'url' => '/original',
            'order' => 1,
            'status' => 1,
        ]);

        $updateData = [
            'name' => 'Updated Menu',
            'slug' => 'updated-menu',
            'parent_id' => 0,
            'type' => 2,
            'url' => 'https://external.com',
            'order' => 2,
            'status' => 0,
        ];

        $response = $this->put(route('setting.navigation.update', $navigation->id), $updateData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('das_navigation', [
            'id' => $navigation->id,
            'name' => 'Updated Menu',
            'status' => 0,
        ]);
    });

    test('destroy deletes navigation successfully', function () {
        $navigation = Navigation::create([
            'name' => 'Menu to Delete',
            'slug' => 'menu-to-delete',
            'parent_id' => 0,
            'type' => 1,
            'url' => '/delete',
            'order' => 1,
            'status' => 1,
        ]);

        $response = $this->delete(route('setting.navigation.destroy', $navigation->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('das_navigation', [
            'id' => $navigation->id,
        ]);
    });

    test('validation requires name', function () {
        $invalidData = [
            'name' => '',
            'slug' => 'test-slug',
            'type' => 1,
            'url' => '/test',
        ];

        $response = $this->post(route('setting.navigation.store'), $invalidData);

        $response->assertSessionHasErrors('name');
    });

    test('validation requires type', function () {
        $invalidData = [
            'name' => 'Test Menu',
            'slug' => 'test-menu',
            'type' => '',
            'url' => '/test',
        ];

        $response = $this->post(route('setting.navigation.store'), $invalidData);

        $response->assertSessionHasErrors('type');
    });
});
