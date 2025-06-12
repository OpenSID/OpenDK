<?php

namespace Database\Factories;

use App\Models\JawabKomplain;
use App\Models\Komplain;
use Illuminate\Database\Eloquent\Factories\Factory;

class JawabKomplainFactory extends Factory
{
    protected $model = JawabKomplain::class;

    public function definition()
    {
        return [
            'komplain_id' => Komplain::factory(),
            'penjawab' => $this->faker->nik(),
            'jawaban' => $this->faker->sentence,
        ];
    }
}