<?php

namespace Tests\Traits;

use Illuminate\Foundation\Testing\DatabaseTransactions;

trait WithDatabaseSetup
{
    use DatabaseTransactions;

    /**
     * Set up database for testing.
     */
    protected function setUpDatabase(): void
    {
        // Add any additional database setup logic here if needed
    }
}
