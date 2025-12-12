<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace Tests\Feature;

use App\Models\DataDesa;
use App\Models\Pesan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CrudTestCase;

class PesanControllerTest extends CrudTestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a user and authenticate
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
    }

    public function test_index()
    {
        Pesan::factory()->count(2)->create(['jenis' => Pesan::PESAN_MASUK]);
        $response = $this->get(route('pesan.index'));
        $response->assertStatus(200);
        $response->assertViewIs('pesan.masuk.index');
    }

    public function test_load_pesan_keluar()
    {
        $this->markTestSkipped('Test skipped due to complex view dependencies.');
    }

    public function test_load_pesan_arsip()
    {
        $this->markTestSkipped('Test skipped due to complex view dependencies.');
    }

    public function test_read_pesan()
    {
        $pesan = Pesan::factory()->create(['sudah_dibaca' => Pesan::BELUM_DIBACA]);
        $response = $this->get(route('pesan.read', $pesan->id));
        $response->assertStatus(200);
        $response->assertViewIs('pesan.read_pesan');
        $this->assertDatabaseHas((new Pesan())->getTable(), [
            'id' => $pesan->id,
            'sudah_dibaca' => Pesan::SUDAH_DIBACA,
        ]);
    }

    public function test_compose_pesan()
    {
        $response = $this->get(route('pesan.compose'));
        $response->assertStatus(200);
        $response->assertViewIs('pesan.compose_pesan');
    }

    public function test_store_compose_pesan_success()
    {
        $desa = DataDesa::factory()->create();
        $response = $this->post(route('pesan.compose.post'), [
            'judul' => 'Judul Pesan',
            'das_data_desa_id' => $desa->desa_id,
            'text' => 'Isi pesan'
        ]);
        
        $response->assertRedirect(route('pesan.keluar'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas((new Pesan())->getTable(), [
            'judul' => 'Judul Pesan',
            'das_data_desa_id' => $desa->desa_id,
        ]);
    }

    public function test_store_compose_pesan_failed()
    {
        $response = $this->post(route('pesan.compose.post'), []);
        $response->assertRedirect(url('/'));
    }

    public function test_set_arsip_pesan()
    {
        $pesan = Pesan::factory()->create(['diarsipkan' => Pesan::NON_ARSIP]);
        $response = $this->post(route('pesan.arsip.post'), ['id' => $pesan->id]);
        $response->assertRedirect(route('pesan.arsip'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas($pesan->getTable(), [
            'id' => $pesan->id,
            'diarsipkan' => Pesan::MASUK_ARSIP,
        ]);
    }

    public function test_set_multiple_read_pesan_status()
    {
        $pesan = Pesan::factory()->count(2)->create(['sudah_dibaca' => Pesan::BELUM_DIBACA]);
        $ids = $pesan->pluck('id')->toArray();
        $response = $this->post(route('pesan.read.multiple'), [
            'array_id' => json_encode($ids),
        ]);
        $response->assertSessionHas('success');
        foreach ($ids as $id) {
            $this->assertDatabaseHas((new Pesan())->getTable(), [
                'id' => $id,
                'sudah_dibaca' => Pesan::SUDAH_DIBACA,
            ]);
        }
    }

    public function test_set_multiple_arsip_pesan_status()
    {
        $pesan = Pesan::factory()->count(2)->create(['diarsipkan' => Pesan::NON_ARSIP]);
        $ids = $pesan->pluck('id')->toArray();
        $response = $this->post(route('pesan.arsip.multiple'), [
            'array_id' => json_encode($ids),
        ]);
        $response->assertSessionHas('success');
        foreach ($ids as $id) {
            $this->assertDatabaseHas((new Pesan())->getTable(), [
                'id' => $id,
                'diarsipkan' => Pesan::MASUK_ARSIP,
            ]);
        }
    }
}
