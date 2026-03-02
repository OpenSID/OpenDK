# Testing Patterns for OpenDK Project

This document outlines the testing patterns and best practices used in the OpenDK project for unit testing models and services using Pest v4.

## Table of Contents

1. [General Testing Principles](#general-testing-principles)
2. [Model Testing Patterns](#model-testing-patterns)
3. [Service Testing Patterns](#service-testing-patterns)
4. [Database Testing Patterns](#database-testing-patterns)
5. [Factory Patterns](#factory-patterns)
6. [Mocking and Faking](#mocking-and-faking)
7. [Assertion Patterns](#assertion-patterns)
8. [Test Organization](#test-organization)
9. [Best Practices](#best-practices)

## General Testing Principles

### Test Structure
Each test should follow the AAA pattern:
- **Arrange**: Set up the test data and conditions
- **Act**: Execute the code being tested
- **Assert**: Verify the expected outcome

```php
it('performs expected behavior', function () {
    // Arrange
    $user = User::factory()->create();
    
    // Act
    $result = $service->performAction($user);
    
    // Assert
    expect($result)->toBeTrue();
});
```

### Test Naming
- Use descriptive test names that explain what is being tested
- Use "it" syntax for natural language descriptions
- Focus on behavior, not implementation details

```php
it('validates user credentials correctly');
it('returns error when invalid data provided');
it('creates new record with valid input');
```

## Model Testing Patterns

### Basic Model Testing
Test basic CRUD operations and model properties:

```php
it('can create model with valid data', function () {
    $model = ModelName::factory()->create();
    
    expect($model)->toBeInstanceOf(ModelName::class);
    expect($model->id)->not->toBeNull();
});

it('can update model attributes', function () {
    $model = ModelName::factory()->create();
    
    $model->update(['attribute' => 'new value']);
    
    expect($model->attribute)->toBe('new value');
});

it('can delete model', function () {
    $model = ModelName::factory()->create();
    
    $model->delete();
    
    expect(ModelName::find($model->id))->toBeNull();
});
```

### Relationship Testing
Test model relationships thoroughly:

```php
it('belongs to related model', function () {
    $relatedModel = RelatedModel::factory()->create();
    $model = ModelName::factory()->create(['related_id' => $relatedModel->id]);
    
    expect($model->relatedModel)->not->toBeNull();
    expect($model->relatedModel->id)->toBe($relatedModel->id);
});

it('has many related models', function () {
    $model = ModelName::factory()->create();
    $related1 = RelatedModel::factory()->create(['model_id' => $model->id]);
    $related2 = RelatedModel::factory()->create(['model_id' => $model->id]);
    
    expect($model->relatedModels)->toHaveCount(2);
    expect($model->relatedModels->pluck('id'))->toContain($related1->id, $related2->id);
});
```

### Scope Testing
Test custom query scopes:

```php
it('filters by custom scope', function () {
    $activeModel = ModelName::factory()->create(['status' => 'active']);
    $inactiveModel = ModelName::factory()->create(['status' => 'inactive']);
    
    $activeModels = ModelName::active()->get();
    
    expect($activeModels)->toHaveCount(1);
    expect($activeModels->first()->id)->toBe($activeModel->id);
});

it('combines multiple scopes', function () {
    $model = ModelName::factory()->create(['status' => 'active', 'type' => 'premium']);
    $otherModel = ModelName::factory()->create(['status' => 'inactive', 'type' => 'premium']);
    
    $results = ModelName::active()->premium()->get();
    
    expect($results)->toHaveCount(1);
    expect($results->first()->id)->toBe($model->id);
});
```

### Accessor/Mutator Testing
Test custom attribute accessors and mutators:

```php
it('formats attribute using accessor', function () {
    $model = ModelName::factory()->create(['name' => 'john doe']);
    
    expect($model->formatted_name)->toBe('John Doe');
});

it('transforms attribute using mutator', function () {
    $model = ModelName::factory()->make();
    $model->name = 'john doe';
    $model->save();
    
    expect($model->name)->toBe('John Doe');
});
```

## Service Testing Patterns

### Basic Service Testing
Test service methods with proper mocking:

```php
it('performs service operation successfully', function () {
    // Arrange
    $input = ['key' => 'value'];
    $expectedResult = ['result' => 'success'];
    
    // Mock dependencies
    $repository = Mockery::mock(Repository::class);
    $repository->shouldReceive('create')->with($input)->andReturn($expectedResult);
    
    $service = new Service($repository);
    
    // Act
    $result = $service->performOperation($input);
    
    // Assert
    expect($result)->toBe($expectedResult);
});
```

### External API Testing
Test services that interact with external APIs:

```php
it('calls external API and returns response', function () {
    // Arrange
    $apiResponse = ['data' => 'test'];
    Http::fake([
        'api.example.com/*' => Http::response($apiResponse, 200)
    ]);
    
    $service = new ExternalApiService();
    
    // Act
    $result = $service->fetchData();
    
    // Assert
    expect($result)->toBe($apiResponse);
    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.example.com/data';
    });
});

it('handles API errors gracefully', function () {
    // Arrange
    Http::fake([
        'api.example.com/*' => Http::response(['error' => 'Service unavailable'], 500)
    ]);
    
    $service = new ExternalApiService();
    
    // Act & Assert
    expect(fn() => $service->fetchData())->toThrow(Exception::class);
});
```

### Caching Testing
Test services that use caching:

```php
it('caches results for performance', function () {
    // Arrange
    Cache::fake();
    $service = new CachedService();
    
    // Act
    $result1 = $service->getCachedData();
    $result2 = $service->getCachedData();
    
    // Assert
    expect($result1)->toBe($result2);
    Cache::assertHas('cache_key');
});

it('invalidates cache when data changes', function () {
    // Arrange
    Cache::fake();
    $service = new CachedService();
    
    // Act
    $service->getCachedData();
    $service->updateData(['new' => 'data']);
    
    // Assert
    Cache::assertMissing('cache_key');
});
```

## Database Testing Patterns

### Transaction Testing
Test database transactions and rollback behavior:

```php
it('commits successful transaction', function () {
    $initialCount = Model::count();
    
    DB::transaction(function () {
        Model::factory()->create();
    });
    
    expect(Model::count())->toBe($initialCount + 1);
});

it('rolls back failed transaction', function () {
    $initialCount = Model::count();
    
    try {
        DB::transaction(function () {
            Model::factory()->create();
            throw new Exception('Test exception');
        });
    } catch (Exception $e) {
        // Expected
    }
    
    expect(Model::count())->toBe($initialCount);
});
```

### Database Constraint Testing
Test database constraints and validations:

```php
it('enforces unique constraints', function () {
    $model = Model::factory()->create(['unique_field' => 'unique_value']);
    
    expect(fn() => Model::factory()->create(['unique_field' => 'unique_value']))
        ->toThrow(QueryException::class);
});

it('validates foreign key constraints', function () {
    expect(fn() => Model::factory()->create(['foreign_id' => 999]))
        ->toThrow(QueryException::class);
});
```

## Factory Patterns

### Basic Factory Usage
Use factories for creating test data:

```php
it('creates model with factory', function () {
    $model = ModelName::factory()->create();
    
    expect($model)->toBeInstanceOf(ModelName::class);
});

it('creates model with specific attributes', function () {
    $model = ModelName::factory()->create(['name' => 'Custom Name']);
    
    expect($model->name)->toBe('Custom Name');
});
```

### Factory Relationships
Create related models using factories:

```php
it('creates model with relationships', function () {
    $model = ModelName::factory()
        ->has(RelatedModel::factory()->count(3))
        ->create();
    
    expect($model->relatedModels)->toHaveCount(3);
});

it('creates nested relationships', function () {
    $model = ModelName::factory()
        ->has(RelatedModel::factory()
            ->has(AnotherModel::factory()->count(2))
        )
        ->create();
    
    expect($model->relatedModels->first()->anotherModels)->toHaveCount(2);
});
```

### Factory States
Use factory states for different variations:

```php
it('creates model with active state', function () {
    $model = ModelName::factory()->active()->create();
    
    expect($model->status)->toBe('active');
});

it('creates model with premium state', function () {
    $model = ModelName::factory()->premium()->create();
    
    expect($model->type)->toBe('premium');
});
```

## Mocking and Faking

### Facade Mocking
Mock Laravel facades for testing:

```php
it('sends email notification', function () {
    Mail::fake();
    
    $service = new NotificationService();
    $service->sendEmail($user, 'Test message');
    
    Mail::assertSent(TestEmail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

it('logs error messages', function () {
    Log::fake();
    
    $service = new LoggingService();
    $service->logError('Test error');
    
    Log::assertLogged('error', function ($level, $message) {
        return str_contains($message, 'Test error');
    });
});
```

### Event Testing
Test event dispatching:

```php
it('dispatches event when action performed', function () {
    Event::fake();
    
    $service = new EventService();
    $service->performAction();
    
    Event::assertDispatched(ActionPerformed::class);
});
```

## Assertion Patterns

### Basic Assertions
Use Pest's expectation syntax:

```php
expect($value)->toBe($expected);
expect($value)->toBeTrue();
expect($value)->toBeFalse();
expect($value)->toBeNull();
expect($value)->not->toBeNull();
expect($value)->toBeInstanceOf(Class::class);
```

### Collection Assertions
Test collections effectively:

```php
expect($collection)->toHaveCount($expected);
expect($collection)->toContain($item);
expect($collection)->not->toContain($item);
expect($collection->pluck('id'))->toContain($expectedId);
```

### Database Assertions
Verify database state:

```php
$this->assertDatabaseHas('table', ['field' => 'value']);
$this->assertDatabaseMissing('table', ['field' => 'value']);
$this->assertModelExists($model);
$this->assertModelMissing($model);
```

## Test Organization

### Test File Structure
Organize tests by feature and type:

```
tests/
├── Unit/
│   ├── Models/
│   │   ├── UserTest.php
│   │   ├── PendudukTest.php
│   │   └── ModelRelationshipsTest.php
│   ├── Services/
│   │   ├── DesaServiceTest.php
│   │   └── OtpServiceTest.php
│   └── Database/
│       └── TransactionTest.php
├── Feature/
└── Pest.php
```

### Test Grouping
Use test groups for organization:

```php
uses(\Tests\TestCase::class)->in('Unit');
uses(\Tests\TestCase::class)->in('Feature');

// Group related tests
it('performs user authentication', function () {
    // Test implementation
})->group('authentication');

it('validates user input', function () {
    // Test implementation
})->group('validation');
```

### Setup and Teardown
Use beforeEach and afterEach hooks:

```php
beforeEach(function () {
    // Setup code that runs before each test
    $this->service = new TestService();
});

afterEach(function () {
    // Cleanup code that runs after each test
    Mockery::close();
});
```

## Best Practices

### Test Independence
Each test should be independent and not rely on other tests:

```php
// Good: Each test creates its own data
it('creates user with valid data', function () {
    $user = User::factory()->create(['email' => 'test@example.com']);
    expect($user->email)->toBe('test@example.com');
});

// Bad: Relies on data from previous test
it('updates user created in previous test', function () {
    $user = User::where('email', 'test@example.com')->first();
    // This test depends on the previous test
});
```

### Test Only Public APIs
Test only public methods and interfaces:

```php
// Good: Test public method
it('calculates total price correctly', function () {
    $result = $service->calculateTotal($items);
    expect($result)->toBe($expected);
});

// Bad: Test private method directly
it('calculates tax internally', function () {
    $reflection = new ReflectionClass($service);
    $method = $reflection->getMethod('calculateTax');
    $method->setAccessible(true);
    $result = $method->invoke($service, $amount);
    expect($result)->toBe($expected);
});
```

### Use Meaningful Test Data
Use realistic and meaningful test data:

```php
// Good: Meaningful test data
it('validates email format correctly', function () {
    $validEmails = ['user@example.com', 'test.email+tag@domain.co.uk'];
    $invalidEmails = ['invalid', '@domain.com', 'user@'];
    
    foreach ($validEmails as $email) {
        expect($validator->isValid($email))->toBeTrue();
    }
    
    foreach ($invalidEmails as $email) {
        expect($validator->isValid($email))->toBeFalse();
    }
});

// Bad: Meaningless test data
it('validates email format', function () {
    expect($validator->isValid('a'))->toBeTrue();
    expect($validator->isValid('b'))->toBeFalse();
});
```

### Test Edge Cases
Test edge cases and error conditions:

```php
it('handles empty input gracefully', function () {
    expect(fn() => $service->process([]))->toThrow(InvalidArgumentException::class);
});

it('handles null values correctly', function () {
    $result = $service->process(null);
    expect($result)->toBeNull();
});

it('handles maximum limits', function () {
    $largeData = array_fill(0, 10000, 'item');
    expect($service->process($largeData))->toHaveCount(10000);
});
```

### Keep Tests Simple and Focused
Each test should focus on one specific behavior:

```php
// Good: Single responsibility
it('validates email format');
it('validates password strength');
it('validates username uniqueness');

// Bad: Multiple responsibilities
it('validates email, password, and username');
```

### Use Descriptive Variable Names
Use clear and descriptive variable names in tests:

```php
// Good: Descriptive names
$activeUser = User::factory()->active()->create();
$expiredToken = OtpToken::factory()->expired()->create();
$invalidCredentials = ['email' => 'invalid@example.com', 'password' => 'wrong'];

// Bad: Unclear names
$user1 = User::factory()->create();
$token = OtpToken::factory()->create();
$data = ['email' => 'test', 'password' => 'test'];
```

## Conclusion

Following these patterns will help ensure that your tests are:
- **Maintainable**: Easy to understand and modify
- **Reliable**: Consistent and trustworthy results
- **Comprehensive**: Covering all important scenarios
- **Efficient**: Fast and resource-conscious

Remember that tests are code too, and should be treated with the same care and attention to detail as your production code.