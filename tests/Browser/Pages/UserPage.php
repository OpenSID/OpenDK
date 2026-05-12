<?php

namespace Tests\Browser\Pages;

class UserPage
{
    /**
     * Get the URL for the user management page.
     */
    public function url(): string
    {
        return '/setting/user';
    }

    /**
     * Create a new user.
     */
    public function createUser($browser, array $userDetails): void
    {
        // Session is maintained in the browser instance
        $browser->waitForText('Pengguna')
            ->click('a[href$="/create"]')
            ->waitForText('Tambah Data')
            ->type('name', $userDetails['name'])
            ->type('email', $userDetails['email'])
            ->type('password', $userDetails['password'])
            ->type('address', $userDetails['address'])
            ->select('role', $userDetails['role'])
            ->press('Simpan');
    }

    /**
     * Edit an existing user.
     */
    public function editUser($browser, $email, array $newDetails): void
    {
        $browser->waitForText($email)
            ->click('a[href*="/edit/"]')
            ->type('name', $newDetails['name'])
            ->press('Simpan');
    }

    /**
     * Delete a user.
     */
    public function deleteUser($browser, $email): void
    {
        $browser->waitForText($email)
            ->click('a[href*="/destroy/"]')
            ->acceptDialog();
    }
}
