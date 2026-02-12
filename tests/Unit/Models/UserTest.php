<?php

use App\Models\User;
use App\Models\Pengurus;
use App\Models\OtpToken;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;



beforeEach(function () {
    // Create default role and permission if they don't exist
    Role::firstOrCreate(['name' => 'admin']);
    Permission::firstOrCreate(['name' => 'manage-users']);
});

it('can create a user', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
    ]);

    expect($user)->toBeInstanceOf(User::class);
    expect($user->name)->toBe('John Doe');
    expect($user->email)->toBe('john@example.com');
    expect(Hash::check('password123', $user->password))->toBeTrue();
});

it('has default password constant', function () {
    expect(User::DEFAULT_PASSWORD)->toBeString()->toBe('12345678');
});

it('has fillable attributes', function () {
    $user = User::factory()->make();

    $fillable = [
        'email', 'password', 'permissions', 'name', 'image', 'address',
        'phone', 'telegram_id', 'gender', 'status', 'last_login',
        'pengurus_id', 'otp_enabled', 'two_fa_enabled', 'otp_channel', 'otp_verified'
    ];

    foreach ($fillable as $field) {
        expect(in_array($field, $user->getFillable()))->toBeTrue();
    }
});

it('hides sensitive attributes', function () {
    $user = User::factory()->create([
        'password' => 'secret',
    ]);

    $hidden = ['password', 'remember_token'];

    foreach ($hidden as $attribute) {
        expect(array_key_exists($attribute, $user->toArray()))->toBeFalse();
    }
});

it('casts attributes correctly', function () {
    $user = User::factory()->create([
        'otp_enabled' => true,
        'two_fa_enabled' => false,
    ]);

    expect($user->otp_enabled)->toBeBool()->toBeTrue();
    expect($user->two_fa_enabled)->toBeBool()->toBeFalse();
});

it('can assign roles using spatie permission', function () {
    $user = User::factory()->create();

    $user->assignRole('admin');

    expect($user->hasRole('admin'))->toBeTrue();
});

it('can sync roles using spatie permission', function () {
    $user = User::factory()->create();

    $user->syncRoles(['admin']);

    expect($user->hasRole('admin'))->toBeTrue();
});

it('can give permissions using spatie permission', function () {
    $user = User::factory()->create();

    $user->givePermissionTo('manage-users');

    expect($user->can('manage-users'))->toBeTrue();
});

it('has pengurus relationship', function () {
    $user = User::factory()->create();
    
    expect($user->pengurus())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasOne::class);
});

it('has otp tokens relationship', function () {
    $user = User::factory()->create();
    
    expect($user->otpTokens())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
});

it('mutates password attribute when setting', function () {
    $user = User::factory()->make();
    $user->password = 'newpassword';

    expect(Hash::check('newpassword', $user->password))->toBeTrue();
});

it('has foto accessor', function () {
    $user = User::factory()->create(['image' => 'test-avatar.jpg']);

    // Check that the foto attribute returns the correct URL
    expect($user->foto)->toBeString(); // Should return a URL string
});

it('has suspend scope', function () {
    User::factory()->create(['email' => 'active@test.com', 'status' => 1]);
    User::factory()->create(['email' => 'suspended@test.com', 'status' => 0]);

    $suspendedUsers = User::suspend('suspended@test.com');

    expect($suspendedUsers->count())->toBeGreaterThanOrEqual(1);
    expect($suspendedUsers->first()->email)->toBe('suspended@test.com');
    expect($suspendedUsers->first()->status)->toBe(0);
});

it('implements JWT subject interface', function () {
    $user = User::factory()->create();

    expect($user->getJWTIdentifier())->toBe($user->id);
    expect($user->getJWTCustomClaims())->toBeArray()->toBeEmpty();
});