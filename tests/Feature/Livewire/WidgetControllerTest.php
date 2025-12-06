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

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Widget\WidgetController;
use App\Models\Widget;
use Livewire\Livewire;
use Tests\TestCase;

class WidgetControllerTest extends TestCase
{
    /** @test */
    public function test_component_can_render()
    {
        $component = Livewire::test(WidgetController::class);

        $component->assertStatus(200);
    }

    /** Menguji properti `search` dan `page` bisa menerima data */
    public function test_widget_page_contains_livewire_component()
    {
        Livewire::withQueryParams(['search' => 'test', 'page' => 2])
            ->test(WidgetController::class)
            ->assertSet('search', 'test')
            ->assertSet('page', 2);
    }

    /** Menguji apakah data yang di cari ada */
    public function test_widget_search_shows_correct_results()
    {
        $widget = Widget::inRandomOrder()->first();

        if (!$widget) {
            $this->markTestSkipped('Tidak ada data di database untuk diuji.');
        }

        Livewire::test(WidgetController::class)
            ->set('search', $widget->judul)
            ->call('render')
            ->assertSee($widget->judul);
    }

    /** create data baru */
    public function test_can_create_a_widget()
    {
        $this->markTestSkipped('Test requires tidy PHP extension which is not installed.');
    }
}
