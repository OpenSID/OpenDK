<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 */

use App\Models\User;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

uses(DatabaseTransactions::class);

beforeEach(function () {
    // Ensure we are logged out
    auth()->logout();

    // Disable captcha for all login tests
    SettingAplikasi::updateOrCreate(
        ['key' => 'google_recaptcha'],
        ['value' => 0]
    );

    $this->password = 'Password123!';
    $this->user = User::factory()->create([
        'password' => Hash::make($this->password),
        'status' => 1,
    ]);
});

describe('Login Flow', function () {
    test('login page is accessible', function () {
        $this->get(route('login'))
            ->assertOk()
            ->assertViewIs('auth.login');
    });

    test('user can login with valid credentials', function () {
        $response = $this->post(route('login'), [
            'email' => $this->user->email,
            'password' => $this->password,
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($this->user);
    });

    test('login fails with incorrect password', function () {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => $this->user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    });

    test('login fails with non-existent email', function () {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'nonexistent@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    });

    test('login succeeds with inactive user status (current behavior)', function () {
        $inactiveUser = User::factory()->create([
            'password' => Hash::make($this->password),
            'status' => 0,
        ]);

        $response = $this->post(route('login'), [
            'email' => $inactiveUser->email,
            'password' => $this->password,
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($inactiveUser);
    });
});

describe('Remember Me', function () {
    test('login with remember me sets the cookie', function () {
        $response = $this->post(route('login'), [
            'email' => $this->user->email,
            'password' => $this->password,
            'remember' => 'on',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($this->user);

        // Check if a remember cookie is present in the response
        $cookies = $response->headers->getCookies();
        $rememberCookie = collect($cookies)->first(function ($cookie) {
            return str_contains($cookie->getName(), 'remember_web_');
        });

        expect($rememberCookie)->not->toBeNull();
    });
});

describe('Session Management', function () {
    test('session is regenerated after login', function () {
        $oldSessionId = Session::getId();

        $this->post(route('login'), [
            'email' => $this->user->email,
            'password' => $this->password,
        ]);

        expect(Session::getId())->not->toBe($oldSessionId);
    });

    test('session is invalidated after logout', function () {
        $this->actingAs($this->user);

        $oldSessionId = Session::getId();

        $this->post(route('logout'));

        expect(Session::getId())->not->toBe($oldSessionId);
        $this->assertGuest();
    });
});

describe('Logout Flow', function () {
    test('authenticated user can logout', function () {
        $this->actingAs($this->user);

        $response = $this->post(route('logout'));

        $response->assertRedirect('/');
        $this->assertGuest();
    });

    test('guest cannot access protected routes and is redirected to login', function () {
        $response = $this->get(route('dashboard'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    });
});

describe('Password Redirection', function () {
    test('user is redirected to change default password if password is "password"', function () {
        $defaultUser = User::factory()->create([
            'password' => Hash::make('password'),
            'status' => 1,
        ]);

        $response = $this->post(route('login'), [
            'email' => $defaultUser->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(url('/changedefault'));
    });
});
