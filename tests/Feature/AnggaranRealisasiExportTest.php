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

namespace Tests\Feature;

use App\Models\AnggaranRealisasi;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class AnggaranRealisasiExportTest extends TestCase
{
    use DatabaseTransactions, WithoutMiddleware;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user untuk testing
        $this->user = User::factory()->create();
    }

    protected function tearDown(): void
    {
        // Bersihkan data test
        AnggaranRealisasi::query()->delete();
        User::query()->delete();

        parent::tearDown();
    }

    /** @test */
    public function dapat_mengakses_halaman_export_anggaran_realisasi()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('data.anggaran-realisasi.export-excel'));

        $response->assertSuccessful();
        $this->assertEquals('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $response->headers->get('Content-Type'));
    }

    /** @test */
    public function dapat_export_data_anggaran_realisasi_kosong()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('data.anggaran-realisasi.export-excel'));

        $response->assertSuccessful();
        $this->assertEquals('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $response->headers->get('Content-Type'));
    }

    /** @test */
    public function dapat_export_data_anggaran_realisasi_dengan_data()
    {
        $this->actingAs($this->user);

        // Buat data anggaran realisasi untuk testing
        AnggaranRealisasi::factory()->count(3)->create();

        $response = $this->get(route('data.anggaran-realisasi.export-excel'));

        $response->assertSuccessful();
        $this->assertEquals('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $response->headers->get('Content-Type'));
    }

    /** @test */
    public function export_anggaran_realisasi_menghasilkan_filename_dengan_timestamp()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('data.anggaran-realisasi.export-excel'));

        $response->assertSuccessful();

        $disposition = $response->headers->get('Content-Disposition');
        $this->assertStringContainsString('data-anggaran-realisasi-', $disposition);
        $this->assertStringContainsString('.xlsx', $disposition);
    }

    /** @test */
    public function export_anggaran_realisasi_memiliki_header_yang_benar()
    {
        $this->actingAs($this->user);

        // Buat data untuk memastikan ada konten
        AnggaranRealisasi::factory()->create();

        $response = $this->get(route('data.anggaran-realisasi.export-excel'));

        $response->assertSuccessful();
        $this->assertEquals('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $response->headers->get('Content-Type'));
    }
}
