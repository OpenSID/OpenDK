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

use App\Models\SettingAplikasi;
use Tests\TestCase;

class TokenRegisteredMiddlewareTest extends TestCase
{
    private $apiUrl = 'api/v1/';
    private $token;
    public function setUp(): void
    {
        parent::setUp();
        $this->token = SettingAplikasi::where(['key' => 'api_key_opendk'])->first()->value ?? '';
    }

    public function test_can_access_api_when_token_registered()
    {
        // Make a GET request to the admin route
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson($this->apiUrl.'test');

        // Assert the response status is 200
        $response->assertStatus(200);
    }

    public function test_can_access_api_when_token_not_registered()
    {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvc2V0dGluZy9hcGxpa2FzaS90b2tlbiIsImlhdCI6MTczNzE1Mzg1NiwiZXhwIjoyMDUyNTEzODU2LCJuYmYiOjE3MzcxNTM4NTYsImp0aSI6IlFxM3ZqTEg1d2llRjJXYWsiLCJzdWIiOiIxIiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.RERx_8rafp73thBqNfktW-OngOUEjFo0Hsc-uUH_1wA';
        // Make a GET request to the admin route
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson($this->apiUrl.'test');

        // Assert the response status is 401
        $response->assertStatus(401);
        $response->assertJson(['error' => 'Token not registered']);
    }
}
