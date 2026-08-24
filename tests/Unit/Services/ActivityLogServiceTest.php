<?php

use App\Services\ActivityLogService;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

uses(\Illuminate\Foundation\Testing\DatabaseTransactions::class);

beforeEach(function () {
    // Create a test user
    $this->user = User::factory()->create();

    // Fake a request
    $request = \Illuminate\Http\Request::create('/test-url', 'GET');
    $request->headers->set('User-Agent', 'Test Agent');
    $request->server->set('REMOTE_ADDR', '127.0.0.1');
    $this->app->instance('request', $request);

    Activity::query()->delete();
});

afterEach(function () {
    $this->app->forgetInstance('request');
});

it('can instantiate ActivityLogService', function () {
    $service = new ActivityLogService();
    expect($service)->toBeInstanceOf(ActivityLogService::class);
});

it('logs activity with default properties', function () {
    $service = new ActivityLogService();
    
    $activity = $service->log('test_event', 'Test description');
    
    expect($activity)->toBeInstanceOf(Activity::class);
    expect($activity->event)->toBe('test_event');
    expect($activity->description)->toBe('Test description');
    expect($activity->properties)->toHaveKey('ip_address');
    expect($activity->properties)->toHaveKey('user_agent');
    expect($activity->properties)->toHaveKey('url_slug');
});

it('logs activity with authenticated user', function () {
    $service = new ActivityLogService();
    $this->actingAs($this->user);
    
    $activity = $service->log('user_action', 'User performed action');
    
    expect($activity)->toBeInstanceOf(Activity::class);
    expect($activity->event)->toBe('user_action');
    expect($activity->causer_id)->toBe($this->user->id);
    expect($activity->causer_type)->toBe(User::class);
});

it('includes custom properties in log', function () {
    $service = new ActivityLogService();
    
    $activity = $service->log('custom_event', 'Custom description', [
        'key1' => 'value1',
        'key2' => 'value2',
    ]);
    
    expect($activity->properties)->toHaveKey('key1', 'value1');
    expect($activity->properties)->toHaveKey('key2', 'value2');
    expect($activity->properties)->toHaveKey('ip_address');
    expect($activity->properties)->toHaveKey('user_agent');
    expect($activity->properties)->toHaveKey('url_slug');
});

it('captures request metadata automatically', function () {
    $service = new ActivityLogService();
    
    $activity = $service->log('request_test', 'Test request capture');
    
    expect($activity->properties['ip_address'])->toBe('127.0.0.1');
    expect($activity->properties['user_agent'])->toBe('Test Agent');
    // The path may or may not have a leading slash depending on the request context
    expect($activity->properties['url_slug'])->toMatch('/^test-url/');
});

it('filters activities by date range', function () {
    $service = new ActivityLogService();
    
    // Create activities on different dates
    Activity::create(['log_name' => 'default', 'event' => 'old', 'description' => 'Old activity', 'created_at' => now()->subDays(5)]);
    $recent = Activity::create(['log_name' => 'default', 'event' => 'recent', 'description' => 'Recent activity', 'created_at' => now()->subDays(2)]);
    Activity::create(['log_name' => 'default', 'event' => 'very_old', 'description' => 'Very old activity', 'created_at' => now()->subDays(10)]);
    
    $query = $service->getFilteredActivities(now()->subDays(3)->format('Y-m-d'), now()->format('Y-m-d'), null, null, null);
    $results = $query->get();
    
    expect($results->count())->toBe(1);
    expect($results->first()->id)->toBe($recent->id);
});

it('filters activities by user', function () {
    $service = new ActivityLogService();
    $otherUser = User::factory()->create();
    
    $userActivity = Activity::create(['log_name' => 'default', 'causer_id' => $this->user->id, 'event' => 'user_event', 'description' => 'User event']);
    Activity::create(['log_name' => 'default', 'causer_id' => $otherUser->id, 'event' => 'other_event', 'description' => 'Other event']);
    
    $query = $service->getFilteredActivities(null, null, $this->user->id, null, null);
    $results = $query->get();
    
    expect($results->count())->toBe(1);
    expect($results->first()->id)->toBe($userActivity->id);
});

it('filters activities by event type', function () {
    $service = new ActivityLogService();
    
    Activity::create(['log_name' => 'default', 'event' => 'login', 'description' => 'Login']);
    $target = Activity::create(['log_name' => 'default', 'event' => 'logout', 'description' => 'Logout']);
    Activity::create(['log_name' => 'default', 'event' => 'login', 'description' => 'Login 2']);
    
    $query = $service->getFilteredActivities(null, null, null, 'logout', null);
    $results = $query->get();
    
    expect($results->count())->toBe(1);
    expect($results->first()->id)->toBe($target->id);
});

it('filters activities by keyword in description', function () {
    $service = new ActivityLogService();
    
    Activity::create(['log_name' => 'default', 'description' => 'User logged in']);
    $target = Activity::create(['log_name' => 'default', 'description' => 'User updated profile']);
    Activity::create(['log_name' => 'default', 'description' => 'User logged out']);
    
    $query = $service->getFilteredActivities(null, null, null, null, 'profile');
    $results = $query->get();
    
    expect($results->count())->toBe(1);
    expect($results->first()->id)->toBe($target->id);
});

it('filters activities by keyword in url_slug', function () {
    $service = new ActivityLogService();
    
    Activity::create(['log_name' => 'default', 'properties' => ['url_slug' => '/dashboard'], 'description' => 'Dashboard']);
    $target = Activity::create(['log_name' => 'default', 'properties' => ['url_slug' => '/setting/profil'], 'description' => 'Profil']);
    Activity::create(['log_name' => 'default', 'properties' => ['url_slug' => '/setting/user'], 'description' => 'User']);
    
    $query = $service->getFilteredActivities(null, null, null, null, 'profil');
    $results = $query->get();
    
    expect($results->count())->toBe(1);
    expect($results->first()->id)->toBe($target->id);
});

it('returns all activities when no filters applied', function () {
    $service = new ActivityLogService();
    
    Activity::create(['log_name' => 'default', 'event' => 'login', 'description' => 'Login']);
    Activity::create(['log_name' => 'default', 'event' => 'logout', 'description' => 'Logout']);
    Activity::create(['log_name' => 'default', 'event' => 'login', 'description' => 'Login 2']);
    
    $query = $service->getFilteredActivities(null, null, null, null, null);
    $results = $query->get();
    
    expect($results->count())->toBe(3);
});

it('handles empty filters gracefully', function () {
    $service = new ActivityLogService();
    
    $query = $service->getFilteredActivities('', '', null, '', '');
    $results = $query->get();
    
    expect($results)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);
});