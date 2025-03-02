<?php

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

        Livewire::test(WidgetController::class)
            ->set('search', $widget->judul)
            ->call('render')
            ->assertSee($widget->judul);
    }

    /** create data baru */
    public function test_can_create_a_widget()
    {
        Livewire::test(WidgetController::class)
            ->set('widget.judul', 'Widget Baru')
            ->set('widget.jenis_widget', 3)
            ->set('widget.isi', 'Isi Widget Baru')
            ->call('store');

        $this->assertDatabaseHas('widgets', ['judul' => 'Widget Baru']);

        // Ambil ID widget yang baru dibuat
        $widget = Widget::where('judul', 'Widget Baru')->first();

        // Pastikan widget ditemukan sebelum dihapus
        $this->assertNotNull($widget);

        // Hapus widget
        Livewire::test(WidgetController::class)
            ->call('destroy', $widget->id);

        // Pastikan widget sudah dihapus dari database
        $this->assertDatabaseMissing('widgets', ['judul' => 'Widget Baru']);
    }

}
