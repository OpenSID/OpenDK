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

namespace Tests\Feature\MasterData;

use App\Models\DataDesa;
use App\Models\Profil;
use App\Models\Imunisasi;
use Tests\Traits\DisableDatabaseGabungan;

uses(DisableDatabaseGabungan::class);

beforeEach(function () {
    // Create profil kecamatan untuk requirement test
    // ValidDesa rule checks if first 6 digits of desa_id match kecamatan_id
    Profil::updateOrCreate(
        ['id' => 1],
        [
            'nama' => 'Kecamatan Test',
            'nama_kecamatan' => 'Cerme',
            'kecamatan_id' => '330101',
            'provinsi_id' => '33',
            'kabupaten_id' => '3301',
        ]
    );
    $this->disableDatabaseGabungan();
    // Clear cache
    cache()->forget('profil');
});

describe('Data Desa CRUD', function () {
    test('index displays data desa list view', function () {
        $response = $this->get(route('data.data-desa.index'));

        $response->assertStatus(200);
        $response->assertViewIs('data.data_desa.index');
        $response->assertViewHas('page_title', config('setting.sebutan_desa', 'Desa'));
    });

    test('create displays data desa creation form', function () {
        $response = $this->get(route('data.data-desa.create'));

        $response->assertStatus(200);
        $response->assertViewIs('data.data_desa.create');
        $response->assertViewHas('page_title', config('setting.sebutan_desa', 'Desa'));
        $response->assertViewHas('page_description', 'Tambah Desa');
    });

    test('store fails with invalid data', function () {
        $invalidData = [
            'desa_id' => '', // required
            'nama' => '', // required
        ];

        $response = $this->post(route('data.data-desa.store'), $invalidData);

        $response->assertSessionHasErrors(['desa_id', 'nama']);
    });

    test('edit displays edit form', function () {
        $desa = DataDesa::create([
            'desa_id' => '3301010001003',
            'nama' => 'Test Desa Edit',
            'sebutan_desa' => 'Desa',
            'website' => 'https://test.com',
            'luas_wilayah' => 100.00,
            'path' => null,
        ]);

        $response = $this->get(route('data.data-desa.edit', $desa->id));

        $response->assertStatus(200);
        $response->assertViewIs('data.data_desa.edit');
        $response->assertViewHas('page_description');
    });

    test('destroy deletes data desa successfully', function () {
        $desa = DataDesa::create([
            'desa_id' => '3301010001004',
            'nama' => 'Test Desa Delete',
            'sebutan_desa' => 'Desa',
            'website' => 'https://test.com',
            'luas_wilayah' => 100.00,
            'path' => null,
        ]);

        $response = $this->delete(route('data.data-desa.destroy', $desa->id));

        $response->assertRedirect(route('data.data-desa.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('das_data_desa', [
            'id' => $desa->id,
        ]);
    });

    test('destroy fails if desa is used in other modules', function () {
        $desa = DataDesa::create([
            'desa_id' => '3301010001005',
            'nama' => 'Test Desa With Relation',
            'sebutan_desa' => 'Desa',
            'website' => 'https://test.com',
            'luas_wilayah' => 100.00,
            'path' => null,
        ]);

        // Create related data that would prevent deletion
        Imunisasi::create([
            'desa_id' => '3301010001005',
            'cakupan_imunisasi' => 80,
            'bulan' => 1,
            'tahun' => 2024,
        ]);

        $response = $this->delete(route('data.data-desa.destroy', $desa->id));

        $response->assertRedirect(route('data.data-desa.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('das_data_desa', [
            'id' => $desa->id,
        ]);
    });

    test('getDataDesa returns JSON response for DataTables', function () {
        DataDesa::create([
            'desa_id' => '3301010001006',
            'nama' => 'Test Desa 1',
            'sebutan_desa' => 'Desa',
            'website' => 'https://test1.com',
            'luas_wilayah' => 100.00,
            'path' => null,
        ]);

        DataDesa::create([
            'desa_id' => '3301010001007',
            'nama' => 'Test Desa 2',
            'sebutan_desa' => 'Desa',
            'website' => 'https://test2.com',
            'luas_wilayah' => 200.00,
            'path' => null,
        ]);

        DataDesa::create([
            'desa_id' => '3301010001008',
            'nama' => 'Test Desa 3',
            'sebutan_desa' => 'Desa',
            'website' => 'https://test3.com',
            'luas_wilayah' => 300.00,
            'path' => null,
        ]);

        $response = $this->get(route('data.data-desa.getdata'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'desa_id',
                    'nama',
                    'sebutan_desa',
                    'website',
                    'luas_wilayah',
                    'aksi',
                ],
            ],
        ]);
    });

    test('validation requires unique desa_id', function () {
        DataDesa::create([
            'desa_id' => '3301010001009',
            'nama' => 'Existing Desa',
            'sebutan_desa' => 'Desa',
            'website' => 'https://existing.com',
            'luas_wilayah' => 100.00,
            'path' => null,
        ]);

        $duplicateData = [
            'desa_id' => '3301010001009',
            'nama' => 'Another Desa',
            'sebutan_desa' => 'Desa',
            'website' => fake()->url,
            'luas_wilayah' => 100.00,
        ];

        $response = $this->post(route('data.data-desa.store'), $duplicateData);

        $response->assertSessionHasErrors('desa_id');
    });
});
