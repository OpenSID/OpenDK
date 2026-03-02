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

use App\Models\AkiAkb;
use App\Models\DataDesa;
use Tests\CrudTestCase;

beforeEach(function () {
    // Test setup if needed
});

describe('AKI AKB CRUD', function () {
    test('index displays aki akb list view', function () {
        $response = $this->get(route('data.aki-akb.index'));

        $response->assertStatus(200);
        $response->assertViewIs('data.aki_akb.index');
        $response->assertViewHas('page_title', 'AKI & AKB');
        $response->assertViewHas('page_description', 'Daftar Kematian Ibu & Bayi');
    });

    test('import displays import form', function () {
        $response = $this->get(route('data.aki-akb.import'));

        $response->assertStatus(200);
        $response->assertViewIs('data.aki_akb.import');
        $response->assertViewHas('page_title', 'AKI & AKB');
        $response->assertViewHas('page_description', 'Import AKI & AKB');
    });

    test('edit displays edit form', function () {
        $akib = AkiAkb::factory()->create();

        $response = $this->get(route('data.aki-akb.edit', $akib->id));

        $response->assertStatus(200);
        $response->assertViewIs('data.aki_akb.edit');
        $response->assertViewHas('akib', $akib);
        $response->assertViewHas('page_description');
    });

    test('update updates aki akb successfully', function () {
        $akib = AkiAkb::factory()->create();

        $updateData = [
            'aki' => 5,
            'akb' => 8,
            'bulan' => 6,
            'tahun' => 2024,
        ];

        $response = $this->put(route('data.aki-akb.update', $akib->id), $updateData);

        $response->assertRedirect(route('data.aki-akb.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('das_akib', [
            'id' => $akib->id,
            'aki' => 5,
            'akb' => 8,
        ]);
    });

    test('update fails with invalid data', function () {
        $akib = AkiAkb::factory()->create();

        $invalidData = [
            'aki' => '',
            'akb' => '',
        ];

        $response = $this->put(route('data.aki-akb.update', $akib->id), $invalidData);

        $response->assertSessionHasErrors(['aki', 'akb']);
    });

    test('destroy deletes aki akb successfully', function () {
        $akib = AkiAkb::factory()->create();

        $response = $this->delete(route('data.aki-akb.destroy', $akib->id));

        $response->assertRedirect(route('data.aki-akb.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('das_akib', [
            'id' => $akib->id,
        ]);
    });

    test('getDataAKIAKB returns JSON response for DataTables', function () {
        $desa = DataDesa::factory()->create();
        AkiAkb::factory()->count(3)->create(['desa_id' => $desa->desa_id]);

        $response = $this->get(route('data.aki-akb.getdata'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'desa_id',
                    'aki',
                    'akb',
                    'bulan',
                    'tahun',
                    'nama_desa',
                    'aksi',
                ],
            ],
        ]);
    });

    test('validation requires aki and akb', function () {
        $akib = AkiAkb::factory()->create();

        $invalidData = [
            'aki' => '',
            'akb' => '',
            'bulan' => 1,
            'tahun' => 2024,
        ];

        $response = $this->put(route('data.aki-akb.update', $akib->id), $invalidData);

        $response->assertSessionHasErrors(['aki', 'akb']);
    });
});
