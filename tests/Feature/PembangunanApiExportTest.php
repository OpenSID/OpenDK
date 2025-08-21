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

use App\Models\DataDesa;
use App\Models\Pembangunan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PembangunanApiExportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test API export pembangunan endpoint can be accessed
     */
    public function test_api_export_pembangunan_endpoint_accessible()
    {
        // Create test data
        $desa = DataDesa::factory()->create();
        
        Pembangunan::factory()->create([
            'desa_id' => $desa->desa_id,
        ]);

        // Make request to API endpoint
        $response = $this->postJson('/api/v1/pembangunan/download');

        // Assert successful response
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        // Check filename pattern
        $contentDisposition = $response->headers->get('content-disposition');
        $this->assertStringContainsString('data_pembangunan_', $contentDisposition);
        $this->assertStringContainsString('.xlsx', $contentDisposition);
    }

    /**
     * Test API export pembangunan with desa filter
     */
    public function test_api_export_pembangunan_with_desa_filter()
    {
        // Create test data
        $desa1 = DataDesa::factory()->create();
        $desa2 = DataDesa::factory()->create();
        
        Pembangunan::factory()->create([
            'desa_id' => $desa1->desa_id,
        ]);
        
        Pembangunan::factory()->create([
            'desa_id' => $desa2->desa_id,
        ]);

        // Make request with desa filter
        $response = $this->postJson('/api/v1/pembangunan/download', [
            'desa' => $desa1->desa_id
        ]);

        // Assert successful response
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    /**
     * Test API export pembangunan with no data
     */
    public function test_api_export_pembangunan_with_no_data()
    {
        // Make request without any data
        $response = $this->postJson('/api/v1/pembangunan/download');

        // Assert successful response even with no data
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}
