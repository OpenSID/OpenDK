# Testing Conventions for OpenDK

This document outlines the testing conventions and best practices for the OpenDK project.

## Table of Contents
- [Introduction](#introduction)
- [Test Structure](#test-structure)
- [Naming Conventions](#naming-conventions)
- [Test Types](#test-types)
- [Pest PHP Features](#pest-php-features)
- [Architecture Testing](#architecture-testing)
- [Parallel Testing](#parallel-testing)
- [Code Coverage](#code-coverage)
- [Best Practices](#best-practices)

## Introduction

OpenDK uses Pest PHP as its primary testing framework alongside Laravel's testing utilities. This ensures consistent, reliable, and maintainable tests across the project.

## Test Structure

Tests are organized into three main directories:
- `tests/Unit/` - Unit tests for individual classes and functions
- `tests/Feature/` - Feature tests for larger functionality and API endpoints
- `tests/Arch/` - Architecture tests to enforce structural constraints
- `tests/Browser/` - Browser tests for UI interactions

## Naming Conventions

- Test files should be named with the suffix `Test.php`
- Test functions should use descriptive names that indicate what is being tested
- Use `snake_case` for test function names
- Group related tests using Pest's `describe()` function

## Test Types

### Unit Tests
- Test individual classes, methods, or functions in isolation
- Should not touch the database or external services
- Focus on pure business logic

### Feature Tests
- Test integrated functionality across multiple components
- Can interact with the database and external services
- Simulate real user interactions with the application

### Architecture Tests
- Enforce architectural constraints and rules
- Ensure code follows established patterns and dependencies
- Prevent architectural violations

## Pest PHP Features

### Test Groups
```php
test('this is a feature test')
    ->group('feature')
    ->group('api');
```

### Datasets
```php
dataset('http_methods', ['GET', 'POST', 'PUT', 'PATCH', 'DELETE']);

test('supports various HTTP methods', function ($method) {
    route('api.users.index')
        ->{$method.toLowerCase()}()
        ->assertSuccessful();
})->with('http_methods');
```

### Hooks
```php
beforeEach(function () {
    $this->user = User::factory()->create();
});

afterEach(function () {
    // Cleanup after each test
});
```

### Custom Expectations
```php
expect()->extend('toBeValidUuid', function () {
    return $this->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i');
});
```

## Architecture Testing

Architecture tests ensure that our codebase maintains proper structure and dependencies:

```php
// Ensure all models extend Eloquent Model
expect('App\Models\*')
    ->toExtend(Model::class);

// Ensure models don't use facades directly  
expect('App\Models\*')
    ->not->toUse('Illuminate\Support\Facades\*');
```

## Parallel Testing

Tests are configured to run in parallel for improved performance. When writing tests, ensure they:
- Don't rely on shared mutable state
- Use unique resources when possible
- Clean up after themselves

## Code Coverage

Code coverage reports are generated to track testing completeness. Aim for:
- Minimum 80% coverage for new features
- Critical paths should have 90%+ coverage
- Legacy code should gradually increase coverage

## Best Practices

### General
- Write tests that are fast, isolated, and deterministic
- Use descriptive test names that explain the expected behavior
- Follow the AAA pattern: Arrange, Act, Assert
- Test behavior, not implementation details
- Avoid testing internal implementation

### Database Testing
- Use factories instead of fixtures
- Clean up database records after tests when necessary
- Consider using `RefreshDatabase` trait for integration tests
- Use `DatabaseMigrations` for migration-related tests

### HTTP Testing
- Use Laravel's fluent testing methods
- Test both successful and error scenarios
- Verify status codes, headers, and content
- Use `assertDatabaseHas` and `assertDatabaseMissing` to verify data changes

### Mocking
- Use mocks sparingly, prefer real implementations when possible
- Mock external services and APIs
- Use Laravel's built-in mocking helpers
- Verify interactions with mocks appropriately

### Performance
- Use `RefreshDatabase` instead of `DatabaseTransactions` when possible
- Consider using SQLite in-memory database for faster tests
- Group related assertions in single tests when appropriate
- Avoid unnecessary database operations in unit tests

## Common Pitfalls to Avoid

- Don't share state between tests
- Don't rely on test execution order
- Don't mock what you don't own
- Don't test Laravel internals
- Don't ignore failing tests

## Running Tests

### Basic Test Execution
```bash
# Run all tests
./vendor/bin/pest

# Run specific test file
./vendor/bin/pest tests/Feature/UserTest.php

# Run tests with group
./vendor/bin/pest --group feature
```

### With Coverage
```bash
# Generate coverage report
./vendor/bin/pest --coverage
```

### In Parallel
```bash
# Run tests in parallel
./vendor/bin/pest --parallel
```

### Watch Mode
```bash
# Run tests in watch mode (when supported)
./vendor/bin/pest --watch
```

For more information about Pest PHP, visit [https://pestphp.com](https://pestphp.com).