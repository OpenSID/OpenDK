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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

uses(DatabaseTransactions::class);

beforeEach(function () {
    auth()->logout();
    $this->password = 'Password123!';
    $this->user = User::factory()->create([
        'password' => Hash::make($this->password),
        'status' => 1,
    ]);
});

describe('Password Redirection', function () {
    test('user with default password "password" is redirected to change password page', function () {
        $user = User::factory()->create(['password' => Hash::make('password')]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(url('/changedefault'));
    });
});

describe('Password Change Requirements', function () {
    test('mandatory password change form works', function () {
        $user = User::factory()->create(['password' => Hash::make('password')]);
        // The group has 'role:administrator-website|super-admin|admin-kecamatan|kontributor-artikel' middleware
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super-admin']);
        $user->assignRole($role);

        $this->actingAs($user);

        $response = $this->post(route('changedefault.store'), [
            'password' => 'NewSecurePassword123!',
            'password_confirmation' => 'NewSecurePassword123!',
        ]);

        $response->assertRedirect(route('dashboard'));
        expect(Hash::check('NewSecurePassword123!', $user->fresh()->password))->toBeTrue();
    });

    test('mandatory password change fails with invalid password format', function () {
        $user = User::factory()->create(['password' => Hash::make('password')]);
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super-admin']);
        $user->assignRole($role);

        $this->actingAs($user);

        $response = $this->from(route('change-default'))->post(route('changedefault.store'), [
            'password' => 'weak',
            'password_confirmation' => 'weak',
        ]);

        $response->assertRedirect(route('change-default'));
        $response->assertSessionHasErrors('password');
        expect(Hash::check('password', $user->fresh()->password))->toBeTrue();
    });
});
