<?php

namespace Tests\Feature\Http\Middleware;

use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MaintenanceModeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_aborts_with_503_when_maintenance_mode_is_enabled()
    {
        SettingAplikasi::updateOrCreate([
            'key' => 'mode_maintenance',            
        ], [
            'value' => 1,
            'type' => 'boolean',
            'description' => 'Mode maintenance.',
            'kategori' => 'web',
            'option' => '{}',
        ]);

        $response = $this->get('/');

        $response->assertStatus(503);
    }

    /** @test */
    public function it_allows_request_when_maintenance_mode_is_disabled()
    {
        SettingAplikasi::updateOrCreate([
            'key' => 'mode_maintenance',            
        ], [
            'value' => 0,
            'type' => 'boolean',
            'description' => 'Mode maintenance.',
            'kategori' => 'web',
            'option' => '{}',
        ]);


        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
