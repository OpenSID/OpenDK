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

namespace Tests\Feature\Keuangan;

use App\Models\Coa;
use App\Models\SubCoa;
use App\Models\SubSubCoa;
use Tests\CrudTestCase;

beforeEach(function () {
    // Clean up test data before each test for isolation using Eloquent models
    SubCoa::where('sub_name', 'LIKE', 'Test%')->delete();
    SubSubCoa::where('sub_sub_name', 'LIKE', 'Test%')->delete();
    Coa::where('coa_name', 'LIKE', 'Test%')->delete();
    
    // Create required test data using Eloquent models
    // For models with non-incrementing IDs, we need to set attributes and save
    $subCoa = new SubCoa();
    $subCoa->id = '1';
    $subCoa->type_id = '1';
    $subCoa->sub_name = 'Test Sub Coa';
    $subCoa->save();
    
    $subSubCoa = new SubSubCoa();
    $subSubCoa->id = '1';
    $subSubCoa->type_id = '1';
    $subSubCoa->sub_id = '1';
    $subSubCoa->sub_sub_name = 'Test Sub Sub Coa';
    $subSubCoa->save();
});

describe('COA CRUD', function () {
    test('index displays coa list view', function () {
        $response = $this->get(route('setting.coa.index'));

        $response->assertStatus(200);
        $response->assertViewIs('setting.coa.index');
        $response->assertViewHas('page_title', 'COA');
        $response->assertViewHas('page_description', 'Daftar COA');
    });

    test('create displays coa creation form', function () {
        $response = $this->get(route('setting.coa.create'));

        $response->assertStatus(200);
        $response->assertViewIs('setting.coa.create');
        $response->assertViewHas('page_title', 'COA');
        $response->assertViewHas('page_description', 'Tambah COA');
    });

    test('store creates new coa successfully', function () {
        $validData = [
            'type_id' => '1',
            'sub_id' => '1',
            'sub_sub_id' => '1',
            'coa_name' => 'Test COA Name',
            'id' => '01',
        ];

        $response = $this->post(route('setting.coa.store'), $validData);

        $response->assertRedirect(route('setting.coa.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('ref_coa', [
            'coa_name' => 'Test COA Name',
        ]);
    });

    test('store fails with invalid data', function () {
        $invalidData = [
            'type_id' => '',
            'sub_id' => '',
            'sub_sub_id' => '',
            'coa_name' => '',
            'id' => '',
        ];

        $response = $this->post(route('setting.coa.store'), $invalidData);

        $response->assertSessionHasErrors(['type_id', 'sub_id', 'sub_sub_id', 'coa_name', 'id']);
    });

    test('get_sub_coa returns sub coa list', function () {
        $subCoa = new SubCoa();
        $subCoa->id = '99';
        $subCoa->type_id = '1';
        $subCoa->sub_name = 'Test Sub Coa 99';
        $subCoa->save();

        $response = $this->get(route('setting.coa.sub_coa', 1));

        $response->assertStatus(200);
        $response->assertJsonFragment(['sub_name' => 'Test Sub Coa 99']);
    });

    test('get_sub_sub_coa returns sub sub coa list', function () {
        $subSubCoa = new SubSubCoa();
        $subSubCoa->id = '99';
        $subSubCoa->type_id = '1';
        $subSubCoa->sub_id = '1';
        $subSubCoa->sub_sub_name = 'Test Sub Sub Coa 99';
        $subSubCoa->save();

        $response = $this->get(route('setting.coa.sub_sub_coa', [1, 1]));

        $response->assertStatus(200);
        $response->assertJsonFragment(['sub_sub_name' => 'Test Sub Sub Coa 99']);
    });    
});
