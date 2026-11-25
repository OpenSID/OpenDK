<?php

use App\Models\AnggaranRealisasi;
use App\Models\Profil;
use App\Services\AnggaranRealisasiChartService;

// Tahun unik agar tidak bercampur dengan data yang sudah ada di database test.
const TAHUN_UJI_ANGGARAN_REALISASI = 2099;

function buatAnggaranRealisasi(array $nilai): void
{
    AnggaranRealisasi::factory()->create(array_merge([
        'profil_id' => Profil::first()->id,
        'bulan' => 1,
        'tahun' => TAHUN_UJI_ANGGARAN_REALISASI,
        'total_anggaran' => 2000,
        'total_belanja' => 1000,
        'belanja_pegawai' => 0,
        'belanja_barang_jasa' => 0,
        'belanja_modal' => 0,
        'belanja_tidak_langsung' => 0,
    ], $nilai));
}

function chartAnggaranRealisasi(): array
{
    return (new AnggaranRealisasiChartService)->chart(1, TAHUN_UJI_ANGGARAN_REALISASI);
}

describe('AnggaranRealisasiChartService', function () {
    it('menghitung persentase belanja barang jasa walaupun belanja pegawai nol', function () {
        buatAnggaranRealisasi([
            'belanja_pegawai' => 0,
            'belanja_barang_jasa' => 500,
        ]);

        $hasil = chartAnggaranRealisasi();

        expect($hasil['sum']['belanja_barang_jasa_persen'])->toBe(50.0)
            ->and($hasil['chart'][1]['value'])->toBe(50.0);
    });

    it('tidak error dan bernilai nol saat total belanja nol', function () {
        buatAnggaranRealisasi([
            'total_belanja' => 0,
            'belanja_pegawai' => 100,
            'belanja_barang_jasa' => 200,
            'belanja_modal' => 300,
            'belanja_tidak_langsung' => 400,
        ]);

        $hasil = chartAnggaranRealisasi();

        expect($hasil['sum']['belanja_pegawai_persen'])->toBe(0.0)
            ->and($hasil['sum']['belanja_barang_jasa_persen'])->toBe(0.0)
            ->and($hasil['sum']['belanja_modal_persen'])->toBe(0.0)
            ->and($hasil['sum']['belanja_tidak_langsung_persen'])->toBe(0.0)
            ->and(array_column($hasil['chart'], 'value'))->each->toBe(0.0);
    });

    it('tidak error dan bernilai nol saat total anggaran nol', function () {
        buatAnggaranRealisasi([
            'total_anggaran' => 0,
            'total_belanja' => 500,
        ]);

        $hasil = chartAnggaranRealisasi();

        expect($hasil['sum']['total_belanja_persen'])->toBe(0.0);
    });

    it('menghitung semua persentase dengan benar untuk data normal', function () {
        buatAnggaranRealisasi([
            'total_anggaran' => 2000,
            'total_belanja' => 1000,
            'belanja_pegawai' => 100,
            'belanja_barang_jasa' => 200,
            'belanja_modal' => 300,
            'belanja_tidak_langsung' => 400,
        ]);

        $hasil = chartAnggaranRealisasi();

        expect($hasil['sum'])->toMatchArray([
            'total_belanja' => 1000.0,
            'total_belanja_persen' => 50.0,
            'belanja_pegawai_persen' => 10.0,
            'belanja_barang_jasa_persen' => 20.0,
            'belanja_modal_persen' => 30.0,
            'belanja_tidak_langsung_persen' => 40.0,
        ])->and(array_column($hasil['chart'], 'value'))->toBe([10.0, 20.0, 30.0, 40.0]);
    });

    it('mempertahankan struktur respons dan bernilai nol saat data tidak ada', function () {
        $hasil = chartAnggaranRealisasi();

        expect($hasil)->toHaveKeys(['sum', 'chart'])
            ->and($hasil['sum'])->toHaveKeys([
                'total_belanja',
                'total_belanja_persen',
                'selisih_anggaran_realisasi',
                'selisih_anggaran_realisasi_persen',
                'belanja_pegawai',
                'belanja_pegawai_persen',
                'belanja_barang_jasa',
                'belanja_barang_jasa_persen',
                'belanja_modal',
                'belanja_modal_persen',
                'belanja_tidak_langsung',
                'belanja_tidak_langsung_persen',
            ])
            ->and($hasil['sum'])->each->toBe(0.0)
            ->and(array_column($hasil['chart'], 'anggaran'))->toBe([
                'Belanja Pegawai',
                'Belanja Barang dan Jasa',
                'Belanja Modal',
                'Belanja Tidak Langsung',
            ]);
    });

    it('tidak error untuk pilihan tahun semua', function () {
        buatAnggaranRealisasi(['total_belanja' => 0, 'belanja_pegawai' => 100]);

        $hasil = (new AnggaranRealisasiChartService)->chart('Semua', 'Semua');

        expect($hasil)->toHaveKeys(['sum', 'chart']);
    });
});
