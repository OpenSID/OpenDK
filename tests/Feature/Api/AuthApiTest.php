<?php

use App\Models\User;
use Database\Seeders\RoleSpatieSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use function Pest\Laravel\postJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\seed;
use function Pest\Laravel\withToken;
use function Pest\Laravel\actingAs;
use Illuminate\Support\Facades\Auth;

uses(DatabaseTransactions::class);

beforeEach(function () {
    // Logout any user auto-logged in by TestCase::setUp
    Auth::logout();

    seed(RoleSpatieSeeder::class);

    $user = User::factory()->create([
        'email' => 'admin@mail.com',
        'password' => 'password',
        'status' => 1,
    ]);
    $user->assignRole('super-admin');
    $this->user = $user;
});

test('user can login with valid credentials', function () {
    $response = postJson('/api/v1/auth/login', [
        'email' => 'admin@mail.com',
        'password' => 'password',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
        ]);
});

test('user cannot login with invalid credentials', function () {
    $response = postJson('/api/v1/auth/login', [
        'email' => 'admin@mail.com',
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(401)
        ->assertJson(['message' => 'Pengguna tidak dikenali']);
});

test('user cannot login if not admin-desa or super-admin', function () {
    $user = User::factory()->create([
        'email' => 'user@mail.com',
        'password' => 'password',
        'status' => 1,
    ]);
    // No role assigned or wrong role

    $response = postJson('/api/v1/auth/login', [
        'email' => 'user@mail.com',
        'password' => 'password',
    ]);

    $response->assertStatus(422)
        ->assertJson(['message' => 'Grup pengguna bukan admin-desa']);
});

test('authenticated user can get their profile', function () {
    $loginResponse = postJson('/api/v1/auth/login', [
        'email' => 'admin@mail.com',
        'password' => 'password',
    ]);

    $token = $loginResponse->json('access_token');

    $response = withToken($token)->getJson('/api/v1/auth/me');

    $response->assertStatus(200)
        ->assertJsonStructure(['user']);

    expect($response->json('user.email'))->toBe('admin@mail.com');
});

test('user can refresh their token', function () {
    $loginResponse = postJson('/api/v1/auth/login', [
        'email' => 'admin@mail.com',
        'password' => 'password',
    ]);

    $token = $loginResponse->json('access_token');

    $response = withToken($token)->postJson('/api/v1/auth/refresh');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
        ]);

    expect($response->json('access_token'))->not->toBe($token);
});

test('user can logout', function () {
    $loginResponse = postJson('/api/v1/auth/login', [
        'email' => 'admin@mail.com',
        'password' => 'password',
    ]);

    $token = $loginResponse->json('access_token');

    $response = withToken($token)->postJson('/api/v1/auth/logout');

    $response->assertStatus(200)
        ->assertJson(['message' => 'Successfully logged out']);
});

test('unauthenticated user cannot access me endpoint', function () {
    Auth::logout(); // Ensure we are logged out
    $response = getJson('/api/v1/auth/me');

    $response->assertStatus(401);
});