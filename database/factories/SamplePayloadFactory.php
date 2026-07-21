<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SamplePayloadFactory extends Factory
{
    public function definition(): array
    {
        return [
            'data' => [
                'id' => $this->faker->uuid(),
                'type' => $this->faker->randomElement(['penduduk', 'keluarga', 'bantuan', 'laporan']),
                'attributes' => [
                    'name' => $this->faker->name(),
                    'description' => $this->faker->sentence(),
                    'amount' => $this->faker->numberBetween(100000, 10000000),
                    'quantity' => $this->faker->numberBetween(1, 100),
                    'is_active' => $this->faker->boolean(),
                ],
            ],
            'meta' => [
                'version' => '1.0',
                'timestamp' => now()->toIso8601String(),
            ],
        ];
    }

    public function penduduk(): static
    {
        return $this->state(fn (array $attributes) => [
            'data' => [
                'id' => $this->faker->uuid(),
                'type' => 'penduduk',
                'attributes' => [
                    'nik' => $this->faker->numerify('################'),
                    'nama' => $this->faker->name(),
                    'tempat_lahir' => $this->faker->city(),
                    'tanggal_lahir' => $this->faker->date(),
                    'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
                    'alamat' => $this->faker->address(),
                ],
            ],
            'meta' => [
                'version' => '1.0',
                'timestamp' => now()->toIso8601String(),
            ],
        ]);
    }
}
