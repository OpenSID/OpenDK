<?php

namespace Tests\Traits;

use App\Models\User;

trait WithUserAuthentication
{
    /**
     * Authenticate as a user.
     */
    protected function actingAsUser($user = null)
    {
        $user = $user ?? User::first() ?? User::factory()->create();
        return $this->actingAs($user);
    }

    /**
     * Authenticate as an admin.
     */
    protected function actingAsAdmin($admin = null)
    {
        // For OpenDK, admin usually has specific roles/permissions, 
        // but for now we follow the existing pattern in TestCase.php
        $admin = $admin ?? User::first() ?? User::factory()->create();
        return $this->actingAs($admin);
    }
}
