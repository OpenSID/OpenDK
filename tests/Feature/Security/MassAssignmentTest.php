<?php

use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;

uses(DatabaseTransactions::class);

describe('Mass Assignment Protection', function () {
    test('penduduk model mencegah mass assignment semua field via guarded', function () {
        $penduduk = new Penduduk();

        // Penduduk model uses $guarded for all sensitive fields
        // Verify that guarded fields are not mass-assignable via fill()
        $data = [
            'nik' => '1234567890123456',
            'nama' => 'Test User',
            'status_dasar' => 1,
            'desa_id' => 1,
        ];

        $penduduk->fill($data);

        // All these fields are in $guarded, so they should NOT be filled
        $attributes = $penduduk->getAttributes();
        expect($attributes)->not->toHaveKey('nik');
        expect($attributes)->not->toHaveKey('nama');
        expect($attributes)->not->toHaveKey('status_dasar');
        expect($attributes)->not->toHaveKey('desa_id');
    })->group('security', 'mass-assignment');

    test('user model mencegah mass assignment field sensitif', function () {
        $user = new User();

        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'status' => 1,          // guarded - should be blocked
            'last_login' => now(),   // guarded - should be blocked
        ];

        $user->fill($data);

        // Fillable fields should be filled
        expect($user->name)->toBe('Test User');
        expect($user->email)->toBe('test@example.com');

        // Guarded fields should NOT be filled via mass assignment
        // Use getAttributes() to check raw values (avoid Spatie trait conflicts)
        $attributes = $user->getAttributes();
        expect($attributes)->not->toHaveKey('status');
        expect($attributes)->not->toHaveKey('last_login');
    })->group('security', 'mass-assignment');
});