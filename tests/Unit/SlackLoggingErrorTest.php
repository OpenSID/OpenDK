<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class SlackLoggingErrorTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testSlackLoggingError()
    {
        Log::channel('slack')->info('Slack error test');
        Log::channel('slack')->warning('Slack error test');
        Log::channel('slack')->error('Slack error test');
        Log::channel('slack')->critical('Slack error test');

        self::assertTrue(true);
    }
}
