<?php

namespace Database\Factories;

use App\Models\SuplemenTerdata;
use App\Models\Suplemen;
use App\Models\Penduduk;
use Illuminate\Database\Eloquent\Factories\Factory;

class SuplemenTerdataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SuplemenTerdata::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'suplemen_id' => function () {
                return Suplemen::factory()->create()->id;
            },
            'penduduk_id' => function () {
                return Penduduk::factory()->create()->id;
            },
            'keterangan' => $this->faker->sentence(),
        ];
    }
}
