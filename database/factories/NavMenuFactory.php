<?php

namespace Database\Factories;

use App\Models\NavMenu;
use Illuminate\Database\Eloquent\Factories\Factory;

class NavMenuFactory extends Factory
{
    protected $model = NavMenu::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(2, true),
            'url' => '/' . $this->faker->slug,
            'target' => $this->faker->randomElement(['_self', '_blank']),
            'type' => $this->faker->randomElement(['modul', 'link', 'kategori', 'dokumen']),
            'is_show' => $this->faker->boolean(90),
            'order' => $this->faker->numberBetween(1, 10),
            'parent_id' => null,
        ];
    }
}