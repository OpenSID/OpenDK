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

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class WebsiteFooterTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Disable the TrackVisitors middleware to prevent duplicate visitor entries
        $this->withoutMiddleware(\App\Http\Middleware\TrackVisitors::class);
    }

    /** @test */
    public function homepage_footer_contains_sebutan_desa_config()
    {
        // Set config untuk testing
        config(['setting.sebutan_desa' => 'Desa']);

        $response = $this->get('/');

        $response->assertStatus(200);

        // Pastikan footer mengandung sebutan desa
        $response->assertSee('Desa', false); // false = case insensitive

        // Atau bisa lebih spesifik dengan selector CSS
        $response->assertSeeInOrder(['footer', 'Desa'], false);
    }

    /** @test */
    public function berita_desa_page_footer_contains_sebutan_desa_config()
    {
        config(['setting.sebutan_desa' => 'Kelurahan']);

        $response = $this->get('/berita-desa');

        $response->assertStatus(200);
        $response->assertSee('Kelurahan', false);
    }

    /** @test */
    public function profil_page_footer_contains_sebutan_desa_config()
    {
        config(['setting.sebutan_desa' => 'Kampung']);

        $response = $this->get('/profil/visi-dan-misi');

        $response->assertStatus(200);
        $response->assertSee('Kampung', false);
    }

    /** @test */
    public function statistik_page_footer_contains_sebutan_desa_config()
    {
        config(['setting.sebutan_desa' => 'Nagari']);

        $response = $this->get('/statistik/kependudukan');

        $response->assertStatus(200);
        $response->assertSee('Nagari', false);
    }

    /** @test */
    public function faq_page_footer_contains_sebutan_desa_config()
    {
        config(['setting.sebutan_desa' => 'Gampong']);

        $response = $this->get('/faq');

        $response->assertStatus(200);
        $response->assertSee('Gampong', false);
    }

    /** @test */
    public function footer_contains_sebutan_desa_with_different_values()
    {
        $sebutanDesaValues = [
            'Desa',
            'Kelurahan',
            'Kampung',
            'Nagari',
            'Gampong',
            'Pekon'
        ];

        foreach ($sebutanDesaValues as $sebutan) {
            config(['setting.sebutan_desa' => $sebutan]);

            $response = $this->get('/');

            $response->assertStatus(200);
            $response->assertSee($sebutan, false);
        }
    }
}
