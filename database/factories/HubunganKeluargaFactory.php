<?php

namespace Database\Factories;

use App\Models\HubunganKeluarga;
use Illuminate\Database\Eloquent\Factories\Factory;

class HubunganKeluargaFactory extends Factory
{
    /**
     * The name of factory's corresponding model.
     *
     * @var string
     */
    protected $model = HubunganKeluarga::class;

    /**
     * Define model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama' => $this->faker->unique()->randomElement(['Kepala Keluarga', 'Suami', 'Istri', 'Anak', 'Menantu', 'Cucu', 'Orang Tua', 'Mertua', 'Famili Lain']),
        ];
    }
}