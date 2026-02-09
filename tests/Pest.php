<?php

use Illuminate\Support\Facades\DB;
use function Pest\Laravel\{get, post, put, delete};

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

// Configure test groups for Feature tests
pest()->group('feature')
    ->extend(Tests\TestCase::class)
    ->in('Feature');

// Configure test groups for Unit tests
pest()->group('unit')
    ->extend(Tests\TestCase::class)
    ->in('Unit');

// Configure test groups for Browser tests
pest()->group('browser')
    ->extend(Tests\TestCase::class)
    ->in('Browser')
    ->beforeEach(function () {
        // Set headless mode for faster execution
        config(['app.url' => env('APP_URL', 'http://opendk.test')]);
    });

/*
|--------------------------------------------------------------------------
| Architecture Testing
|--------------------------------------------------------------------------
|
| Define architectural constraints and rules to maintain clean code
| structure and prevent architectural violations.
|
*/

uses()
    ->group('arch')
    ->in('Arch');

// Example architectural tests - these would be defined in tests/Arch/ directory
// For example: ensure models extend Eloquent Model, controllers extend BaseController, etc.
// pest()->group('arch')->in('Arch');

/*
|--------------------------------------------------------------------------
| Hooks
|--------------------------------------------------------------------------
|
| Hooks allow you to tap into the lifecycle of tests to prepare and clean up
| resources. You can define hooks for before each test, after each test,
| before all tests in a file, and after all tests in a file.
|
*/

// Global beforeAll hook for all test files
beforeAll(function () {
    // Initialize shared resources
    echo "Starting test suite...\n";
});

// Global afterAll hook for all test files
afterAll(function () {
    // Clean up shared resources
    echo "Finishing test suite...\n";
});

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

// Custom expectation to check if a value is one
expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

// Additional custom expectations for the OpenDK project
expect()->extend('toBeValidUuid', function () {
    return $this->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i');
});

expect()->extend('toBeSuccessfulResponse', function () {
    return $this->toHaveKey('status', 'success')
        ->and($this)->toHaveKey('data');
});

/*
|--------------------------------------------------------------------------
| Datasets
|--------------------------------------------------------------------------
|
| Datasets allow you to define a collection of data that can be used across
| multiple tests. This helps in creating data-driven tests with different
| inputs while reusing the same test logic.
|
*/

dataset('http_methods', ['GET', 'POST', 'PUT', 'PATCH', 'DELETE']);
dataset('user_roles', ['admin', 'operator', 'member']);
dataset('status_codes', [200, 201, 400, 401, 403, 404, 500]);

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * Helper function to create an authenticated user for testing
 */
function createAuthenticatedUser($role = 'admin')
{
    $user = \App\Models\User::factory()->create();
    
    if ($role === 'admin') {
        // Assign admin role if needed
        $user->assignRole('admin');
    }
    
    return $user;
}

/**
 * Helper function to get test database connection
 */
function getTestConnection()
{
    return DB::connection(config('database.testing.default', 'sqlite'));
}
