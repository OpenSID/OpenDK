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
            'id' => $this->faker->unique()->numberBetween(1, 999999),
            'nama' => $this->faker->words(3, true) . ' Program',
            'sasaran' => $this->faker->randomElement([1, 2]), // 1 = Penduduk/Perorangan, 2 = Keluarga-KK
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->dateTimeBetween('+1 month', '+1 year')->format('Y-m-d'),
            'description' => $this->faker->sentence(),
            'desa_id' => function () {
                return DataDesa::factory()->create()->desa_id;
            },
            'status' => $this->faker->randomElement([1, 0]), // 1 = aktif, 0 = tidak aktif
        ];
    }
}
