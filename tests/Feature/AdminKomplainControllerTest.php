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

namespace Tests\Feature\Http\Controllers\Data;

use App\Models\JawabKomplain;
use App\Models\KategoriKomplain;
use App\Models\Komplain;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CrudTestCase;

class AdminKomplainControllerTest extends CrudTestCase
{
    use DatabaseTransactions;

    public function test_index()
    {
        $response = $this->get(route('admin-komplain.index'));
        $response->assertStatus(200);
        $response->assertViewIs('sistem_komplain.index');
    }

    public function test_get_data_komplain()
    {
        KategoriKomplain::factory()->create();
        Komplain::factory()->create();
        $response = $this->getJson(route('admin-komplain.getdata'));
        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    public function test_disetujui()
    {
        $komplain = Komplain::factory()->create(['status' => 'REVIEW']);
        $response = $this->put(route('admin-komplain.setuju', $komplain->id), [
            'status' => 'SELESAI'
        ]);
        $response->assertRedirect(route('admin-komplain.index'));
        $this->assertDatabaseHas('das_komplain', [
            'id' => $komplain->id,
            'status' => 'SELESAI'
        ]);
    }

    public function test_anonim()
    {
        $komplain = Komplain::factory()->create(['anonim' => 0]);
        $response = $this->put(route('admin-komplain.anonim', $komplain->id), [
            'anonim' => 1
        ]);
        $response->assertRedirect(route('admin-komplain.index'));
        $this->assertDatabaseHas('das_komplain', [
            'id' => $komplain->id,
            'anonim' => 1
        ]);
    }

    public function test_show()
    {
        $komplain = Komplain::factory()->create();
        $response = $this->get(route('admin-komplain.show', $komplain->id));
        $response->assertStatus(200);
        $response->assertViewIs('sistem_komplain.show');
    }

    public function test_edit()
    {
        $komplain = Komplain::factory()->create();
        $response = $this->get(route('admin-komplain.edit', $komplain->id));
        $response->assertStatus(200);
        $response->assertViewIs('sistem_komplain.edit');
    }

    public function test_update()
    {
        $kategori = KategoriKomplain::factory()->create();
        $penduduk = $this->getPenduduk();
        $komplain = Komplain::factory()->create(['kategori' => $kategori->id, 'nik' => $penduduk->nik]);
        $response = $this->put(route('admin-komplain.update', $komplain->id), [
            'nik' => $komplain->nik,
            'judul' => 'Judul Baru',
            'kategori' => $kategori->id,
            'laporan' => 'Isi laporan baru',
        ]);
        $response->assertRedirect(route('admin-komplain.index'));
        $this->assertDatabaseHas('das_komplain', [
            'id' => $komplain->id,
            'judul' => 'Judul Baru'
        ]);
    }

    public function test_update_komentar()
    {
        $penduduk = $this->getPenduduk();
        $jawab = JawabKomplain::factory()->create(['penjawab' => $penduduk->nik]);
        $response = $this->put(route('admin-komplain.updatekomentar', $jawab->id), [
            'jawaban' => 'Jawaban baru'
        ]);
        $response->assertJson(['status' => 'success']);
        $this->assertDatabaseHas('das_jawab_komplain', [
            'id' => $jawab->id,
            'jawaban' => 'Jawaban baru'
        ]);
    }

    public function test_statistik()
    {
        $response = $this->get(route('admin-komplain.statistik'));
        $response->assertStatus(200);
        $response->assertViewIs('sistem_komplain.statistik');
    }

    public function test_get_komentar()
    {
        $jawab = JawabKomplain::factory()->create();
        $response = $this->get(route('admin-komplain.getkomentar', $jawab->id));
        $response->assertJson(['status' => 'success']);
    }

    public function test_destroy()
    {
        $komplain = Komplain::factory()->create();
        $response = $this->delete(route('admin-komplain.destroy', $komplain->id));
        $response->assertRedirect(route('admin-komplain.index'));
        $this->assertDatabaseMissing('das_komplain', [
            'id' => $komplain->id
        ]);
    }

    public function test_delete_komentar()
    {
        $jawab = JawabKomplain::factory()->create();
        $response = $this->delete(route('admin-komplain.deletekomentar', $jawab->id));
        $this->assertDatabaseMissing('das_jawab_komplain', [
            'id' => $jawab->id
        ]);
    }

    private function getPenduduk()
    {
        $penduduk = \App\Models\Penduduk::inRandomOrder()->first();
        return $penduduk ?: \App\Models\Penduduk::factory()->create();
    }
}
