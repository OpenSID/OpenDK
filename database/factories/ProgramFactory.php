<?php

namespace Database\Factories;

use App\Models\Program;
use App\Models\DataDesa;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProgramFactory extends Factory
{
    protected $model = Program::class;

    public function definition()
    {
        return [
            'id' => function () {
                // Generate unique ID by using microtime converted to an integer within safe range
                $micro = microtime(true);
                $id = intval(($micro - floor($micro)) * 1000000) + (time() % 1000000) * 100;
                // Ensure it's within integer range and positive
                return abs($id % 2000000000);
            },
            'nama' => $this->faker->words(3, true) . ' Program',
            'sasaran' => $this->faker->randomElement([1, 2]), // 1 = Penduduk/Perorangan, 2 = Keluarga-KK
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->dateTimeBetween('+1 month', '+1 year')->format('Y-m-d'),
            'description' => $this->faker->sentence(),
            'desa_id' => function () {
                return DataDesa::firstOrCreate(
                    ['nama' => 'Desa Contoh'],
                    [
                        'nama' => 'Desa Contoh',
                        'website' => 'https://example.com',
                        'luas_wilayah' => 10.5
                    ]
                )->desa_id;
            },
            'status' => $this->faker->randomElement([1, 0]), // 1 = aktif, 0 = tidak aktif
        ];
    }
}
