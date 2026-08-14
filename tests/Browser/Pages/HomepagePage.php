<?php

namespace Tests\Browser\Pages;

class HomepagePage
{
    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return '/';
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert($browser): void
    {
        $browser->assertPathIs($this->url())
            ->assertPresent('meta[name="viewport"]')
            ->assertPresent('nav, .navbar, .menu');
    }

    /**
     * Get the element shortcuts for the page.
     */
    public function elements(): array
    {
        return [
            '@navbar' => 'nav, .navbar',
            '@content' => '.content, main',
            '@footer' => 'footer',
        ];
    }
}
