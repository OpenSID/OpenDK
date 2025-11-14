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

namespace Tests\Feature\Api\Frontend;

use App\Repositories\StatistikPendudukApiRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CrudTestCase;

class StatistikPendudukControllerTest extends CrudTestCase
{
    use DatabaseTransactions;
    use WithFaker;

    protected $repository;

    public function setUp(): void
    {
        parent::setUp();

        // Mock repository
        $this->repository = $this->mock(StatistikPendudukApiRepository::class);
        $this->app->instance(StatistikPendudukApiRepository::class, $this->repository);

        Cache::flush();
    }

    /**
     * Test index method returns statistik penduduk data
     */
    public function test_index_returns_statistik_penduduk_data()
    {
        // Mock repository data method
        $expectedData = [
            [
                'id' => 1,
                'yearList' => [2020, 2021, 2022, 2023, 2024],
                'dashboard' => [
                    'total_penduduk' => 1000,
                    'penduduk_laki' => 500,
                    'penduduk_perempuan' => 500,
                ],
                'chart' => [
                    'penduduk' => [
                        'labels' => ['2020', '2021', '2022', '2023', '2024'],
                        'data' => [800, 850, 900, 950, 1000],
                    ],
                    'penduduk-usia' => [
                        'labels' => ['0-5', '6-12', '13-18', '19-40', '41-60', '60+'],
                        'data' => [100, 150, 120, 300, 200, 130],
                    ],
                    'penduduk-pendidikan' => [
                        'labels' => ['Tidak Sekolah', 'SD', 'SMP', 'SMA', 'D1/D2/D3', 'S1', 'S2/S3'],
                        'data' => [50, 200, 250, 300, 100, 80, 20],
                    ],
                    'penduduk-golongan-darah' => [
                        'labels' => ['A', 'B', 'AB', 'O'],
                        'data' => [250, 250, 200, 300],
                    ],
                    'penduduk-kawin' => [
                        'labels' => ['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'],
                        'data' => [300, 600, 50, 50],
                    ],
                    'penduduk-agama' => [
                        'labels' => ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu'],
                        'data' => [800, 50, 30, 50, 50, 20],
                    ],
                ]
            ]
        ];

        $this->repository
            ->shouldReceive('data')
            ->once()
            ->with('Semua', date('Y'))
            ->andReturn($expectedData);

        // Make request to API
        $response = $this->getJson('/api/frontend/v1/statistik-penduduk');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'type',
                    'id',
                    'attributes' => [
                        'yearList',
                        'dashboard',
                        'chart' => [
                            'penduduk',
                            'penduduk-usia',
                            'penduduk-pendidikan',
                            'penduduk-golongan-darah',
                            'penduduk-kawin',
                            'penduduk-agama',
                        ]
                    ]
                ]
            ]
        ]);
    }

    /**
     * Test index method with kategori parameter
     */
    public function test_index_with_kategori_parameter()
    {
        // Mock repository data method
        $this->repository
            ->shouldReceive('data')
            ->once()
            ->with('Pendidikan', date('Y'))
            ->andReturn([]);

        // Make request with kategori parameter
        $response = $this->getJson('/api/frontend/v1/statistik-penduduk?kategori=Pendidikan');

        $response->assertStatus(200);
    }

    /**
     * Test index method with tahun parameter
     */
    public function test_index_with_tahun_parameter()
    {
        // Mock repository data method
        $this->repository
            ->shouldReceive('data')
            ->once()
            ->with('Semua', '2023')
            ->andReturn([]);

        // Make request with tahun parameter
        $response = $this->getJson('/api/frontend/v1/statistik-penduduk?tahun=2023');

        $response->assertStatus(200);
    }

    /**
     * Test index method with both kategori and tahun parameters
     */
    public function test_index_with_kategori_and_tahun_parameters()
    {
        // Mock repository data method
        $this->repository
            ->shouldReceive('data')
            ->once()
            ->with('Kesehatan', '2022')
            ->andReturn([]);

        // Make request with both parameters
        $response = $this->getJson('/api/frontend/v1/statistik-penduduk?kategori=Kesehatan&tahun=2022');

        $response->assertStatus(200);
    }

    /**
     * Test index method with pagination parameters
     */
    public function test_index_with_pagination_parameters()
    {
        // Mock repository data method
        $this->repository
            ->shouldReceive('data')
            ->once()
            ->with('Semua', date('Y'))
            ->andReturn([]);

        // Make request with pagination parameters
        $response = $this->getJson('/api/frontend/v1/statistik-penduduk?page[number]=2&page[size]=10');

        $response->assertStatus(200);
    }

    /**
     * Test index method with search parameter
     */
    public function test_index_with_search_parameter()
    {
        // Mock repository data method
        $this->repository
            ->shouldReceive('data')
            ->once()
            ->with('Semua', date('Y'))
            ->andReturn([]);

        // Make request with search parameter
        $response = $this->getJson('/api/frontend/v1/statistik-penduduk?search=test');

        $response->assertStatus(200);
    }

    /**
     * Test index method with sort and order parameters
     */
    public function test_index_with_sort_and_order_parameters()
    {
        // Mock repository data method
        $this->repository
            ->shouldReceive('data')
            ->once()
            ->with('Semua', date('Y'))
            ->andReturn([]);

        // Make request with sort and order parameters
        $response = $this->getJson('/api/frontend/v1/statistik-penduduk?sort=nama&order=asc');

        $response->assertStatus(200);
    }

    /**
     * Test index method with include parameter
     */
    public function test_index_with_include_parameter()
    {
        // Mock repository data method
        $this->repository
            ->shouldReceive('data')
            ->once()
            ->with('Semua', date('Y'))
            ->andReturn([]);

        // Make request with include parameter
        $response = $this->getJson('/api/frontend/v1/statistik-penduduk?include=penduduk');

        $response->assertStatus(200);
    }

    /**
     * Test index method with all parameters
     */
    public function test_index_with_all_parameters()
    {
        // Mock repository data method
        $this->repository
            ->shouldReceive('data')
            ->once()
            ->with('Ekonomi', '2023')
            ->andReturn([]);

        // Make request with all parameters
        $response = $this->getJson('/api/frontend/v1/statistik-penduduk?kategori=Ekonomi&tahun=2023&page[number]=1&page[size]=15&search=test&sort=nama&order=desc&include=penduduk');

        $response->assertStatus(200);
    }

    /**
     * Test index method with empty data
     */
    public function test_index_with_empty_data()
    {
        // Mock repository data method to return empty array
        $this->repository
            ->shouldReceive('data')
            ->once()
            ->with('Semua', date('Y'))
            ->andReturn([]);

        // Make request to API
        $response = $this->getJson('/api/frontend/v1/statistik-penduduk');

        $response->assertStatus(200);
        $response->assertJson([
            'data' => []
        ]);
    }
}
