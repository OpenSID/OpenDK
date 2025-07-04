<?php

namespace Tests\Feature;

use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CrudTestCase;


class AplikasiControllerTest extends CrudTestCase
{
    use DatabaseTransactions;    

    public function test_index_displays_settings()
    {                
        $response = $this->get(route('setting.aplikasi.index'));

        $response->assertStatus(200);
        $response->assertViewIs('setting.aplikasi.index');        
        $response->assertViewHas('page_title', 'Pengaturan Aplikasi');
    }

    public function test_edit_displays_edit_form()
    {
        
        $setting = SettingAplikasi::factory()->create();

        $response = $this->get(route('setting.aplikasi.edit', $setting->id));

        $response->assertStatus(200);
        $response->assertViewIs('setting.aplikasi.edit');
        $response->assertViewHas('aplikasi', $setting);
    }

    public function test_update_success()
    {
        
        $setting = SettingAplikasi::factory()->create(['value' => 'lama']);

        $response = $this->put(route('setting.aplikasi.update', $setting->id), [
            'value' => 'baru',
        ]);

        $response->assertRedirect(route('setting.aplikasi.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas($setting->getTable(), [
            'id' => $setting->id,
            'value' => 'baru',
        ]);
    }

    public function test_update_validation_error()
    {
        
        $setting = SettingAplikasi::factory()->create();

        $response = $this->from(route('setting.aplikasi.edit', $setting->id))
            ->put(route('setting.aplikasi.update', $setting->id), [
                'value' => '',
            ]);

        $response->assertRedirect(route('setting.aplikasi.edit', $setting->id));
        $response->assertSessionHasErrors('value');
    }
}