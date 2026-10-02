<?php

namespace Tests\Traits;

use App\Models\SettingAplikasi;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tymon\JWTAuth\JWT;

trait WithApiKeyTesting
{
    protected User $testUser;
    protected string $jwtToken;

    protected function setUpApiKeyTesting(): void
    {
        $this->createApiKeyTestSchema();

        DB::beginTransaction();

        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $this->testUser = $user;

        config(['jwt.secret' => 'test_secret_key_for_testing_only_do_not_use_in_production']);
        $this->jwtToken = app(JWT::class)->fromUser($user);

        SettingAplikasi::updateOrCreate(
            ['key' => 'api_key_opendk'],
            ['value' => $this->jwtToken]
        );

        $this->setDefaultApplicationConfig();
    }

    protected function tearDownApiKeyTesting(): void
    {
        DB::rollBack();
    }

    private function createApiKeyTestSchema(): void
    {
        Schema::dropIfExists('api_key_audit_logs');
        Schema::dropIfExists('api_keys');
        Schema::dropIfExists('password_histories');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('das_setting');
        Schema::dropIfExists('users');

        Schema::create('users', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('status')->default(1);
            $table->boolean('otp_enabled')->default(false);
            $table->string('otp_channel')->nullable();
            $table->string('otp_identifier')->nullable();
            $table->string('telegram_chat_id')->nullable();
            $table->boolean('two_fa_enabled')->default(false);
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('password_histories', function ($table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('password');
            $table->timestamps();
        });

        Schema::create('personal_access_tokens', function ($table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('das_setting', function ($table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('kategori')->nullable();
            $table->string('type')->nullable();
            $table->text('description')->nullable();
            $table->text('option')->nullable();
        });

        Schema::create('password_resets', function ($table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('api_keys', function ($table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('key', 64)->unique();
            $table->string('key_prefix', 12);
            $table->json('scopes')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->string('status', 20)->default('active');
            $table->timestamps();
        });

        Schema::create('api_key_audit_logs', function ($table) {
            $table->id();
            $table->foreignId('api_key_id')->constrained('api_keys')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 50);
            $table->json('payload')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->boolean('success')->default(true);
            $table->timestamps();
        });
    }
}
