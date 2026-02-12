<?php

namespace Database\Factories;

use App\Models\PendidikanKK;
use Illuminate\Database\Eloquent\Factories\Factory;

class PendidikanKKFactory extends Factory
{
    /**
     * The name of factory's corresponding model.
     *
     * @var string
     */
    protected $model = PendidikanKK::class;

    /**
     * Define model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama' => $this->faker->unique()->randomElement(['Tidak/Tidak Pernah Sekolah', 'Belum Tamat SD/SD', 'SLTP/Sederajat', 'SLTA/Sederajat', 'Diploma I/II', 'Akademi/Diploma III/Sarjana Muda', 'Diploma IV/Strata I', 'Strata II', 'Strata III']),
        ];
    }
}