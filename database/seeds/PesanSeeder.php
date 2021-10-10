<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory;
use App\Models\DataDesa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class PesanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultCounter = 50;
        if(env("truncate", 0) === "1"){
            Schema::disableForeignKeyConstraints();
            $this->command->info("truncating...");
            DB::table('das_pesan')->truncate();
            Schema::enableForeignKeyConstraints();
        }

        $faker = Factory::create("id-ID");
        $das_desa = DataDesa::skip(0)->take(20)->pluck('id');
        $sample_enum = ["Surat Masuk", "Surat Keluar"];

        for ($i = 1; $i <= $defaultCounter; $i++) {
            DB::table("das_pesan")->insert([
                'judul' => $faker->text(50),
                'das_data_desa_id' => $faker->randomElement($das_desa),
                'jenis' => $faker->randomElement($sample_enum),
                'sudah_dibaca' => $faker->randomElement([0,1]),
                'diarsipkan' => $faker->randomElement([0,1]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

        }

    }
}
