<?php

use App\Models\PasswordHistory;
use App\Models\User;
use App\Services\PasswordHistoryService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->password = 'Password123!';
    $this->newPassword = 'NewPassword123!';
    $this->user = User::factory()->create([
        'password' => Hash::make($this->password),
        'status' => 1,
    ]);
    $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super-admin']);
    $this->user->assignRole($role);
    $this->actingAs($this->user);
});

describe('PasswordHistoryService', function () {
    it('detects reused password against current password', function () {
        $service = app(PasswordHistoryService::class);

        $reused = $service->isPasswordReused($this->user, $this->password);

        expect($reused)->toBeTrue();
    });

    it('detects reused password against password history', function () {
        $service = app(PasswordHistoryService::class);

        PasswordHistory::factory()->create([
            'user_id' => $this->user->id,
            'password' => Hash::make('OldPassword123!'),
        ]);

        $reused = $service->isPasswordReused($this->user, 'OldPassword123!');

        expect($reused)->toBeTrue();
    });

    it('allows new password not in history', function () {
        $service = app(PasswordHistoryService::class);

        $reused = $service->isPasswordReused($this->user, $this->newPassword);

        expect($reused)->toBeFalse();
    });

    it('stores current password and prunes to max 10 records', function () {
        $service = app(PasswordHistoryService::class);

        foreach (range(1, 10) as $i) {
            $this->user->passwordHistories()->create([
                'password' => Hash::make("Password{$i}123!"),
            ]);
        }

        expect($this->user->passwordHistories()->count())->toBe(10);

        $service->storeCurrentPassword($this->user);

        expect($this->user->passwordHistories()->count())->toBe(10);
    });

    it('removes oldest records when pruning', function () {
        $service = app(PasswordHistoryService::class);

        foreach (range(1, 12) as $i) {
            $this->user->passwordHistories()->create([
                'password' => Hash::make("Password{$i}123!"),
                'created_at' => now()->subHours(2)->addMinutes($i * 5),
            ]);
        }

        expect($this->user->passwordHistories()->count())->toBe(12);

        $service->storeCurrentPassword($this->user);

        expect($this->user->passwordHistories()->count())->toBe(10);
    });
});

describe('Profile self-service password change', function () {
    it('blocks password reuse from current password', function () {
        $response = $this->from(route('profile.password'))->post(route('profile.password.update'), [
            'current_password' => $this->password,
            'password' => $this->password,
            'password_confirmation' => $this->password,
        ]);

        $response->assertSessionHasErrors('password');
        expect(Hash::check($this->password, $this->user->fresh()->password))->toBeTrue();
    });

    it('blocks password reuse from history', function () {
        PasswordHistory::factory()->create([
            'user_id' => $this->user->id,
            'password' => Hash::make('OldPassword123!'),
        ]);

        $response = $this->from(route('profile.password'))->post(route('profile.password.update'), [
            'current_password' => $this->password,
            'password' => 'OldPassword123!',
            'password_confirmation' => 'OldPassword123!',
        ]);

        $response->assertSessionHasErrors('password');
        expect(Hash::check($this->password, $this->user->fresh()->password))->toBeTrue();
    });

    it('allows new password and stores old password to history', function () {
        $response = $this->from(route('profile.password'))->post(route('profile.password.update'), [
            'current_password' => $this->password,
            'password' => $this->newPassword,
            'password_confirmation' => $this->newPassword,
        ]);

        $response->assertSessionHasNoErrors();
        expect(Hash::check($this->newPassword, $this->user->fresh()->password))->toBeTrue();

        $this->user->fresh();
        $historyCount = $this->user->passwordHistories()->count();
        expect($historyCount)->toBe(1);
        expect(Hash::check($this->password, $this->user->passwordHistories()->first()->password))->toBeTrue();
    });
});

describe('Default password change (first login)', function () {
    it('blocks password reuse from history', function () {
        PasswordHistory::factory()->create([
            'user_id' => $this->user->id,
            'password' => Hash::make('OldPassword123!'),
        ]);

        auth()->logout();
        auth()->login($this->user);

        $response = $this->from(route('change-default'))->post(route('changedefault.store'), [
            'password' => 'OldPassword123!',
            'password_confirmation' => 'OldPassword123!',
        ]);

        $response->assertSessionHasErrors('password');
    });

    it('allows new password and stores old password to history', function () {
        auth()->logout();
        auth()->login($this->user);

        $response = $this->from(route('change-default'))->post(route('changedefault.store'), [
            'password' => $this->newPassword,
            'password_confirmation' => $this->newPassword,
        ]);

        $response->assertRedirect(route('dashboard'));
        expect(Hash::check($this->newPassword, $this->user->fresh()->password))->toBeTrue();
        expect($this->user->passwordHistories()->count())->toBe(1);
    });
});

describe('Admin password reset via UserController', function () {
    it('blocks password reuse from history', function () {
        PasswordHistory::factory()->create([
            'user_id' => $this->user->id,
            'password' => Hash::make('OldPassword123!'),
        ]);

        $response = $this->put(route('setting.user.updatePassword', $this->user->id), [
            'password' => 'OldPassword123!',
            'password_confirmation' => 'OldPassword123!',
        ]);

        $response->assertSessionHasErrors('password');
    });

    it('allows new password and stores old password to history', function () {
        $response = $this->put(route('setting.user.updatePassword', $this->user->id), [
            'password' => $this->newPassword,
            'password_confirmation' => $this->newPassword,
        ]);

        expect(Hash::check($this->newPassword, $this->user->fresh()->password))->toBeTrue();
        expect($this->user->passwordHistories()->count())->toBe(1);
    });
});

describe('Password history pruning', function () {
    it('keeps only the last 10 passwords in history', function () {
        foreach (range(1, 15) as $i) {
            $this->user->passwordHistories()->create([
                'password' => Hash::make("Password{$i}123!"),
                'created_at' => now()->addMinutes($i),
            ]);
        }

        expect($this->user->passwordHistories()->count())->toBe(15);

        $service = app(PasswordHistoryService::class);
        $service->prune($this->user);

        expect($this->user->passwordHistories()->count())->toBe(10);
    });
});
