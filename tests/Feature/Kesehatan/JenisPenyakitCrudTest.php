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

namespace Tests\Feature\Kesehatan;

use App\Models\JenisPenyakit;
use Tests\CrudTestCase;

beforeEach(function () {
    // Test setup if needed
});

describe('Jenis Penyakit CRUD', function () {
    test('index displays jenis penyakit list view', function () {
        $response = $this->get(route('setting.jenis-penyakit.index'));

        $response->assertStatus(200);
        $response->assertViewIs('setting.jenis_penyakit.index');
        $response->assertViewHas('page_title', 'Jenis Penyakit');
        $response->assertViewHas('page_description', 'Daftar Jenis Penyakit');
    });

    test('store creates new jenis penyakit successfully', function () {
        $validData = [
            'nama' => 'Penyakit Baru',
        ];

        $response = $this->post(route('setting.jenis-penyakit.store'), $validData);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('ref_penyakit', [
            'nama' => 'Penyakit Baru',
        ]);
    });

    test('store fails with invalid data', function () {
        $invalidData = [
            'nama' => '',
        ];

        $response = $this->post(route('setting.jenis-penyakit.store'), $invalidData);

        $response->assertSessionHasErrors('nama');
    });

    test('edit returns jenis penyakit data as JSON', function () {
        $jenisPenyakit = JenisPenyakit::factory()->create();

        $response = $this->get(route('setting.jenis-penyakit.edit', $jenisPenyakit->id));

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $jenisPenyakit->id,
            'nama' => $jenisPenyakit->nama,
        ]);
    });

    test('update updates jenis penyakit successfully', function () {
        $jenisPenyakit = JenisPenyakit::factory()->create();

        $updateData = [
            'nama' => 'Updated Penyakit Name',
        ];

        $response = $this->put(route('setting.jenis-penyakit.update', $jenisPenyakit->id), $updateData);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('ref_penyakit', [
            'id' => $jenisPenyakit->id,
            'nama' => 'Updated Penyakit Name',
        ]);
    });

    test('update fails with invalid data', function () {
        $jenisPenyakit = JenisPenyakit::factory()->create();

        $invalidData = [
            'nama' => '',
        ];

        $response = $this->put(route('setting.jenis-penyakit.update', $jenisPenyakit->id), $invalidData);

        $response->assertSessionHasErrors('nama');
    });

    test('destroy deletes jenis penyakit successfully', function () {
        $jenisPenyakit = JenisPenyakit::factory()->create();

        $response = $this->delete(route('setting.jenis-penyakit.destroy', $jenisPenyakit->id));

        $response->assertRedirect(route('setting.jenis-penyakit.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('ref_penyakit', [
            'id' => $jenisPenyakit->id,
        ]);
    });

    test('getData returns JSON response for DataTables', function () {
        JenisPenyakit::factory()->count(5)->create();

        $response = $this->get(route('setting.jenis-penyakit.getdata'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'nama',
                    'aksi',
                ],
            ],
        ]);
    });    

    test('validation requires nama field', function () {
        $invalidData = [
            'nama' => null,
        ];

        $response = $this->post(route('setting.jenis-penyakit.store'), $invalidData);

        $response->assertSessionHasErrors('nama');
    });
});
