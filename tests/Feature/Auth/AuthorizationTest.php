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
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

uses(DatabaseTransactions::class);

beforeEach(function () {
    auth()->logout();

    // Ensure roles exist
    $this->adminRole = Role::firstOrCreate(['name' => 'super-admin']);
    $this->kecamatanRole = Role::firstOrCreate(['name' => 'admin-kecamatan']);

    $this->admin = User::factory()->create(['status' => 1]);
    $this->admin->assignRole($this->adminRole);

    $this->user = User::factory()->create(['status' => 1]);
    $this->user->assignRole($this->kecamatanRole);
});

describe('Role Based Access Control', function () {
    test('super-admin can access dashboard', function () {
        $this->actingAs($this->admin)
            ->get(route('dashboard'))
            ->assertOk();
    });

    test('admin-kecamatan can access dashboard', function () {
        $this->actingAs($this->user)
            ->get(route('dashboard'))
            ->assertOk();
    });

    test('guest cannot access dashboard', function () {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    });
});

describe('Permission Checks', function () {
    test('user without required role cannot access administrative routes', function () {
        $guestUser = User::factory()->create(['status' => 1]);
        // No roles assigned

        $this->actingAs($guestUser)
            ->get(route('informasi.artikel.index'))
            ->assertForbidden();
    });

    test('user with kontributor-artikel role can access artikel index', function () {
        $role = Role::firstOrCreate(['name' => 'kontributor-artikel']);
        $contributor = User::factory()->create(['status' => 1]);
        $contributor->assignRole($role);

        $this->actingAs($contributor)
            ->get(route('informasi.artikel.index'))
            ->assertOk();
    });
});
