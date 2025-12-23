<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 */

use App\Http\Livewire\Widget\WidgetController;
use App\Models\Widget;
use Livewire\Livewire;

test('component can render', function () {
    $component = Livewire::test(WidgetController::class);
    $component->assertStatus(200);
});

test('widget page contains livewire component with search param', function () {
    // Test that search query param is properly set
    Livewire::withQueryParams(['search' => 'test'])
        ->test(WidgetController::class)
        ->assertSet('search', 'test');
});

test('widget search shows correct results', function () {
    $widget = Widget::inRandomOrder()->first();

    if (!$widget) {
        $this->markTestSkipped('Tidak ada data di database untuk diuji.');
    }

    // In Livewire 3, we don't call render() directly - just set the property and check the view
    Livewire::test(WidgetController::class)
        ->set('search', $widget->judul)
        ->assertSee($widget->judul);
});

test('can create a widget', function () {
    Livewire::test(WidgetController::class)
        ->set('widget.judul', 'Widget Baru')
        ->set('widget.jenis_widget', 3)
        ->set('widget.isi', 'Isi Widget Baru')
        ->call('store');

    $this->assertDatabaseHas('widgets', ['judul' => 'Widget Baru']);

    $widget = Widget::where('judul', 'Widget Baru')->first();
    expect($widget)->not->toBeNull();

    Livewire::test(WidgetController::class)->call('destroy', $widget->id);

    $this->assertDatabaseMissing('widgets', ['judul' => 'Widget Baru']);
});
