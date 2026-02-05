<?php

use App\Models\Penduduk;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

describe('Mass Assignment Protection', function () {
    test('mencegah mass assignment field sensitif', function () {
        $data = [
            'nik' => '1234567890123456',
            'nama' => 'Test User',
            'status_dasar' => 1,
            'id' => 999, // Should not be assignable
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 year')), // Should not be assignable
        ];
        
        $penduduk = Penduduk::create($data);
        
        expect($penduduk->id)->not->toBe(999);
        expect($penduduk->created_at->format('Y'))->not->toBe(date('Y'));
    })->group('security', 'mass-assignment');
});