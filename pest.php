<?php

/*
|--------------------------------------------------------------------------
| Pest Configuration File
|--------------------------------------------------------------------------
|
| This file contains the configuration settings for Pest PHP testing framework.
| You can customize various aspects of how tests behave and are reported.
|
*/

// Use the Laravel plugin for Laravel-specific testing features
uses(Tests\TestCase::class)->in('Feature', 'Unit', 'Arch');

// Set up global before/after hooks if needed
beforeAll(function () {
    // Runs before all tests
    fwrite(STDERR, "Starting test suite...\n");
});

afterAll(function () {
    // Runs after all tests
    fwrite(STDERR, "Finished test suite.\n");
});

// Configure verbosity for more informative output
$conf = $GLOBALS['argv'] ?? [];
if (in_array('--debug', $conf) || in_array('-v', $conf) || in_array('-vv', $conf) || in_array('-vvv', $conf)) {
    // More verbose output when debug flags are used
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

// Custom formatter for test output
// Note: Pest uses PHPUnit's underlying infrastructure for reporting
// Advanced customization would require a custom ResultPrinter class