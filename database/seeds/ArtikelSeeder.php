<?php

use Faker\Factory;
use App\Models\Artikel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArtikelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Artikel::truncate();

        $faker = Factory::create("id-ID");

        foreach (range(1, 50) as $index) {
            Artikel::create([
                'judul' => $faker->sentence(),
                'gambar' => '/img/no-image.png',
                'isi' => $faker->paragraph(),
                'status' => 1, //$faker->randomElement([0, 1]),
                'created_at' => $faker->dateTimeThisYear(),
                'updated_at' => $faker->dateTimeThisYear(),
            ]);
        }
    }
}
