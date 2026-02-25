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

use App\Models\DataDesa;
use App\Models\Imunisasi;
use Tests\CrudTestCase;

beforeEach(function () {
    // Test setup if needed
});

describe('Imunisasi CRUD', function () {
    test('index displays imunisasi list view', function () {
        $response = $this->get(route('data.imunisasi.index'));

        $response->assertStatus(200);
        $response->assertViewIs('data.imunisasi.index');
        $response->assertViewHas('page_title', 'Imunisasi');
        $response->assertViewHas('page_description', 'Daftar Imunisasi');
    });

    test('import displays import form', function () {
        $response = $this->get(route('data.imunisasi.import'));

        $response->assertStatus(200);
        $response->assertViewIs('data.imunisasi.import');
        $response->assertViewHas('page_title', 'Imunisasi');
        $response->assertViewHas('page_description', 'Impor Imunisasi');
    });

    test('edit displays edit form', function () {
        $imunisasi = Imunisasi::factory()->create();

        $response = $this->get(route('data.imunisasi.edit', $imunisasi->id));

        $response->assertStatus(200);
        $response->assertViewIs('data.imunisasi.edit');
        $response->assertViewHas('imunisasi', $imunisasi);
        $response->assertViewHas('page_description');
    });

    test('update updates imunisasi successfully', function () {
        $imunisasi = Imunisasi::factory()->create();

        $updateData = [
            'cakupan_imunisasi' => 95,
            'bulan' => 6,
            'tahun' => 2024,
        ];

        $response = $this->put(route('data.imunisasi.update', $imunisasi->id), $updateData);

        $response->assertRedirect(route('data.imunisasi.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('das_imunisasi', [
            'id' => $imunisasi->id,
            'cakupan_imunisasi' => 95,
        ]);
    });

    test('update fails with invalid data', function () {
        $imunisasi = Imunisasi::factory()->create();

        $invalidData = [
            'cakupan_imunisasi' => '',
        ];

        $response = $this->put(route('data.imunisasi.update', $imunisasi->id), $invalidData);

        $response->assertSessionHasErrors('cakupan_imunisasi');
    });

    test('destroy deletes imunisasi successfully', function () {
        $imunisasi = Imunisasi::factory()->create();

        $response = $this->delete(route('data.imunisasi.destroy', $imunisasi->id));

        $response->assertRedirect(route('data.imunisasi.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('das_imunisasi', [
            'id' => $imunisasi->id,
        ]);
    });

    test('getDataAKIAKB returns JSON response for DataTables', function () {
        $desa = DataDesa::factory()->create();
        Imunisasi::factory()->count(3)->create(['desa_id' => $desa->desa_id]);

        $response = $this->get(route('data.imunisasi.getdata'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'desa_id',
                    'cakupan_imunisasi',
                    'bulan',
                    'tahun',
                    'nama_desa',
                    'aksi',
                ],
            ],
        ]);
    });

    test('validation requires cakupan_imunisasi', function () {
        $desa = DataDesa::factory()->create();

        $invalidData = [
            'cakupan_imunisasi' => '',
            'bulan' => 1,
            'tahun' => 2024,
            'desa_id' => $desa->desa_id,
        ];

        $response = $this->put(route('data.imunisasi.update', 1), $invalidData);

        $response->assertSessionHasErrors('cakupan_imunisasi');
    });    
});
