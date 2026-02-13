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
                // Get the maximum ID from existing records and add a random increment
                // This ensures uniqueness while staying within integer range
                $maxId = Program::max('id') ?? 0;
                return $maxId + rand(1, 10000);
            },
            'nama' => $this->faker->words(3, true) . ' Program',
            'sasaran' => $this->faker->randomElement([1, 2]), // 1 = Penduduk/Perorangan, 2 = Keluarga-KK
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->dateTimeBetween('+1 month', '+1 year')->format('Y-m-d'),
            'description' => $this->faker->sentence(),
            'desa_id' => function () {
                return DataDesa::firstOrCreate(['nama' => 'Desa Contoh'], ['nama' => 'Desa Contoh', 'website' => 'https://example.com', 'luas_wilayah' => 10.5])->id;
            },
            'status' => $this->faker->randomElement([1, 0]), // 1 = aktif, 0 = tidak aktif
        ];
    }
}
