<?php

namespace Database\Factories;

use App\Models\Suplemen;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SuplemenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Suplemen::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $nama = $this->faker->words(3, true);

        return [
            'nama' => $nama,
            'slug' => Str::slug($nama),
            'sasaran' => $this->faker->randomElement([1, 2]), // 1 = Penduduk, 2 = Keluarga
            'keterangan' => $this->faker->sentence(),
        ];
    }
}
