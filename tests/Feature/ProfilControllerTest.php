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

use App\Models\Profil;
use App\Models\DataUmum;
use App\Models\DataDesa;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfilControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test data
        $this->createTestProfil();
    }

    /**
     * Test getting all profiles.
     *
     * @return void
     */
    public function test_can_get_all_profiles()
    {
        $response = $this->getJson('/api/frontend/v1/profil');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'provinsi_id',
                            'nama_provinsi',
                            'kabupaten_id',
                            'nama_kabupaten',
                            'kecamatan_id',
                            'nama_kecamatan',
                            'alamat',
                            'kode_pos',
                            'telepon',
                            'email',
                            'tahun_pembentukan',
                            'dasar_pembentukan',
                            'file_struktur_organisasi',
                            'file_logo',
                            'sambutan',
                            'visi',
                            'misi',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ],
                'meta' => [
                    'pagination' => [
                        'total',
                        'count',
                        'per_page',
                        'current_page',
                        'total_pages'
                    ]
                ],
                'links'
            ]);
    }

    /**
     * Test getting profiles with pagination.
     *
     * @return void
     */
    public function test_can_get_profiles_with_pagination()
    {
        $response = $this->getJson('/api/frontend/v1/profil?page[number]=1&page[size]=5');

        $response->assertStatus(200)
            ->assertJsonPath('meta.pagination.per_page', 5)
            ->assertJsonPath('meta.pagination.current_page', 1);
    }

    /**
     * Test getting profiles with sorting.
     *
     * @return void
     */
    public function test_can_get_profiles_with_sorting()
    {
        $response = $this->getJson('/api/frontend/v1/profil?sort=nama_kecamatan');

        $response->assertStatus(200);
    }

    /**
     * Test getting profiles with filtering by kecamatan name.
     *
     * @return void
     */
    public function test_can_get_profiles_with_filter_by_kecamatan_name()
    {
        $response = $this->getJson('/api/frontend/v1/profil?filter[nama_kecamatan]=Test');

        $response->assertStatus(200);
    }

    /**
     * Test getting profiles with filtering by kabupaten name.
     *
     * @return void
     */
    public function test_can_get_profiles_with_filter_by_kabupaten_name()
    {
        $response = $this->getJson('/api/frontend/v1/profil?filter[nama_kabupaten]=Test');

        $response->assertStatus(200);
    }

    /**
     * Test getting profiles with filtering by provinsi name.
     *
     * @return void
     */
    public function test_can_get_profiles_with_filter_by_provinsi_name()
    {
        $response = $this->getJson('/api/frontend/v1/profil?filter[nama_provinsi]=Test');

        $response->assertStatus(200);
    }

    /**
     * Test getting profiles with filtering by kecamatan ID.
     *
     * @return void
     */
    public function test_can_get_profiles_with_filter_by_kecamatan_id()
    {
        $response = $this->getJson('/api/frontend/v1/profil?filter[kecamatan_id]=317101');

        $response->assertStatus(200);
    }

    /**
     * Test getting profiles with search.
     *
     * @return void
     */
    public function test_can_get_profiles_with_search()
    {
        $response = $this->getJson('/api/frontend/v1/profil?search=test');

        $response->assertStatus(200);
    }

    /**
     * Test getting profiles with include dataUmum.
     *
     * @return void
     */
    public function test_can_get_profiles_with_include_data_umum()
    {
        $response = $this->getJson('/api/frontend/v1/profil?include=dataUmum');

        $response->assertStatus(200);
    }

    /**
     * Test getting profiles with include dataDesa.
     *
     * @return void
     */
    public function test_can_get_profiles_with_include_data_desa()
    {
        $response = $this->getJson('/api/frontend/v1/profil?include=dataDesa');

        $response->assertStatus(200);
    }

    /**
     * Test getting profiles with field selection.
     *
     * @return void
     */
    public function test_can_get_profiles_with_fields()
    {
        $response = $this->getJson('/api/frontend/v1/profil?fields=id,nama_kecamatan,nama_kabupaten');

        $response->assertStatus(200);
    }

    /**
     * Test getting single profile by ID.
     *
     * @return void
     */
    public function test_can_get_single_profile_by_id()
    {
        $profil = Profil::first();

        $response = $this->getJson("/api/frontend/v1/profil?filter[id]={$profil->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'provinsi_id',
                            'nama_provinsi',
                            'kabupaten_id',
                            'nama_kabupaten',
                            'kecamatan_id',
                            'nama_kecamatan',
                            'alamat',
                            'kode_pos',
                            'telepon',
                            'email',
                            'tahun_pembentukan',
                            'dasar_pembentukan',
                            'file_struktur_organisasi',
                            'file_logo',
                            'sambutan',
                            'visi',
                            'misi',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Test getting single profile by ID with includes.
     *
     * @return void
     */
    public function test_can_get_single_profile_by_id_with_includes()
    {
        $profil = Profil::first();

        $response = $this->getJson("/api/frontend/v1/profil?filter[id]={$profil->id}?include=dataUmum,dataDesa");

        $response->assertStatus(200);
    }

    /**
     * Test getting profile by non-existent ID returns 404.
     *
     * @return void
     */
    public function test_get_profile_by_non_existent_id_returns_404()
    {
        $response = $this->getJson('/api/frontend/v1/profil/999999');

        $response->assertStatus(404);
    }


    /**
     * Test validation error for invalid sort field.
     *
     * @return void
     */
    public function test_validation_error_for_invalid_sort_field()
    {
        $response = $this->getJson('/api/frontend/v1/profil?sort=invalid_field');

        $response->assertStatus(400);
    }

    /**
     * Test validation error for invalid order.
     *
     * @return void
     */
    public function test_validation_error_for_invalid_order()
    {
        $response = $this->getJson('/api/frontend/v1/profil?sort=invalid_order');

        $response->assertStatus(400);
    }
    
    /**
     * Test rate limiting headers are present.
     *
     * @return void
     */
    public function test_rate_limiting_headers_are_present()
    {
        $response = $this->getJson('/api/frontend/v1/profil');

        $response->assertStatus(200)
            ->assertHeader('X-RateLimit-Limit')
            ->assertHeader('X-RateLimit-Remaining')
            ->assertHeader('X-RateLimit-Reset');
    }

    /**
     * Test rate limiting configuration values.
     *
     * @return void
     */
    public function test_rate_limiting_configuration_values()
    {
        $response = $this->getJson('/api/frontend/v1/profil');

        $response->assertStatus(200);

        // Check that the rate limit headers match configuration
        $rateLimitHeader = $response->headers->get('X-RateLimit-Limit');
        $this->assertNotNull($rateLimitHeader);
        $this->assertIsNumeric($rateLimitHeader);
    }

    /**
     * Create test profil data.
     *
     * @return void
     */
    private function createTestProfil()
    {
        Profil::create([
            'provinsi_id' => 31,
            'nama_provinsi' => 'DKI Jakarta',
            'kabupaten_id' => 3171,
            'nama_kabupaten' => 'Jakarta Pusat',
            'kecamatan_id' => 317101,
            'nama_kecamatan' => 'Menteng',
            'alamat' => 'Jl. Test No. 123',
            'kode_pos' => '12345',
            'telepon' => '021-1234567',
            'email' => 'test@example.com',
            'tahun_pembentukan' => '1978',
            'dasar_pembentukan' => 'Peraturan Pemerintah No. 25 Tahun 1978',
            'file_struktur_organisasi' => '/storage/test/struktur.pdf',
            'file_logo' => '/storage/test/logo.png',
            'sambutan' => 'Test sambutan',
            'visi' => 'Test visi',
            'misi' => 'Test misi',
        ]);

        // Create related data
        $profil = Profil::first();

        DataUmum::create([
            'profil_id' => $profil->id,
            'tipologi' => 'Pedesaan',
            'sejarah' => 'Test sejarah',
            'ketinggian' => 25,
            'sumber_luas_wilayah' => 1,
            'luas_wilayah' => 1250.5,
            'bts_wil_utara' => 'Test Utara',
            'bts_wil_timur' => 'Test Timur',
            'bts_wil_selatan' => 'Test Selatan',
            'bts_wil_barat' => 'Test Barat',
            'jml_puskesmas' => 2,
            'jml_puskesmas_pembantu' => 3,
            'jml_posyandu' => 15,
            'jml_pondok_bersalin' => 1,
            'jml_paud' => 5,
            'jml_sd' => 8,
            'jml_smp' => 4,
            'jml_sma' => 2,
            'jml_masjid_besar' => 3,
            'jml_mushola' => 15,
            'jml_gereja' => 2,
            'jml_pasar' => 2,
            'jml_balai_pertemuan' => 5,
            'embed_peta' => '<iframe>test</iframe>',
            'path' => '[[123, 456], [789, 012]]',
            'lat' => -6.2088,
            'lng' => 106.8456,
        ]);

        DataDesa::create([
            'profil_id' => $profil->id,
            'desa_id' => 3171011001,
            'nama' => 'Test Desa',
            'sebutan_desa' => 'Kelurahan',
            'website' => 'https://test.desa.id',
            'luas_wilayah' => 125.5,
            'path' => '[[123, 456], [789, 012]]',
        ]);
    }
}
