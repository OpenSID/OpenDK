<?php

namespace Tests\Feature\Http\Controllers\Setting;

use App\Models\NavMenu;
use App\Models\Artikel;
use App\Models\Kategori;
use App\Models\JenisDokumen;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CrudTestCase;

class NavMenuControllerTest extends CrudTestCase
{
    use DatabaseTransactions;   

    public function test_index_displays_nav_menu_page()
    {
               // Seed dummy data
        NavMenu::factory()->create(['name' => 'Menu Utama']);
        Artikel::factory()->create(['judul' => 'Judul Artikel']);
        Kategori::factory()->create(['nama' => 'Kategori']);
        JenisDokumen::factory()->create(['nama' => 'Dokumen']);

        $response = $this->get(route('setting.navmenu.index'));
        $response->assertStatus(200);
        $response->assertViewIs('setting.nav_menu.index');
        $response->assertSee('Pengaturan Menu');
    }

    public function test_store_saves_nav_menu()
    {
        $jsonMenu = [
            [
                'text' => 'Menu 1',
                'href' => '/menu-1',
                'target' => '_self',
                'type' => 'link',
                'is_show' => 1,
                'children' => [
                    [
                        'text' => 'Submenu 1',
                        'href' => '/menu-1/sub-1',
                        'target' => '_self',
                        'type' => 'link',
                        'is_show' => 1,
                    ]
                ]
            ]
        ];

        $response = $this->post(route('setting.navmenu.store'), [
            'json_menu' => json_encode($jsonMenu)
        ]);

        $response->assertRedirect(route('setting.navmenu.index'));
        $this->assertDatabaseHas('nav_menus', [
            'name' => 'Menu 1',
            'url' => '/menu-1',
        ]);
        $this->assertDatabaseHas('nav_menus', [
            'name' => 'Submenu 1',
            'url' => '/menu-1/sub-1',
        ]);
    }
}