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

use App\Enums\{LogVerifikasiSurat, StatusSurat};
use App\Http\Middleware\{Authenticate, CompleteProfile};
use App\Models\{DataDesa, Surat};
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Middleware\{PermissionMiddleware, RoleMiddleware};
use Tests\TestCase;

class FilterDesaSuratTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withViewErrors([]);
        $this->withoutMiddleware([
            Authenticate::class,
            RoleMiddleware::class,
            PermissionMiddleware::class,
            CompleteProfile::class,
        ]);
    }

    public function test_filter_desa_di_halaman_permohonan_dengan_desa_tertentu()
    {
        // Buat data desa yang berbeda
        $desaId1 = '3201.01.2001';
        $desaId2 = '3201.01.2002';

        DataDesa::factory()->create(['desa_id' => $desaId1, 'nama' => 'Desa Test 1']);
        DataDesa::factory()->create(['desa_id' => $desaId2, 'nama' => 'Desa Test 2']);

        // Buat surat permohonan untuk masing-masing desa
        Surat::factory()->count(2)->create([
            'desa_id' => $desaId1,
            'status' => StatusSurat::Permohonan,
        ]);

        Surat::factory()->create([
            'desa_id' => $desaId2,
            'status' => StatusSurat::Permohonan,
        ]);

        // Test filter dengan desa tertentu (tanpa titik sesuai format di view)
        $response = $this->getJson(route('surat.permohonan.getdata', [
            'kode_desa' => str_replace('.', '', $desaId1)
        ]));

        $response->assertStatus(200);
        $data = $response->json();

        // Verifikasi bahwa endpoint berjalan dengan baik
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('recordsTotal', $data);
    }

    public function test_filter_desa_di_halaman_permohonan_dengan_semua_desa()
    {
        // Buat beberapa data desa dan surat
        $desaId1 = '3201.01.2003';
        $desaId2 = '3201.01.2004';

        DataDesa::factory()->create(['desa_id' => $desaId1]);
        DataDesa::factory()->create(['desa_id' => $desaId2]);

        Surat::factory()->create([
            'desa_id' => $desaId1,
            'status' => StatusSurat::Permohonan,
        ]);

        Surat::factory()->create([
            'desa_id' => $desaId2,
            'status' => StatusSurat::Permohonan,
        ]);

        // Test filter dengan "Semua" desa
        $response = $this->getJson(route('surat.permohonan.getdata', [
            'kode_desa' => 'Semua'
        ]));

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('recordsTotal', $data);
    }

    public function test_filter_desa_di_halaman_permohonan_tanpa_parameter()
    {
        // Buat data surat
        Surat::factory()->create([
            'desa_id' => '3201.01.2005',
            'status' => StatusSurat::Permohonan,
        ]);

        // Test tanpa parameter filter (default)
        $response = $this->getJson(route('surat.permohonan.getdata'));
        $response->assertStatus(200);

        $data = $response->json();
        $this->assertArrayHasKey('data', $data);
    }

    public function test_filter_desa_di_halaman_permohonan_ditolak_dengan_desa_tertentu()
    {
        // Buat data desa
        $desaId1 = '3201.01.2006';
        $desaId2 = '3201.01.2007';

        DataDesa::factory()->create(['desa_id' => $desaId1]);
        DataDesa::factory()->create(['desa_id' => $desaId2]);

        // Buat surat ditolak untuk masing-masing desa
        Surat::factory()->count(2)->create([
            'desa_id' => $desaId1,
            'status' => StatusSurat::Ditolak,
            'log_verifikasi' => LogVerifikasiSurat::Ditolak,
        ]);

        Surat::factory()->create([
            'desa_id' => $desaId2,
            'status' => StatusSurat::Ditolak,
            'log_verifikasi' => LogVerifikasiSurat::Ditolak,
        ]);

        // Test filter dengan desa tertentu
        $response = $this->getJson(route('surat.permohonan.getdataditolak', [
            'kode_desa' => str_replace('.', '', $desaId1)
        ]));

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('recordsTotal', $data);
    }

    public function test_filter_desa_di_halaman_permohonan_ditolak_dengan_semua_desa()
    {
        // Buat data
        $desaId = '3201.01.2008';
        DataDesa::factory()->create(['desa_id' => $desaId]);

        Surat::factory()->create([
            'desa_id' => $desaId,
            'status' => StatusSurat::Ditolak,
            'log_verifikasi' => LogVerifikasiSurat::Ditolak,
        ]);

        // Test filter dengan semua desa
        $response = $this->getJson(route('surat.permohonan.getdataditolak', [
            'kode_desa' => 'Semua'
        ]));

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertArrayHasKey('data', $data);
    }

    public function test_filter_desa_di_halaman_permohonan_ditolak_tanpa_parameter()
    {
        // Buat surat ditolak
        Surat::factory()->create([
            'desa_id' => '3201.01.2009',
            'status' => StatusSurat::Ditolak,
            'log_verifikasi' => LogVerifikasiSurat::Ditolak,
        ]);

        // Test tanpa parameter filter
        $response = $this->getJson(route('surat.permohonan.getdataditolak'));
        $response->assertStatus(200);

        $data = $response->json();
        $this->assertArrayHasKey('data', $data);
    }

    public function test_filter_desa_di_halaman_arsip_dengan_desa_tertentu()
    {
        // Buat data desa yang berbeda
        $desaId1 = '3201.01.2010';
        $desaId2 = '3201.01.2011';

        DataDesa::factory()->create(['desa_id' => $desaId1]);
        DataDesa::factory()->create(['desa_id' => $desaId2]);

        // Buat surat arsip untuk masing-masing desa
        Surat::factory()->count(3)->create([
            'desa_id' => $desaId1,
            'status' => StatusSurat::Arsip,
        ]);

        Surat::factory()->create([
            'desa_id' => $desaId2,
            'status' => StatusSurat::Arsip,
        ]);

        // Test filter dengan desa tertentu (tanpa titik sesuai format di controller arsip)
        $response = $this->getJson(route('surat.arsip.getdata', [
            'kode_desa' => str_replace('.', '', $desaId1)
        ]));

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('recordsTotal', $data);
    }

    public function test_filter_desa_di_halaman_arsip_dengan_semua_desa()
    {
        // Buat data
        $desaId1 = '3201.01.2012';
        $desaId2 = '3201.01.2013';

        DataDesa::factory()->create(['desa_id' => $desaId1]);
        DataDesa::factory()->create(['desa_id' => $desaId2]);

        Surat::factory()->create([
            'desa_id' => $desaId1,
            'status' => StatusSurat::Arsip,
        ]);

        Surat::factory()->create([
            'desa_id' => $desaId2,
            'status' => StatusSurat::Arsip,
        ]);

        // Test filter dengan semua desa
        $response = $this->getJson(route('surat.arsip.getdata', [
            'kode_desa' => 'Semua'
        ]));

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertArrayHasKey('data', $data);
    }

    public function test_filter_desa_di_halaman_arsip_tanpa_parameter()
    {
        // Buat surat arsip
        Surat::factory()->create([
            'desa_id' => '3201.01.2014',
            'status' => StatusSurat::Arsip,
        ]);

        // Test tanpa parameter filter
        $response = $this->getJson(route('surat.arsip.getdata'));
        $response->assertStatus(200);

        $data = $response->json();
        $this->assertArrayHasKey('data', $data);
    }

    public function test_filter_permohonan_mengembalikan_data_sesuai_desa_yang_dipilih()
    {
        // Buat data desa spesifik
        $desaId1 = '3201.01.2015';
        $desaId2 = '3201.01.2016';

        DataDesa::factory()->create(['desa_id' => $desaId1, 'nama' => 'Desa Filter 1']);
        DataDesa::factory()->create(['desa_id' => $desaId2, 'nama' => 'Desa Filter 2']);

        // Buat 2 surat untuk desa 1 dan 1 surat untuk desa 2
        Surat::factory()->count(2)->create([
            'desa_id' => $desaId1,
            'status' => StatusSurat::Permohonan,
        ]);

        Surat::factory()->create([
            'desa_id' => $desaId2,
            'status' => StatusSurat::Permohonan,
        ]);

        // Filter untuk desa 1 saja (tanpa titik)
        $response = $this->getJson(route('surat.permohonan.getdata', [
            'kode_desa' => str_replace('.', '', $desaId1),
            'page[size]' => 10,
            'page[number]' => 1,
        ]));

        $response->assertStatus(200);
        $data = $response->json();

        // Verifikasi bahwa data yang dikembalikan minimal ada 2 record
        $this->assertGreaterThanOrEqual(2, $data['recordsTotal']);
    }

    public function test_filter_arsip_mengembalikan_data_sesuai_desa_yang_dipilih()
    {
        // Buat data desa spesifik
        $desaId1 = '3201.01.2017';
        $desaId2 = '3201.01.2018';

        DataDesa::factory()->create(['desa_id' => $desaId1, 'nama' => 'Desa Arsip 1']);
        DataDesa::factory()->create(['desa_id' => $desaId2, 'nama' => 'Desa Arsip 2']);

        // Buat 3 surat arsip untuk desa 1 dan 1 surat untuk desa 2
        Surat::factory()->count(3)->create([
            'desa_id' => $desaId1,
            'status' => StatusSurat::Arsip,
        ]);

        Surat::factory()->create([
            'desa_id' => $desaId2,
            'status' => StatusSurat::Arsip,
        ]);

        // Filter untuk desa 1 saja
        $response = $this->getJson(route('surat.arsip.getdata', [
            'kode_desa' => str_replace('.', '', $desaId1),
            'page[size]' => 10,
            'page[number]' => 1,
        ]));

        $response->assertStatus(200);
        $data = $response->json();

        // Verifikasi data yang dikembalikan minimal ada 3 record
        $this->assertGreaterThanOrEqual(3, $data['recordsTotal']);
    }

    public function test_filter_ditolak_mengembalikan_data_sesuai_desa_yang_dipilih()
    {
        // Buat data desa spesifik
        $desaId1 = '3201.01.2019';
        $desaId2 = '3201.01.2020';

        DataDesa::factory()->create(['desa_id' => $desaId1, 'nama' => 'Desa Ditolak 1']);
        DataDesa::factory()->create(['desa_id' => $desaId2, 'nama' => 'Desa Ditolak 2']);

        // Buat surat ditolak untuk desa 1 dan desa 2
        Surat::factory()->count(2)->create([
            'desa_id' => $desaId1,
            'status' => StatusSurat::Ditolak,
            'log_verifikasi' => LogVerifikasiSurat::Ditolak,
        ]);

        Surat::factory()->create([
            'desa_id' => $desaId2,
            'status' => StatusSurat::Ditolak,
            'log_verifikasi' => LogVerifikasiSurat::Ditolak,
        ]);

        // Filter untuk desa 1 saja
        $response = $this->getJson(route('surat.permohonan.getdataditolak', [
            'kode_desa' => str_replace('.', '', $desaId1),
            'page[size]' => 10,
            'page[number]' => 1,
        ]));

        $response->assertStatus(200);
        $data = $response->json();

        // Verifikasi data yang dikembalikan minimal ada 2 record
        $this->assertGreaterThanOrEqual(2, $data['recordsTotal']);
    }

    public function test_halaman_permohonan_dapat_diakses_dan_memiliki_filter_desa()
    {
        $response = $this->get(route('surat.permohonan'));

        $response->assertStatus(200)
            ->assertViewIs('surat.permohonan.index')
            ->assertSee('list_desa'); // Memastikan ada elemen filter desa
    }

    public function test_halaman_permohonan_ditolak_dapat_diakses_dan_memiliki_filter_desa()
    {
        $response = $this->get(route('surat.permohonan.ditolak'));

        $response->assertStatus(200)
            ->assertViewIs('surat.permohonan.ditolak')
            ->assertSee('list_desa'); // Memastikan ada elemen filter desa
    }

    public function test_halaman_arsip_dapat_diakses_dan_memiliki_filter_desa()
    {
        $response = $this->get(route('surat.arsip'));

        $response->assertStatus(200)
            ->assertViewIs('surat.arsip')
            ->assertSee('list_desa'); // Memastikan ada elemen filter desa
    }
}
