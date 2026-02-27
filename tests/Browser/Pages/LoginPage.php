<?php

namespace Tests\Browser\Pages;

class LoginPage
{
    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return '/login';
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert($browser): void
    {
        $browser->assertPathIs($this->url())
            ->assertSee('Login')
            ->assertPresent('#email')
            ->assertPresent('#password')
            ->assertPresent('button[type="submit"]');
    }

    /**
     * Get the element shortcuts for the page.
     */
    public function elements(): array
    {
        return [
            '@email' => '#email',
            '@password' => '#password',
            '@submit' => 'button[type="submit"]',
            '@signin' => 'button:contains("Sign In")',
        ];
    }

    /**
     * Login the user.
     */
    public function login($browser, $email, $password): void
    {
        $browser->type('#email', $email)
            ->type('#password', $password)
            ->click('button[type="submit"]');
    }
}
