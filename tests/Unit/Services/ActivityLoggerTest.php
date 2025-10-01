<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class ActivityLoggerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['cache.default' => 'array']);
        Cache::flush();

        $this->prepareDatabase();
    }

    public function test_log_records_activity_with_enriched_metadata(): void
    {
        Http::fake([
            'https://ipwhois.app/*' => Http::response([
                'success' => true,
                'country' => 'Indonesia',
                'country_code' => 'ID',
                'region' => 'Jawa Timur',
                'city' => 'Surabaya',
                'isp' => 'Example ISP',
            ], 200),
        ]);

        $this->fakeRequest([
            'REMOTE_ADDR' => '1.2.3.4',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36',
            'HTTP_REFERER' => 'https://opendk.test/dashboard',
        ]);

        $causer = User::factory()->create();
        $subject = User::factory()->create();

        $activity = ActivityLogger::log(
            category: 'pengguna',
            event: 'created',
            message: 'Menambahkan pengguna',
            subject: $subject,
            causer: $causer,
            additionalProperties: ['custom' => 'value']
        );

        $this->assertDatabaseHas('activity_log', [
            'id' => $activity->id,
            'log_name' => 'pengguna',
            'event' => 'created',
            'description' => 'Menambahkan pengguna',
            'subject_id' => $subject->getKey(),
            'subject_type' => $subject::class,
            'causer_id' => $causer->getKey(),
            'causer_type' => $causer::class,
        ]);

        $properties = $activity->properties->toArray();

        $this->assertSame('1.2.3.4', $properties['ip_address']);
        $this->assertSame('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', $properties['user_agent']);
        $this->assertSame('https://opendk.test/admin/users', $properties['url']);
        $this->assertSame('admin/users', $properties['slug']);
        $this->assertSame('POST', $properties['method']);
        $this->assertSame('https://opendk.test/dashboard', $properties['referer']);
        $this->assertSame('Chrome', $properties['browser']);
        $this->assertSame('OS X', $properties['platform']);
        $this->assertSame('Macintosh', $properties['device']);

        $this->assertTrue($properties['ip_location_available']);
        $this->assertSame('Indonesia', $properties['ip_country']);
        $this->assertSame('ID', $properties['ip_country_code']);
        $this->assertSame('Jawa Timur', $properties['ip_region']);
        $this->assertSame('Surabaya', $properties['ip_city']);
        $this->assertSame('Example ISP', $properties['ip_isp']);
        $this->assertSame('value', $properties['custom']);
    }

    public function test_log_uses_authenticated_user_and_private_ip_message(): void
    {
        Http::fake();

        $this->fakeRequest([
            'REMOTE_ADDR' => '127.0.0.1',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36',
        ]);

        $causer = User::factory()->create();
        $subject = User::factory()->create();

        $this->actingAs($causer);

        $activity = ActivityLogger::log(
            category: 'pengguna',
            event: 'updated',
            message: 'Memperbarui pengguna',
            subject: $subject
        );

        $this->assertSame($causer->getKey(), $activity->causer_id);

        $properties = $activity->properties->toArray();

        $this->assertSame('127.0.0.1', $properties['ip_address']);
        $this->assertFalse($properties['ip_location_available']);
        $this->assertSame('IP lokal/internal', $properties['ip_location_message']);
    }

    public function test_append_properties_merges_values(): void
    {
        Http::fake();

        $this->fakeRequest([
            'REMOTE_ADDR' => '127.0.0.1',
        ]);

        $user = User::factory()->create();

        $activity = ActivityLogger::log(
            category: 'pengguna',
            event: 'updated',
            message: 'Memperbarui pengguna',
            subject: $user,
            causer: $user
        );

        ActivityLogger::appendProperties($activity, ['additional' => 'data']);

        $properties = $activity->fresh()->properties->toArray();

        $this->assertSame('data', $properties['additional']);
        $this->assertSame('127.0.0.1', $properties['ip_address']);
    }

    private function fakeRequest(array $server): void
    {
        $defaults = [
            'HTTP_HOST' => 'opendk.test',
            'HTTPS' => 'on',
        ];

        $request = Request::create(
            uri: 'https://opendk.test/admin/users',
            method: 'POST',
            server: array_merge($defaults, $server)
        );

        app()->instance('request', $request);
    }

    private function prepareDatabase(): void
    {
        Schema::dropIfExists('activity_log');
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('activity_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('log_name')->nullable();
            $table->text('description')->nullable();
            $table->nullableMorphs('subject');
            $table->nullableMorphs('causer');
            $table->json('properties')->nullable();
            $table->string('event')->nullable();
            $table->uuid('batch_uuid')->nullable();
            $table->timestamps();
        });
    }
}
