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

test('widget page contains livewire component', function () {
    Livewire::withQueryParams(['search' => 'test', 'page' => 2])
        ->test(WidgetController::class)
        ->assertSet('search', 'test')
        ->assertSet('page', 2);
});

test('widget search shows correct results', function () {
    $widget = Widget::inRandomOrder()->first();

    if (!$widget) {
        $this->markTestSkipped('Tidak ada data di database untuk diuji.');
    }

    Livewire::test(WidgetController::class)
        ->set('search', $widget->judul)
        ->call('render')
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
