<?php

namespace Database\Seeds\Demo;

use App\Models\Pesan;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DemoPesanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultCounter = 150;
        if (env("truncate", 0) === "1") {
            Schema::disableForeignKeyConstraints();
            $this->command->info("truncating...");
            DB::table('das_pesan_detail')->truncate();
            Schema::enableForeignKeyConstraints();
        }

        $faker = Factory::create("id-ID");
        $pesan = Pesan::skip(0)->take(20);
        $pesan_id = $pesan->pluck('id');
        $desa_id = $pesan->pluck('das_data_desa_id')->push(null);

        for ($i = 1; $i <= $defaultCounter; $i++) {
            DB::table("das_pesan_detail")->insert([
                'pesan_id' => $faker->randomElement($pesan_id),
                'text' => $faker->text,
                'desa_id' => $faker->randomElement($desa_id),
                'created_at' => $faker->dateTimeThisYear(),
                'updated_at' => $faker->dateTimeThisYear(),
            ]);
        }
    }
}
