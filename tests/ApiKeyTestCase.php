<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Traits\WithApiKeyTesting;
use Tests\Traits\WithSettingAplikasi;

abstract class ApiKeyTestCase extends BaseTestCase
{
    use CreatesApplication, WithApiKeyTesting, WithSettingAplikasi;

    public static function setUpBeforeClass(): void
    {
        // Force SQLite in-memory BEFORE the app boots.
        // This overrides .env.testing's DB_CONNECTION=mysql.
        $_ENV['DB_CONNECTION'] = 'sqlite';
        $_ENV['DB_DATABASE'] = ':memory:';
        $_SERVER['DB_CONNECTION'] = 'sqlite';
        $_SERVER['DB_DATABASE'] = ':memory:';
        putenv('DB_CONNECTION=sqlite');
        putenv('DB_DATABASE=:memory:');

        parent::setUpBeforeClass();
    }

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);

        $this->setUpApiKeyTesting();
    }

    protected function tearDown(): void
    {
        $this->tearDownApiKeyTesting();
        parent::tearDown();
    }
}
