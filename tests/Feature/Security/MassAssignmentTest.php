<?php

use App\Models\Penduduk;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;

uses(DatabaseTransactions::class);

describe('Mass Assignment Protection', function () {
    test('mencegah mass assignment field sensitif', function () {
        $createdAt = Carbon::now()->subYear();
        $data = [
            'nik' => '1234567890123456',
            'nama' => 'Test User',
            'status_dasar' => 1,
            'id' => 999, // Should not be assignable
            'created_at' => $createdAt
        ];
        
        $penduduk = Penduduk::create($data);
        
        expect($penduduk->id)->not->toBe(999);
        expect($penduduk->created_at->format('Y'))->toBe($createdAt->format('Y'));
    })->group('security', 'mass-assignment');
});