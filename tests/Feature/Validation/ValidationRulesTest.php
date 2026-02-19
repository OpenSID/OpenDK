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

namespace Tests\Feature\Validation;

use App\Models\Imunisasi;
use Tests\CrudTestCase;

beforeEach(function () {
    // Test setup if needed
});

describe('Validation Rules', function () {
    describe('Data Desa Validation', function () {
        test('desa_id is required', function () {
            $response = $this->post(route('data.data-desa.store'), [
                'nama' => 'Test Desa',
                'sebutan_desa' => 'Desa',
            ]);

            $response->assertSessionHasErrors('desa_id');
        });

        test('nama is required', function () {
            $response = $this->post(route('data.data-desa.store'), [
                'desa_id' => '1234567890123',
                'sebutan_desa' => 'Desa',
            ]);

            $response->assertSessionHasErrors('nama');
        });
    });

    describe('Imunisasi Validation', function () {
        test('cakupan_imunisasi is required', function () {
            $imunisasi = Imunisasi::factory()->create();

            $response = $this->put(route('data.imunisasi.update', $imunisasi->id), [
                'bulan' => 1,
                'tahun' => 2024,
            ]);

            $response->assertSessionHasErrors('cakupan_imunisasi');
        });
    });

    describe('Artikel Validation', function () {
        test('judul is required', function () {
            $response = $this->post(route('informasi.artikel.store'), [
                'id_kategori' => 1,
                'isi' => 'Test content',
            ]);

            $response->assertSessionHasErrors('judul');
        });

        test('isi is required', function () {
            $response = $this->post(route('informasi.artikel.store'), [
                'judul' => 'Test Judul',
                'id_kategori' => 1,
            ]);

            $response->assertSessionHasErrors('isi');
        });
    });

    describe('Event Validation', function () {
        test('event_name is required', function () {
            $response = $this->post(route('informasi.event.store'), [
                'start' => '2024-12-01 09:00:00',
                'end' => '2024-12-01 17:00:00',
            ]);

            $response->assertSessionHasErrors('event_name');
        });
    });
});
