<?php

namespace Database\Factories;

use App\Models\SettingAplikasi;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingAplikasiFactory extends Factory
{
    protected $model = SettingAplikasi::class;

    public function definition()
    {
        return [
            'key' => $this->faker->unique()->word,
            'value' => $this->faker->word,
            'type' => $this->faker->randomElement(['input', 'select', 'textarea']),
            'description' => $this->faker->sentence,
            'option' => json_encode($this->faker->words(3)), // Simulate options for select type
            'kategori' => $this->faker->randomElement(['aplikasi', 'surat', 'lainnya']),
        ];
    }
}