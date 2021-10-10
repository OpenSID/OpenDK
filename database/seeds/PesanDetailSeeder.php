<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Pesan;
use Carbon\Carbon;

class PesanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultCounter = 150;
        if(env("truncate", 0) === "1"){
            Schema::disableForeignKeyConstraints();
            $this->command->info("truncating...");
            DB::table('das_pesan_detail')->truncate();
            Schema::enableForeignKeyConstraints();
        }

        $faker = Factory::create("id-ID");
        $pesan = Pesan::skip(0)->take(20);
        $pesan_id = $pesan->pluck('id');
        $desa_id = $pesan->pluck('das_data_desa_id');

        for ($i = 1; $i <= $defaultCounter; $i++) {
            DB::table("das_pesan_detail")->insert([
                'pesan_id' => $faker->randomElement($pesan_id),
                'text' => $faker->text,
                'desa_id' => $faker->randomElement($desa_id),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

        }

    }
}
